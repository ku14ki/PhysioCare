<?php

session_start();

if (!isset($_SESSION['patient_id'])) {

    header("Location: login.html");
    exit();
}

include "php/db.php";

header(
    "Cache-Control: no-cache, no-store, must-revalidate"
);

header("Pragma: no-cache");

header("Expires: 0");

$patientId = $_SESSION['patient_id'];

if(isset($_POST['updateProfile'])){

    $name =
    $_POST['name'];

    $email =
    $_POST['email'];

    $phone =
    $_POST['phone'];

    $updateQuery = "

    UPDATE patients

    SET

    p_name = '$name',
    p_email = '$email',
    p_phn = '$phone'

    WHERE patient_id =
    '$patientId'

    ";

    if($conn->query($updateQuery)){

        echo "

        <script>

        alert(
        'Profile updated successfully!'
        );

        window.location.href =
        'my-profile.php';

        </script>

        ";

        exit();
    }
}

$query = "

SELECT *

FROM patients

WHERE patient_id =
'$patientId'

";

$result =
$conn->query($query);

$patient =
$result->fetch_assoc();

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

<div class="dashboard-main">

<h1>
My Profile
</h1>

<form
method="POST"
class="profile-form">

<div class="profile-card">

<div class="form-group">

<label>
Full Name
</label>

<input
type="text"
name="name"

value="<?php
echo $patient['p_name'];
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
echo $patient['p_email'];
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
echo $patient['p_phn'];
?>"

required>

</div>

<button
type="submit"
name="updateProfile"
class="save-btn">

Save Changes

</button>

</div>

</form>

</div>