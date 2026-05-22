<?php

session_start();

include "php/db.php";

if(!isset($_GET['id'])){

    echo "Appointment not found";

    exit();
}

$bookingId =
$_GET['id'];

$query = "

SELECT
booking.*,
patients.p_name

FROM booking

INNER JOIN patients
ON booking.patient_id =
patients.patient_id

WHERE booking.booking_id =
'$bookingId'

";

$result =
$conn->query($query);

if($result->num_rows == 0){

    echo "Appointment not found";

    exit();
}

$appointment =
$result->fetch_assoc();

?>

<!DOCTYPE html>

<html>

<head>

<title>
Complete Session
</title>

<link
rel="stylesheet"
href="css/profile.css">

</head>

<body>

<div class="details-card">

<h2>
Complete Session
</h2>

<p>

<b>Patient:</b>

<?php
echo $appointment['p_name'];
?>

</p>

<p>

<b>Date:</b>

<?php
echo $appointment['date'];
?>

</p>

<p>

<b>Time:</b>

<?php
echo date(
"h:i A",
strtotime(
$appointment['time']
)
);
?>

</p>

<form
method="POST"
action="php/save-session-notes.php">

<input
type="hidden"
name="booking_id"
value="<?php
echo $bookingId;
?>">

<textarea

name="notes"

placeholder="Write session notes..."

required

style="
width:100%;
height:150px;
padding:15px;
margin-top:20px;
border-radius:10px;
"

></textarea>

<br><br>

<button
type="submit"
class="action-btn complete-btn">

Save & Complete Session

</button>

</form>

</div>

</body>

</html>