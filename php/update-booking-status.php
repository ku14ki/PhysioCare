<?php

include "db.php";

$booking_id =
$_POST['booking_id'];

$status =
$_POST['status'];

$sql = "

UPDATE booking

SET status='$status'

WHERE booking_id='$booking_id'

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