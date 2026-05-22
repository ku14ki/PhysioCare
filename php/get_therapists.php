<?php

include 'db.php';

$sql = "

SELECT

therapist_id,
specialization,
experience,
t_name,
t_email,
t_phn,
availability,
passwd,
about,
fee,
image

FROM therapists

";

$result = mysqli_query($conn, $sql);

$therapists = [];

while($row = mysqli_fetch_assoc($result)){

    $therapists[] = $row;
}

header('Content-Type: application/json');

echo json_encode($therapists);

?>