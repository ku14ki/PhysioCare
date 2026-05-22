<?php
include "php/therapist-auth.php";

include "php/db.php";

$email = $_SESSION['therapist_email'];

$sql = "SELECT * FROM therapists
        WHERE t_email='$email'";

$result = $conn->query($sql);

$therapist = $result->fetch_assoc();

$therapistId = $therapist['therapist_id'];

// TOTAL PATIENTS

$patientsQuery = "

SELECT COUNT(DISTINCT patient_id)
AS total

FROM booking

WHERE therapist_id = '$therapistId'

";

$patientsResult = $conn->query($patientsQuery);

$totalPatients =
$patientsResult->fetch_assoc()['total'];


// TOTAL CONSULTATIONS
// temporary static value

$consultationQuery = "

SELECT COUNT(*) AS total

FROM booking

WHERE therapist_id = '$therapistId'

";

$consultationResult =
$conn->query($consultationQuery);

$totalConsultations =
$consultationResult->fetch_assoc()['total'];

$recentPatientsQuery = "

SELECT
    patients.patient_id,
    patients.p_name,

    MAX(booking.status)
    AS status

FROM booking

INNER JOIN patients
ON booking.patient_id =
patients.patient_id

WHERE booking.therapist_id =
'$therapistId'

GROUP BY patients.patient_id

ORDER BY MAX(booking.booking_id) DESC

LIMIT 5

";

$recentPatientsResult =
$conn->query($recentPatientsQuery);

$weeklyQuery = "

SELECT
    DAYNAME(date) AS day,
    COUNT(*) AS total

FROM booking

WHERE therapist_id = '$therapistId'
AND status IN ('Accepted', 'Completed')

GROUP BY DAYNAME(date)

";

$weeklyResult =
$conn->query($weeklyQuery);

$days = [];
$totals = [];

