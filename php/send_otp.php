<?php

include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';
require '../phpmailer/Exception.php';

$email = $_POST['email'];

$otp = rand(100000, 999999);

// DELETE OLD OTP
$conn->query(
"DELETE FROM otp_verification
WHERE email='$email'"
);

// INSERT NEW OTP
$conn->query(
"INSERT INTO otp_verification(email, otp)
VALUES('$email', '$otp')"
);

// SEND MAIL
$mail = new PHPMailer(true);

try {

    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    $mail->Username = 'physiocarehv2026@gmail.com';

    $mail->Password = 'mywp ddqk vqqm qcgh';

    $mail->SMTPSecure = 'tls';

    $mail->Port = 587;

    $mail->setFrom(
        'physiocarehv2026@gmail.com',
        'PhysioCare'
    );

    $mail->addAddress($email);

    $mail->isHTML(true);

    $mail->Subject =
    'PhysioCare OTP Verification';

    $mail->Body =
    "<h2>Your OTP is: $otp</h2>";

    $mail->send();

    echo "success";

}

catch (Exception $e) {

    echo "error";
}