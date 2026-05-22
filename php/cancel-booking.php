<?php

include "db.php";

$booking_id =
$_POST['booking_id'];

$sql = "

UPDATE booking

SET status='Cancelled'

WHERE booking_id='$booking_id'

";

if($conn->query($sql)){

    header(
        "Location: ../dashboard.php"
    );

}
else{

    echo "Failed";

}

?>