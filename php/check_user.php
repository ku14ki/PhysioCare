<?php

include 'db.php';

$email = $_POST['email'];

$sql =
"SELECT * FROM patients
WHERE p_email='$email'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    echo "exists";

} else {

    echo "new";
}