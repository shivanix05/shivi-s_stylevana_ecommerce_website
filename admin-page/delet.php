<?php
$cn = mysqli_connect("localhost", "root", "root123", "shivi-stylevana");
$s=$_REQUEST["r"];
$str ="delete from userdetail where sno=".$s;
mysqli_query($cn,$str) ;
header("location:user-record.php");
  ?>
  