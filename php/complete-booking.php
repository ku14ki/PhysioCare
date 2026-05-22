<?php

include "db.php";

$booking_id =
$_POST['booking_id'];

$sql = "

UPDATE booking

SET status='Completed'

WHERE booking_id='$booking_id'

";

if($conn->query($sql)){

    header(
        "Location: ../profile.php"
    );

}
else{

    echo "Failed";

}

?>