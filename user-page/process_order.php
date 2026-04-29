<?php
require_once __DIR__ . "/config.php";
session_start();

if(isset($_POST['place_order'])) {
    if(!isset($_SESSION['user'])) {
        echo "<script>alert('Please login first!'); window.location.href='login.php';</script>";
        exit();
    }

    $user = $_SESSION['user'];
    $pid = mysqli_real_escape_string($cn, $_POST['pid']);
    $order_date = date("Y-m-d H:i:s");
    $name = mysqli_real_escape_string($cn, $_POST['name']);
    $mobile = mysqli_real_escape_string($cn, $_POST['mobile']);
    $address = mysqli_real_escape_string($cn, $_POST['address']);
    $total = mysqli_real_escape_string($cn, $_POST['total_amount']);
    $payment_mode = $_POST['payment_method'];

    // --- PHOTO FETCH LOGIC ---
    if($pid != '0') {
        // Single Item Case
        $res = mysqli_query($cn, "SELECT productphoto FROM shop WHERE pid = '$pid'");
        $row = mysqli_fetch_assoc($res);
        $photo = $row['productphoto'];
    } else {
        // Cart Case: Pehle item ki photo le lo display ke liye
        $res = mysqli_query($cn, "SELECT s.productphoto FROM cart c JOIN shop s ON c.pid = s.pid WHERE c.user_email = '$user' LIMIT 1");
        $row = mysqli_fetch_assoc($res);
        $photo = $row['productphoto'];
    }

    // --- INSERT QUERY ---
    $query = "INSERT INTO orders (user_email, pid, name, mobilenumber, adddress, productprice, qty, productphoto, payment_method, order_date, status) 
              VALUES ('$user', '$pid', '$name', '$mobile', '$address', '$total', '1', '$photo', '$payment_mode', '$order_date', 'Order Placed')";
    
    if(mysqli_query($cn, $query)) {

        // --- STOCK UPDATE LOGIC (Naya Part) ---
        if($pid != '0') {
            // Agar ek hi product hai toh uska stock kam karo
            mysqli_query($cn, "UPDATE shop SET stock_qty = stock_qty - 1 WHERE pid = '$pid' AND stock_qty > 0");
        } else {
            // Agar Cart se order hai, toh Cart ke sabhi products ka stock kam karo
            $cart_items_query = "SELECT pid FROM cart WHERE user_email = '$user'";
            $cart_res = mysqli_query($cn, $cart_items_query);
            while($cart_row = mysqli_fetch_assoc($cart_res)) {
                $c_pid = $cart_row['pid'];
                mysqli_query($cn, "UPDATE shop SET stock_qty = stock_qty - 1 WHERE pid = '$c_pid' AND stock_qty > 0");
            }
        }
        // --- STOCK UPDATE END ---

        if($pid == '0') {
            mysqli_query($cn, "DELETE FROM cart WHERE user_email = '$user'");
        } else {
            mysqli_query($cn, "DELETE FROM cart WHERE user_email = '$user' AND pid = '$pid'");
        }
        echo "<script>alert('Order Placed Successfully!'); window.location.href='my-orders.php';</script>";
    } else {
        echo "Error: " . mysqli_error($cn);
    }
}
?>
