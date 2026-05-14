<?php
require_once __DIR__ . "/config.php";
session_start();

if(isset($_POST['place_order'])) {
    $user = $_SESSION['user'];
    $pid = $_POST['pid'];
    $name = mysqli_real_escape_string($cn, $_POST['name']);
    $mobile = mysqli_real_escape_string($cn, $_POST['mobile']);
    $address = mysqli_real_escape_string($cn, $_POST['address']);
    $total = $_POST['total_amount'];
    $order_date = date("Y-m-d H:i:s");

    // 1. Order Insert Query
    $query = "INSERT INTO orders (user_email, pid, name, mobilenumber, adddress, productprice, payment_method, order_date) 
              VALUES ('$user', '$pid', '$name', '$mobile', '$address', '$total', 'COD', '$order_date')";
    
    if(mysqli_query($cn, $query)) {
        
        // 2. STOCK MINUS LOGIC (Ye naya part hai)
        // Isse 'shop' table mein product ki quantity 1 kam ho jayegi
        $update_stock = "UPDATE shop SET stock_qty = stock_qty - 1 WHERE pid = '$pid' AND stock_qty > 0";
        mysqli_query($cn, $update_stock);

        echo "<script>alert('Order Placed Successfully!'); window.location.href='myorder.php';</script>";
    } else {
        echo "Error: " . mysqli_error($cn);
    }
}
?>
<!-- assitent id 
 a7272df7-670d-47fe-8d4d-40aaf0f4d70a 
 api
 8dc74dad-9dea-444f-8b92-8dd83435f59c -->