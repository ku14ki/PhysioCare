<?php

include "db.php";

$bookingId =
$_POST['booking_id'];

$notes =
mysqli_real_escape_string(
$conn,
$_POST['notes']
);

$sql = "

UPDATE booking

SET

notes='$notes',
status='Completed'

WHERE booking_id='$bookingId'

";

if($conn->query($sql)){

header(
"Location: ../therapist-appointments.php"
);

}
else{

echo "Failed";

}

?>