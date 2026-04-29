<?php
require_once __DIR__ . "/config.php";
session_start();

// Checking if payment was successful from Razorpay
if(isset($_POST['razorpay_payment_id'])) {
    
    $payment_id = $_POST['razorpay_payment_id']; 
    $user = $_SESSION['user'];
    $data = $_SESSION['temp_order']; // Ye data checkout.php se aa raha hai
    $order_date = date("Y-m-d H:i:s");

    // Order details extraction with safety checks
    $pid = mysqli_real_escape_string($cn, $data['pid']);
    $name = mysqli_real_escape_string($cn, $data['name']);
    $mobile = mysqli_real_escape_string($cn, $data['mobile']);
    $address = mysqli_real_escape_string($cn, $data['address']);
    $total = mysqli_real_escape_string($cn, $data['total_amount']);
    
    // Photo aur Qty fix:
    $photo = isset($data['photo']) ? mysqli_real_escape_string($cn, $data['photo']) : ''; 
    $qty = (isset($data['qty']) && !empty($data['qty'])) ? (int)$data['qty'] : 1;

    /** * FIX: Query mein 'status' column add kiya hai aur value 'placed' set ki hai.
     * Isse myorder.php mein "Placed" ka pink label dikhega aur admin page ka error bhi hatega.
     **/
    $query = "INSERT INTO orders (user_email, pid, name, mobilenumber, adddress, productprice, qty, productphoto, payment_method, order_date, status) 
              VALUES ('$user', '$pid', '$name', '$mobile', '$address', '$total', '$qty', '$photo', 'Online (ID: $payment_id)', '$order_date', 'placed')";

    if(mysqli_query($cn, $query)) {
        
        // Cart clean-up logic
        if($pid == "0") {
            // Agar full cart checkout tha
            mysqli_query($cn, "DELETE FROM cart WHERE user_email = '$user'");
        } else {
            // Agar single product checkout tha
            mysqli_query($cn, "DELETE FROM cart WHERE user_email = '$user' AND pid = '$pid'");
        }
        
        // Success hone ke baad temporary session delete karein
        unset($_SESSION['temp_order']); 

        echo "<script>
                alert('Payment Successful! Your Transaction ID: $payment_id');
                window.location.href = 'myorder.php';
              </script>";
    } else {
        // Agar database mein koi error aaye
        echo "Database Error: " . mysqli_error($cn);
    }

} else {
    // Agar user ne payment cancel ki ya fail hui
    echo "<script>
            alert('Payment Cancelled or Failed. Please try again.');
            window.location.href = 'checkout.php';
          </script>";
}
?>
