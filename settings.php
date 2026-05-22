<?php

session_start();

if(!isset($_SESSION['therapist_id'])){

    header("Location: login.html");
    exit();
}

include "php/db.php";

$therapistId =
$_SESSION['therapist_id'];

$query = "

SELECT *

FROM therapists

WHERE therapist_id =
'$therapistId'

";

$result =
$conn->query($query);

$therapist =
$result->fetch_assoc();

if(isset($_POST['updateProfile'])){

    $name =
    $_POST['name'];

    $email =
    $_POST['email'];

    $phone =
    $_POST['phone'];

    $specialization =
    $_POST['specialization'];

    $updateQuery = "

    UPDATE therapists

    SET

    t_name = '$name',
    t_email = '$email',
    t_phn = '$phone',
    specialization = '$specialization'

    WHERE therapist_id =
    '$therapistId'

    ";

    if($conn->query($updateQuery)){

    $_SESSION['therapist_name']
    = $name;

    echo "

    <script>

    alert(
    'Profile updated successfully!'
    );

    window.location.href =
    'settings.php';

    </script>

    ";

    exit();
}
}
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
Settings
</title>

<link
rel="stylesheet"
href="css/profile.css">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

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

<li>

<a href="therapist-appointments.php">

<i class="fa-solid fa-calendar-check"></i>

<span>Appointments</span>

</a>

</li>

<li class="active">

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
Settings
</h1>

<p>
Manage your profile
</p>

</div>

</div>

<form
method="POST"
class="settings-form">

<div class="form-group">

<label>
Full Name
</label>

<input
type="text"
name="name"

value="<?php
echo $therapist['t_name'];
?>"

required>

</div>

<div class="form-group">

<label>
Email
</label>

<input
type="email"
name="email"

value="<?php
echo $therapist['t_email'];
?>"

required>

</div>

<div class="form-group">

<label>
Phone
</label>

<input
type="text"
name="phone"

value="<?php
echo $therapist['t_phn'];
?>"

required>

</div>

<div class="form-group">

<label>
Specialization
</label>

<input
type="text"
name="specialization"

value="<?php
echo $therapist['specialization'];
?>"

required>

</div>

<button
type="submit"
name="updateProfile"
class="save-btn">

Save Changes

</button>

</form>

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