while($row =
$weeklyResult->fetch_assoc()){

    $days[] =
    $row['day'];

    $totals[] =
    $row['total'];
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/profile.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="dashboard">

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">

    <div class="sidebar-header">
    <span class="logo-text">PhysioCare</span>

    <div class="toggle-btn" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </div>
    </div>

    <ul class="nav">

<li class="active">

<a href="profile.php">

<i class="fa-solid fa-house"></i>

<span>Dashboard</span>

</a>

</li>

<li>

<a href="patients.php">

<i class="fa-solid fa-user-injured"></i>

<span>Patients</span>

</a>

</li>

<li>

<a href="therapist-appointments.php">

<i class="fa-solid fa-calendar-check"></i>

<span>Appointments</span>

</a>

</li>

<li>

<a href="settings.php">

<i class="fa-solid fa-gear"></i>

<span>Settings</span>

</a>

</li>

</ul>

</div>

    <!-- Main Content -->
    <div class="main" id="mainContent">

        <div
class="topbar">
            <input
type="text"
id="patientSearch"
placeholder="Search patients..."
onkeyup="searchDashboardPatients()">

<div id="patientResults"></div>

        </div>

        <div class="welcome-card">
            <h2>
    Good Day,
    Dr.
    <?php echo explode(" ", $_SESSION['therapist_name'])[0]; ?>
    👋
</h2>
            <p>Have a great day helping patients recover!</p>
        </div>

        <div class="stats">

    <div class="stat-card">
        <i class="fa-solid fa-users"></i>

        <h3>
            <?php echo $totalPatients; ?>
        </h3>

        <p>Patients</p>
    </div>

    <div class="stat-card">
        <i class="fa-solid fa-stethoscope"></i>

        <h3>
            <?php echo $totalConsultations; ?>
        </h3>

        <p>Consultations</p>
    </div>

    <div class="stat-card">
        <i class="fa-solid fa-file-medical"></i>

        <h3>
            <?php echo 0; ?>
        </h3>

        <p>Reports</p>
    </div>

</div>

        <div class="analytics">

    <div class="chart-card">
        <h4>Weekly Consultations</h4>
        <canvas id="consultChart"></canvas>
    </div>

    <div class="chart-card">
        <h4>Patient Distribution</h4>
        <canvas id="patientChart"></canvas>
    </div>

</div>

<div class="appointments">

    <h3>Today's Appointments</h3>

    <?php

    $therapistId = $_SESSION['therapist_id'];

    $today = date("Y-m-d");

    $appointmentQuery = "
        SELECT
    booking.booking_id,
    booking.time,
    booking.status,
    patients.p_name
        FROM booking
        INNER JOIN patients
        ON booking.patient_id = patients.patient_id
        WHERE booking.therapist_id = '$therapistId'
        AND booking.date = CURDATE()
        ORDER BY booking.time ASC
    ";

    $appointmentResult =
    $conn->query($appointmentQuery);

    if($appointmentResult->num_rows > 0){

        $count = 0;

        while($appointment =
        $appointmentResult->fetch_assoc()){

    ?>

        <div

class="appointment

<?php

if($count >= 3){

echo 'extra-appointment';

}

?>

"
>

    <div>

        <span>
            <?php
            echo date(
                "h:i A",
                strtotime($appointment['time'])
            );
            ?>
        </span>

        <span>
            Consultation with
            <?php
            echo $appointment['p_name'];
            ?>
        </span>

        <?php

$status = $appointment['status'];

$bg = "#eee";
$color = "#333";

if($status == "Pending"){

    $bg = "#fff3cd";
    $color = "#856404";
}

else if($status == "Accepted"){

    $bg = "#d4edda";
    $color = "#155724";
}

else if($status == "Rejected"){

    $bg = "#f8d7da";
    $color = "#721c24";
}

else if($status == "Cancelled"){

    $bg = "#e2e3e5";
    $color = "#383d41";
}

else if($status == "Completed"){

    $bg = "#d1ecf1";
    $color = "#0c5460";
}

?>

<p style="margin-top:8px;">

    Status:

    <span

    style="
    padding:6px 12px;
    border-radius:20px;
    background:<?php echo $bg; ?>;
    color:<?php echo $color; ?>;
    font-size:13px;
    ">

        <?php
        echo $status;
        ?>

    </span>

</p>

    </div>

    <?php
    if($appointment['status'] == 'Pending'){
    ?>

        <div>

            <a href="
            php/update-booking-status.php?
            id=<?php echo $appointment['booking_id']; ?>
            &status=Accepted
            ">

                <button>
                    Accept
                </button>

            </a>

            <a href="
            php/update-booking-status.php?
            id=<?php echo $appointment['booking_id']; ?>
            &status=Rejected
            ">

                <button>
                    Reject
                </button>

            </a>

        </div>

    <?php
    }
    ?>

    <?php
if($appointment['status'] == 'Accepted'){
?>

<form
method="POST"
action="php/complete-booking.php">

    <input
    type="hidden"
    name="booking_id"
    value="<?php
    echo $appointment['booking_id'];
    ?>">

    <button type="submit">

        Complete Session

    </button>

</form>

<?php
}
?>

</div>

<?php
$count++;
?>

    <?php
        }

        echo '

<button
class="see-more-btn"
onclick="toggleAppointments()">

See More

</button>

';

    } else {

        echo "
        <p>
            No appointments today
        </p>
        ";
    }

    ?>

</div>

        <div class="patients">

    <h3>Recent Patients</h3>

    <?php
if($recentPatientsResult->num_rows > 0){

    while($patient =
    $recentPatientsResult->fetch_assoc()){
?>

    <div class="patient">

        <img src="images/reviewer3.png">

        <div>

            <h4>
                <?php
                echo $patient['p_name'];
                ?>
            </h4>

            <p>
                Patient ID:
                <?php
                echo $patient['patient_id'];
                ?>
            </p>

        </div>

        <span class="status active">

    <?php
    echo $patient['status'];
    ?>

</span>

    </div>

<?php
    }
}
else{

    echo "<p>No patients found</p>";
}
?>

</div>

    </div>

    <!-- Right Panel -->
    <div class="right-panel">

        <div class="profile-card">
            <img src="php/uploads/<?php echo $therapist['image']; ?>">
            <h3>
    Dr.
    <?php echo $_SESSION['therapist_name']; ?>
</h3>
            <p>
    <?php echo $therapist['specialization']; ?>
</p>
        </div>

        <div class="calendar-card">

<h3>

<?php
echo date("F Y");
?>

</h3>

<div class="calendar-grid">

<?php

$totalDays =
date("t");

$currentDay =
date("j");

$bookedDates = [];

$bookedQuery = "

SELECT DISTINCT DAY(date)
AS booked_day

FROM booking

WHERE therapist_id =
'$therapistId'

AND MONTH(date) =
MONTH(CURDATE())

AND YEAR(date) =
YEAR(CURDATE())

";

$bookedResult =
$conn->query($bookedQuery);

while(
$row =
$bookedResult->fetch_assoc()
){

$bookedDates[] =
$row['booked_day'];
}

for(
$i = 1;
$i <= $totalDays;
$i++
){

$isToday =
$i == $currentDay;

$isBooked =
in_array(
$i,
$bookedDates
);

?>

<div

data-date="<?php echo $i; ?>"

onclick="
selectCalendarDate(this)
"

title="

<?php

if($isBooked){

echo 'Appointments Available';

}
else{

echo 'No Appointments';

}

?>

"

class="calendar-date

<?php

if($isToday){

echo 'active-date';

}

if($isBooked){

echo ' booked-date';

}

?>

"

>

<?php
echo $i;
?>

</div>

<?php
}
?>

</div>

<script>

const appointments = {

<?php

$allAppointmentsQuery = "

SELECT date,
time,
status

FROM booking

WHERE therapist_id =
'$therapistId'

ORDER BY date ASC

";

$allAppointmentsResult =
$conn->query(
$allAppointmentsQuery
);

$data = [];

while(
$row =
$allAppointmentsResult
->fetch_assoc()
){

$day =
date(
"j",
strtotime(
$row['date']
)
);

$data[$day][] = [

"time" =>
date(
"h:i A",
strtotime(
$row['time']
)
),

"status" =>
$row['status']

];
}

foreach(
$data as $day => $items
){

echo "'$day':[";

foreach($items as $item){

echo "{

time:'".$item['time']."',

status:'".$item['status']."'

},";

}

echo "],";
}

?>

};

