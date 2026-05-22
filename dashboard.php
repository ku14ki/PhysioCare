<?php

include "php/patient-auth.php";

include "php/db.php";

header(
    "Cache-Control: no-cache, no-store, must-revalidate"
);

header("Pragma: no-cache");

header("Expires: 0");

$patientId = $_SESSION['patient_id'];

$selectedTherapistId =
$_GET['id'] ?? "";

$appointmentsQuery = "

SELECT
    booking.booking_id,
    booking.date,
    booking.time,
    booking.status,
    therapists.t_name

FROM booking

INNER JOIN therapists
ON booking.therapist_id = therapists.therapist_id

WHERE booking.patient_id = '$patientId'

AND booking.date >= CURDATE()

ORDER BY booking.date ASC,
booking.time ASC

LIMIT 3

";

$appointmentsResult =
$conn->query($appointmentsQuery);

$paymentsQuery = "

SELECT
    booking.date,
    booking.time,
    therapists.t_name

FROM booking

INNER JOIN therapists
ON booking.therapist_id =
therapists.therapist_id

WHERE booking.patient_id =
'$patientId'

AND booking.status = 'Completed'

ORDER BY booking.date DESC,
booking.time DESC

LIMIT 3

";

$paymentsResult =
$conn->query($paymentsQuery);

?>


<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="dashboard">

<!-- SIDEBAR -->
<aside class="sidebar">
    <h2>PhysioCare</h2>
    <ul>
        <li class="active"
onclick="window.location.href='dashboard.php'"><i class="fa-solid fa-house"></i> Dashboard</li>
        <li class="active"
onclick="window.location.href='my-bookings.php'"><i class="fa-solid fa-list"></i> My Bookings</li>
        <li class="active"
onclick="window.location.href='my-profile.php'"><i class="fa-solid fa-user"></i> Profile</li>
    </ul>
</aside>

<!-- MAIN -->
<main class="dashboard-main">

    <!-- TOP -->
    <div class="top-bar">
    <h2>
Hello,
<?php echo explode(" ", $_SESSION['patient_name'])[0]; ?>
👋
</h2>

    <div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Search..." id="searchInput">
    <div class="search-results" id="results"></div>
</div>
</div>

    <!-- CARDS -->
    <div class="dashboard-cards" id="dashboardCards">

        <div class="highlight-card empty-state">

    <div>

        <h2>Welcome to PhysioCare 👋</h2>

        <p>
            You haven’t booked any appointments yet.
        </p>

        <span>
            Search therapists and book your first session.
        </span>

    </div>

    <button class="primary-btn"
    onclick="window.location.href='therapists.html'">

        Explore Therapists

    </button>

</div>

<div class="dashboard-card">

    <h3>My Appointments</h3>

    <?php

    if($appointmentsResult->num_rows > 0){

        while($appointment =
        $appointmentsResult->fetch_assoc()){

    ?>

        <div style="margin-top:15px;">

            <b>
                Dr.
                <?php
                echo $appointment['t_name'];
                ?>
            </b>

            <p>
                <?php
                echo $appointment['date'];
                ?>
                —
                <?php
                echo date(
                    "h:i A",
                    strtotime($appointment['time'])
                );
                ?>
            </p>

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

<span

style="
padding:6px 12px;
border-radius:20px;
background:<?php echo $bg; ?>;
color:<?php echo $color; ?>;
font-size:13px;
">

                <?php
                echo $appointment['status'];
                ?>

            </span>

            <?php
if(
    $appointment['status'] != 'Completed'
    &&
    $appointment['status'] != 'Rejected'
){
?>

<form
method="POST"
action="php/cancel-booking.php"
style="margin-top:10px;"
>

<input
type="hidden"
name="booking_id"
value="<?php
echo $appointment['booking_id'];
?>">

<button type="submit">

    Cancel Appointment

</button>

</form>

<?php
}
?>

        </div>

        <hr style="margin-top:15px;">

    <?php
        }

        echo '

<a href="#"
class="view-all-btn">

View All Appointments
<i class="fa-solid fa-arrow-right"></i>

</a>

';

    } else {

        echo "
        <p>
            No appointments booked yet
        </p>
        ";
    }

    ?>

