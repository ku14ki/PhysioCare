<?php

session_start();

include 'db.php';

header('Content-Type: application/json');

if(!isset($_SESSION['patient_id'])){

    echo json_encode([
        "success" => false,
        "message" => "Please login first"
    ]);

    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$patient_id = $_SESSION['patient_id'];

$therapist_id = $data['therapist_id'];

$raw_date = $data['booking_date'];

$dateParts = explode("/", $raw_date);

$date = $dateParts[2] . "-" .
        $dateParts[1] . "-" .
        $dateParts[0];

$booking_date =
$dateParts[2] . "-" .
$dateParts[1] . "-" .
$dateParts[0];

$booking_time =
$data['booking_time'];

if(empty($booking_time)){

    echo json_encode([
        "success" => false,
        "message" => "Invalid booking time"
    ]);

    exit();
}

$hour = date(
    "H",
    strtotime($booking_time)
);

if($hour < 9 || $hour > 20){

    echo json_encode([
        "success" => false,
        "message" =>
        "Please select time between 9 AM and 8 PM"
    ]);

    exit();
}

$checkQuery = "

SELECT *
FROM booking

WHERE therapist_id = '$therapist_id'
AND date = '$booking_date'
AND status != 'Rejected'

AND ABS(

    TIME_TO_SEC(
        TIMEDIFF(
            time,
            '$booking_time'
        )
    )

) < 3600

";

$checkResult =
$conn->query($checkQuery);

if($checkResult->num_rows > 0){

    echo "slot_taken";

    exit();
}

$sql = "

INSERT INTO booking
(therapist_id, patient_id, date, time, status)

VALUES
('$therapist_id', '$patient_id', '$booking_date', '$booking_time', 'Pending')

";

if(mysqli_query($conn, $sql)){

    echo json_encode([
        "success" => true,
        "message" => "Appointment booked successfully"
    ]);

}else{

    echo json_encode([
        "success" => false,
        "message" => "Booking failed"
    ]);
}

?>