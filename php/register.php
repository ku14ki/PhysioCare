<?php

include 'db.php';

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);
$password = trim($_POST['password']);
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$role = $_POST['role'];

if($role == "patient"){

    // INSERT INTO patients

}
else if($role == "therapist"){

    // INSERT INTO therapists

}

// EMPTY CHECK
if (
    empty($name) ||
    empty($email) ||
    empty($phone) ||
    empty($address) ||
    empty($password)
) {

    die("empty");
}

// CHECK EMAIL
$check =
"SELECT * FROM patients
WHERE p_email='$email'";

$result = $conn->query($check);

if ($result->num_rows > 0) {

    die("exists");
}

// INSERT USER
$sql = "INSERT INTO patients

(p_name, p_email, p_phn, p_addr, passwd)

VALUES

('$name', '$email', '$phone', '$address', '$hashedPassword')";

if ($conn->query($sql)) {

    die("success");

} else {

    die("error");
}