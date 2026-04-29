<?php
include("function.php");
$cn = make_connection();
$rid = $_GET['rid'];
mysqli_query($cn, "DELETE FROM reviews WHERE rid='$rid'");
header("location:review.php");
?>