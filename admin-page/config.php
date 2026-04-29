<?php

$host = "localhost";
$user = "root";
$pass = "root123"; 
$dbname = "shivi-stylevana";


$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("notconnected succesfully: " . mysqli_connect_error());
}

date_default_timezone_set('Asia/Kolkata');

?>