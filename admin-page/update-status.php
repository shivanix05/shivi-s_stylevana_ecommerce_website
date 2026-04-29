<?php
include("function.php");
session_start();

// Admin security check
if(!isset($_SESSION["admin"])) { 
    header("location:adminlogin.php"); 
    exit(); 
}

$cn = make_connection();

if(isset($_POST["btn_status"])) {
    // 1. Data receive karo form se
    // Order ID hum URL (GET) se bhi le sakte hain ya hidden input se
    $oid = mysqli_real_escape_string($cn, $_GET["oid"]); 
    $new_status = mysqli_real_escape_string($cn, $_POST["status_val"]);
    $track_id = mysqli_real_escape_string($cn, $_POST["track_id"]);

    // 2. Database update query (As per your image_23ee7f.png columns)
    $q = "UPDATE orders SET 
          status = '$new_status', 
          tracking_id = '$track_id' 
          WHERE order_id = '$oid'";

    if(mysqli_query($cn, $q)) {
        // Success: Wapas Order details page par bhej do ek msg ke saath
        echo "<script>
                alert('Order Status Updated Successfully!');
                window.location='order-details.php?oid=$oid';
              </script>";
    } else {
        // Error handling
        echo "Error updating record: " . mysqli_error($cn);
    }
} else {
    // Agar koi direct is page ko khole toh wapas bhej do
    header("location:order.php");
}
?>