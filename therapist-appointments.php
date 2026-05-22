<?php

include "php/therapist-auth.php";

include "php/db.php";

$therapistId =
$_SESSION['therapist_id'];

$appointmentsQuery = "

SELECT
    booking.booking_id,
    booking.date,
    booking.time,
    booking.status,
    patients.patient_id,
    patients.p_name

FROM booking

INNER JOIN patients
ON booking.patient_id = patients.patient_id

WHERE booking.therapist_id = '$therapistId'

ORDER BY booking.date ASC,
booking.time ASC

";

$pendingCount = $conn->query(

"SELECT COUNT(*) AS total
FROM booking
WHERE therapist_id='$therapistId'
AND status='Pending'"

)->fetch_assoc()['total'];

$acceptedCount = $conn->query(

"SELECT COUNT(*) AS total
FROM booking
WHERE therapist_id='$therapistId'
AND status='Accepted'"

)->fetch_assoc()['total'];

$completedCount = $conn->query(

"SELECT COUNT(*) AS total
FROM booking
WHERE therapist_id='$therapistId'
AND status='Completed'"

)->fetch_assoc()['total'];

$cancelledCount = $conn->query(

"SELECT COUNT(*) AS total
FROM booking
WHERE therapist_id='$therapistId'
AND status='Cancelled'"

)->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/profile.css">
    <link
rel="stylesheet"
href="
https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css
">

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

<li>

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

<li class="active">

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

<div class="main" id="mainContent">

<div class="dashboard-container appointments-page">

<div class="dashboard-header">

    <div>

        <h1>
            Appointments
        </h1>

        <p>
            Manage patient bookings and sessions
        </p>

    </div>

</div>

<div class="top-controls">

    <div class="left-controls">

        <div class="search-box">

            <input
            type="text"
            id="searchAppointment"
            placeholder="Search patient...">

        </div>

        <input
        type="date"
        id="dateFilter">

    </div>

    <div class="filters">

        <button onclick="filterAppointments('All')" class="active-filter">
            All
        </button>

        <button onclick="filterAppointments('Pending')">
            Pending
        </button>

        <button onclick="filterAppointments('Accepted')">
            Accepted
        </button>

        <button onclick="filterAppointments('Completed')">
            Completed
        </button>

        <button onclick="filterAppointments('Cancelled')">
            Cancelled
        </button>

        <button onclick="filterAppointments('Rejected')">
            Rejected
        </button>

    </div>

</div>

<div class="appointment-stats">

<div class="mini-card pending-card">
<h3><?php echo $pendingCount; ?></h3>
<p>Pending</p>
</div>

<div class="mini-card accepted-card">
<h3><?php echo $acceptedCount; ?></h3>
<p>Accepted</p>
</div>

<div class="mini-card completed-card">
<h3><?php echo $completedCount; ?></h3>
<p>Completed</p>
</div>

<div class="mini-card cancelled-card">
<h3><?php echo $cancelledCount; ?></h3>
<p>Cancelled</p>
</div>

</div>

</div>

<table class="appointment-table">

<thead>
<tr>
    <th onclick="sortTable(0, 'text')">

    <div class="th-content">
        Patient
        <span class="sort-icon">↕</span>
    </div>

</th>

<th onclick="sortTable(1, 'date')">

    <div class="th-content">
        Date
        <span class="sort-icon">↕</span>
    </div>

</th>

<th onclick="sortTable(2, 'time')">

    <div class="th-content">
        Time
        <span class="sort-icon">↕</span>
    </div>

</th>

    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php

$appointmentsResult =
$conn->query($appointmentsQuery);

