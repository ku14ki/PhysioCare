<?php

include "db.php";

$date =
$_GET['date'];

$therapist_id =
$_GET['therapist_id'];

$sql = "

SELECT time
FROM booking

WHERE therapist_id = '$therapist_id'
AND date = '$date'
AND status != 'Rejected'

";

$result =
$conn->query($sql);

$slots = [];

while($row =
$result->fetch_assoc()){

    $slots[] =
    $row['time'];
}

header(
    'Content-Type: application/json'
);

echo json_encode($slots);

?>