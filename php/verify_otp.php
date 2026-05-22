<?php

include 'db.php';

$email = $_POST['email'];
$otp = $_POST['otp'];

$sql =
"SELECT * FROM otp_verification

WHERE email='$email'
AND otp='$otp'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    echo "success";

} else {

    echo "invalid";
}