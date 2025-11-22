<?php include("config.php"); 

session_start();
if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();
}
?>
<?php
    if (isset($_POST["logoutbtn"])){
        session_destroy();
        header("location:login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="afterl-style.css">
   
</head>
<body>

    <?php
include("header.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $pid = $_POST['pid'];
    $name = $_POST['name'];
     $gmail = $_POST['gmail'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $payment = $_POST['payment'];
   


    $query = "INSERT INTO orders (product_id, customer_name,gmail, address, mobile, payment_method, order_date) 
              VALUES ($pid, '$name','$gmail', '$address', '$mobile', '$payment', NOW())";

    if (mysqli_query($cn, $query)) {
        echo "<h2>✅ Order Placed Successfully!</h2>";
        echo "<p>Thank you, $name. Your order has been received.</p>";
    } else {
        echo "❌ Error: " . mysqli_error($cn);
    }
}
?>
<?php include("footer.php") ?>
</body>
</html>