if($appointmentsResult->num_rows > 0){

while(
$appointment =
$appointmentsResult->fetch_assoc()
){

?>

<tr
class="appointment-row"

data-status="<?php
echo $appointment['status'];
?>"

data-patient="<?php
echo strtolower(
    $appointment['p_name']
);
?>"

data-date="<?php
echo $appointment['date'];
?>">

    <td>
        <?php
        echo $appointment['p_name'];
        ?>
    </td>

    <td>
        <?php
        echo $appointment['date'];
        ?>
    </td>

    <td>
        <?php
        echo date(
            "h:i A",
            strtotime(
                $appointment['time']
            )
        );
        ?>
    </td>

    <td>

<span class="status-badge <?php
echo strtolower($appointment['status']);
?>">

<?php
echo $appointment['status'];
?>

</span>

</td>

<td>

<div class="action-dropdown">

<button
type="button"
class="action-menu-btn"
onclick="toggleDropdown(this)">

Actions

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="action-dropdown-content">

<a

href="
patient-profile.php?id=
<?php
echo $appointment['patient_id'];
?>
"

>

View Profile

</a>

<?php
if(
$appointment['status']
== 'Pending'
){
?>

<form
method="POST"
action="php/update-booking-status.php">

<input
type="hidden"
name="booking_id"
value="<?php
echo $appointment['booking_id'];
?>">

<input
type="hidden"
name="status"
value="Accepted">

<button type="submit">

Accept

</button>

</form>

<form
method="POST"
action="php/update-booking-status.php">

<input
type="hidden"
name="booking_id"
value="<?php
echo $appointment['booking_id'];
?>">

<input
type="hidden"
name="status"
value="Rejected">

<button type="submit">

Reject

</button>

</form>

<?php
}
?>

<?php
if(
$appointment['status']
== 'Accepted'
){
?>

<a

href="
complete-session.php?id=
<?php
echo $appointment['booking_id'];
?>
"

>

Complete

</a>

<?php
}
?>

</div>

</div>

</td>

</tr>

<?php
}
}
else{

echo "
<tr>
<td colspan='5'>
No appointments found
</td>
</tr>
";

}
?>

</tbody>

</table>

<script>

function filterAppointments(status){

const rows =
document.querySelectorAll(
'.appointment-row'
);

rows.forEach(row => {

const rowStatus =
row.dataset.status;

if(
    status === 'All'
    ||
    rowStatus === status
){

    row.style.display =
    '';

}
else{

    row.style.display =
    'none';
}

});

}

const searchInput =
document.getElementById(
'searchAppointment'
);

searchInput.addEventListener(
'input',
function(){

const value =
this.value.toLowerCase();

const rows =
document.querySelectorAll(
'.appointment-row'
);

rows.forEach(row => {

const patient =
row.dataset.patient;

if(
    patient.includes(value)
){

    row.style.display =
    '';

}
else{

    row.style.display =
    'none';
}

});

});

const dateFilter =
document.getElementById(
'dateFilter'
);

dateFilter.addEventListener(
'change',
function(){

const selectedDate =
this.value;

const rows =
document.querySelectorAll(
'.appointment-row'
);

rows.forEach(row => {

const rowDate =
row.dataset.date;

if(
    selectedDate === ''
    ||
    rowDate === selectedDate
){

    row.style.display =
    '';

}
else{

    row.style.display =
    'none';
}

});

});

</script>

</div>

<script src=js/script.js></script>

<script>

function toggleDropdown(button){

    let dropdown =
    button.nextElementSibling;

    document
    .querySelectorAll(
    ".action-dropdown-content"
    )
    .forEach(menu => {

        if(menu !== dropdown){

            menu.classList.remove(
            "show"
            );
        }

    });

    dropdown.classList.toggle(
    "show"
    );
}

document.addEventListener(
"click",
function(e){

    if(
    !e.target.closest(
    ".action-dropdown"
    )
    ){

        document
        .querySelectorAll(
        ".action-dropdown-content"
        )
        .forEach(menu => {

            menu.classList.remove(
            "show"
            );

        });
    }
});

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

        item.style.display =
        expanded
        ? "none"
        : "block";

    });

    button.innerText =
    expanded
    ? "See More"
    : "Show Less";

    expanded = !expanded;
}

</script>

</div>
</div>

</body>
</html>