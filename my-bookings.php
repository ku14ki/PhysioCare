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

$bookingsQuery = "

SELECT
    booking.booking_id,
    booking.date,
    booking.time,
    booking.status,
    therapists.t_name

FROM booking

INNER JOIN therapists
ON booking.therapist_id =
therapists.therapist_id

WHERE booking.patient_id =
'$patientId'

ORDER BY booking.date DESC,
booking.time DESC

";

$bookingsResult =
$conn->query($bookingsQuery);

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
My Bookings
</h1>

<div class="bookings-container">

<?php

if($bookingsResult->num_rows > 0){

while(
$booking =
$bookingsResult->fetch_assoc()
){

$status =
$booking['status'];

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

<div class="booking-card">

<h3>

Dr.
<?php
echo $booking['t_name'];
?>

</h3>

<p>

<?php
echo $booking['date'];
?>

—

<?php

echo date(
"h:i A",
strtotime(
$booking['time']
)
);

?>

</p>

<span
class="status-badge"

style="
background:<?php echo $bg; ?>;
color:<?php echo $color; ?>;
">

<?php
echo $status;
?>

</span>

<?php

if(
$status != 'Completed'
&&
$status != 'Rejected'
&&
$status != 'Cancelled'
){

?>

<form
method="POST"
action="php/cancel-booking.php"
style="margin-top:15px;"
>

<input
type="hidden"
name="booking_id"

value="<?php
echo $booking['booking_id'];
?>">

<button
type="submit"
class="cancel-btn">

Cancel Appointment

</button>

</form>

<?php
}
?>

</div>

<?php
}
}
else{

echo "

<p>

No bookings found.

</p>

";

}

?>

</div>

</div>

</div>

</body>