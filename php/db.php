<?php

$conn = new mysqli(
    "127.0.0.1",
    "root",
    "",
    "physiocare"
);

if ($conn->connect_error) {
    die("Connection Failed");
}

?>