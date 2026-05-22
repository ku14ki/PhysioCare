<?php

include 'db.php';

$id = $_GET['id'];

$sql = "

SELECT *
FROM therapists
WHERE therapist_id='$id'

";

$result = $conn->query($sql);

if($result->num_rows > 0){

    $therapist =
    $result->fetch_assoc();

    echo json_encode([

        "name" =>
        $therapist['t_name'],

        "specialization" =>
        $therapist['specialization'],

        "about" =>
        $therapist['about'],

        "experience" =>
        $therapist['experience'],

        "fee" =>
        $therapist['fee'],

        "image" =>
        $therapist['image']

    ]);

}
else{

    echo json_encode([
        "error" => "Therapist not found"
    ]);
}

?>