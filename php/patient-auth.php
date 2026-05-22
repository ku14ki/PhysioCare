<?php

session_start();

if(
!isset($_SESSION['role'])
||
$_SESSION['role'] !== "patient"
){

    header("Location: login.html");
    exit();
}
?>