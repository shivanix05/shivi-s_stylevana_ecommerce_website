<?php
$cn = mysqli_connect("localhost", "root", "123456789", "shivi-stylevana");
$s=$_REQUEST["r"];
$str ="delete from shop where pid=".$s;
mysqli_query($cn,$str) ;
header("location:product.php");
  ?>
  