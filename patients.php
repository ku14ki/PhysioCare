<?php

include "php/therapist-auth.php";

include "php/db.php";

$therapistId =
$_SESSION['therapist_id'];

$query = "

SELECT
patients.patient_id,
patients.p_name,
patients.p_email,
patients.p_phn,

COUNT(booking.booking_id)
AS total_appointments,

MAX(booking.date)
AS last_visit,

MAX(booking.status)
AS latest_status

FROM booking

INNER JOIN patients
ON booking.patient_id =
patients.patient_id

WHERE booking.therapist_id =
'$therapistId'

GROUP BY patients.patient_id

ORDER BY last_visit DESC

";

$result =
$conn->query($query);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width,
initial-scale=1.0">

<title>
Patients
</title>

<link
rel="stylesheet"
href="css/profile.css">

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

<li>

<a href="profile.php">

<i class="fa-solid fa-house"></i>

<span>Dashboard</span>

</a>

</li>

<li class="active">

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

<div class="main">

<div class="appointments-page">

<div class="dashboard-header">

<div>

<h1>
Patients
</h1>

<p>
Manage your patients
</p>

</div>

</div>

<div class="top-controls">

<div class="search-box">

<input
type="text"
id="patientSearch"
placeholder="Search patient..."
onkeyup="searchPatients()">

</div>

</div>

<table class="appointment-table">

<thead>

<tr>

<th>Patient</th>
<th>Phone</th>
<th>Email</th>
<th>Appointments</th>
<th>Last Visit</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody id="patientsTable">

<?php

if($result->num_rows > 0){

while(
$row =
$result->fetch_assoc()
){

?>

<tr>

<td>

<div
style="
display:flex;
align-items:center;
gap:12px;
">

<img
src="images/reviewer3.png"
style="
width:45px;
height:45px;
border-radius:50%;
">

<?php
echo $row['p_name'];
?>

</div>

</td>

<td>

<?php
echo $row['p_phn'];
?>

</td>

<td>

<?php
echo $row['p_email'];
?>

</td>

<td>

<?php
echo $row['total_appointments'];
?>

</td>

<td>

<?php
echo $row['last_visit'];
?>

</td>

<td>

<span
class="status-badge
<?php
echo strtolower(
$row['latest_status']
);
?>">

<?php
echo $row['latest_status'];
?>

</span>

</td>

<td>

<a
href="
patient-profile.php?id=
<?php
echo $row['patient_id'];
?>
">

<button
class="action-btn profile-btn">

View Profile

</button>

</a>

</td>

</tr>

<?php
}
}
?>

</tbody>

</table>

<script>

function searchPatients(){

    const input =
    document.getElementById(
    "patientSearch"
    ).value.toLowerCase();

    const rows =
    document.querySelectorAll(
    "#patientsTable tr"
    );

    rows.forEach(row => {

        const text =
        row.innerText
        .toLowerCase();

        row.style.display =
        text.includes(input)
        ? ""
        : "none";

    });
}

</script>

</div>

</div>
</div>

<script>

function toggleSidebar(){

    const sidebar =
    document.getElementById(
    "sidebar"
    );

    const main =
    document.querySelector(
    ".main"
    );

    sidebar.classList.toggle(
    "collapsed"
    );

    main.classList.toggle(
    "expanded"
    );
}

</script>

</body>

</html>