</script>

<div class="schedule">

<h4>
Schedule
</h4>

<div id="scheduleContent">

<p>
Select a date
</p>

</div>

</div>

</div>

</div>


<script src="js/script.js"></script>

<script>

const consultCtx =
document.getElementById(
    "consultChart"
);

if(consultCtx){

new Chart(consultCtx, {

    type: "bar",

    data: {

        labels:
        <?php echo json_encode($days); ?>,

        datasets: [{

            label:
            "Consultations",

            data:
            <?php echo json_encode($totals); ?>,

            borderWidth: 1
        }]
    },

    options: {

        responsive: true
    }
});

}

</script>

<script>

function selectCalendarDate(element){

    document
    .querySelectorAll(
    ".calendar-date"
    )
    .forEach(date => {

        date.classList.remove(
        "selected-date"
        );

    });

    element.classList.add(
    "selected-date"
    );

    const selectedDay =
    element.dataset.date;

    const scheduleContent =
    document.getElementById(
    "scheduleContent"
    );

    const dayAppointments =
    appointments[selectedDay];

    if(
    dayAppointments &&
    dayAppointments.length > 0
    ){

        let html = "";

        dayAppointments.forEach(item => {

            html += `

            <p>

            <b>${item.time}</b>

            ${item.status}

            </p>

            `;

        });

        scheduleContent.innerHTML =
        html;
    }
    else{

        scheduleContent.innerHTML =

        `
        <p>
        No appointments
        </p>
        `;
    }
}

</script>

<script>

let expanded = false;

function toggleAppointments(){

    const hiddenItems =
    document.querySelectorAll(
    ".extra-appointment"
    );

    const button =
    document.querySelector(
    ".see-more-btn"
    );

    hiddenItems.forEach(item => {

        if(expanded){

            item.style.display =
            "none";
        }
        else{

            item.style.display =
            "block";
        }

    });

    if(expanded){

        button.innerText =
        "See More";
    }
    else{

        button.innerText =
        "Show Less";
    }

    expanded = !expanded;
}

</script>

<script>

function searchDashboardPatients(){

    const input =
    document.getElementById(
    "patientSearch"
    ).value.toLowerCase();

    const resultsBox =
    document.getElementById(
    "patientResults"
    );

    const patients =
    document.querySelectorAll(
    ".patient"
    );

    resultsBox.innerHTML = "";

    if(input === ""){

        resultsBox.style.display =
        "none";

        return;
    }

    let found = false;

    patients.forEach(patient => {

        const text =
        patient.innerText.toLowerCase();

        if(text.includes(input)){

            found = true;

            const name =
            patient.querySelector("h4")
            ?.innerText;

            const status =
            patient.querySelector(".status")
            ?.innerText;

            const div =
            document.createElement("div");

            div.classList.add(
            "search-result-item"
            );

            div.innerHTML = `

                <h4>${name}</h4>

                <p>${status}</p>

            `;

            div.onclick = () => {

                patient.scrollIntoView({

                    behavior:"smooth",

                    block:"center"
                });

                resultsBox.style.display =
                "none";
            };

            resultsBox.appendChild(div);
        }
    });

    resultsBox.style.display =
    found ? "block" : "none";
}

</script>

</body>
</html>