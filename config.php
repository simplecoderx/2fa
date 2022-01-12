<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "2fa";

$conn = mysqli_connect($hostname, $username, $password, $database) or die("Database connection failed");

$base_url = "http://localhost/2fa/";
$my_email = "villacortalynn8@gmail.com"; //edit this, ilisi ug email add nga imong gamiton

?>