</div>

        <div class="dashboard-card">

<h3>
Recent Payments
</h3>

<?php

if($paymentsResult->num_rows > 0){

while(
$payment =
$paymentsResult->fetch_assoc()
){

?>

<div class="payment-item">

<b>

Dr.
<?php
echo $payment['t_name'];
?>

</b>

<p>

₹500 •
<?php
echo $payment['date'];
?>

</p>

<span class="paid-badge">

Paid

</span>

</div>

<hr>

<?php
}
}
else{

echo "

<p>

No completed payments yet.

</p>

";

}

?>

</div>

        <div class="dashboard-card">

    <h3>Recent Activity</h3>

    <p>
        No recent activity available.
    </p>

</div>

    <div class="dashboard-card quick-card">
    <h3>Quick Actions</h3>

    <div class="quick-actions">
        <button class="action-btn">View Exercises</button>
        <button class="action-btn">My Reports</button>
    </div>
</div>

<div class="dashboard-card">
    <h3>Tip</h3>
    <p>Stay hydrated and maintain posture 👍</p>
</div>

    </div>

    <!-- SEARCH RESULT -->
    <div class="search-panel hidden" id="searchPanel"></div>

</main>

<!-- RIGHT PANEL -->
<aside class="right-panel">

    <div class="selected-therapist" id="selectedTherapist">
    <p style="opacity:0.7;">No therapist selected</p>
</div>

    <h3>Book Appointment <i class="fa-solid fa-calendar-check"></i></h3>


<div class="calendar-header">
    <button id="prevMonth"><i class="fa-solid fa-chevron-left"></i></button>
    <h4 id="monthYear"></h4>
    <button id="nextMonth"><i class="fa-solid fa-chevron-right"></i></button>
</div>

<div class="calendar" id="calendar"></div>

<label style="margin-top:15px; display:block;">Select Time</label>
<select id="timePicker" class="time-input">

    <option value="">
        Select Time
    </option>

    <option value="09:00:00">
        09:00 AM
    </option>

    <option value="10:00:00">
        10:00 AM
    </option>

    <option value="11:00:00">
        11:00 AM
    </option>

    <option value="12:00:00">
        12:00 PM
    </option>

    <option value="14:00:00">
        02:00 PM
    </option>

    <option value="15:00:00">
        03:00 PM
    </option>

    <option value="16:00:00">
        04:00 PM
    </option>

    <option value="17:00:00">
        05:00 PM
    </option>

</select>

<button class="payment-btn">
Make Payment
</button>
<p id="confirmation" style="margin-top:10px; font-size:14px;"></p>

</aside>

</div>

<script>

const therapistAvailability =
<?php
echo json_encode(
    $therapist['availability'] ?? ""
);
?>;

</script>

    <script src="js/script.js"></script>

    <script>

const selectedTherapistId =
"<?php echo $selectedTherapistId; ?>";

if(selectedTherapistId){

    fetch(
    `php/get_therapist.php?id=${selectedTherapistId}`
    )

    .then(res => res.json())

    .then(data => {

    selectedTherapist = data;

    selectedTherapist.id =
    selectedTherapistId;

    document
    .getElementById("selectedTherapist")
    .innerHTML = `

        <img
        src="php/uploads/${data.image}"
        style="
            width:70px;
            height:70px;
            border-radius:50%;
            object-fit:cover;
            margin-bottom:10px;
        ">

        <h4>
            Dr. ${data.name}
        </h4>

        <p>
            ${data.specialization}
        </p>

    `;

});

}

</script>

    <script>

history.pushState(null, null, location.href);

window.onpopstate = function () {

    history.go(1);
};

</script>

</body>
</html>
