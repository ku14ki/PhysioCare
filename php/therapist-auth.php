<?php

session_start();

if(
!isset($_SESSION['role'])
||
$_SESSION['role'] !== "therapist"
){

    header("Location: login.html");
    exit();
}
?>