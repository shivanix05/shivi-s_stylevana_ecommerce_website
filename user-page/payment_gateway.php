<?php
require_once __DIR__ . "/config.php";
session_start();

// Session check
if(!isset($_SESSION['temp_order'])) {
    header("location:after-login.php");
    exit();
}

$data = $_SESSION['temp_order'];
$user_email = $_SESSION['user'];

// Razorpay Key 
$api_key = "rzp_test_SYD8WG4EZjkBw4"; 

$display_amount = $data['total_amount'];
$razorpay_amount = $display_amount * 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Processing - Shivi's Stylevana</title>
    <style>
        body { background-color: #F0E4D3; font-family: 'Poppins', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .payment-card { background: white; padding: 40px; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 400px; }
        .logo { width: 100px; margin-bottom: 20px; }
        .amount { font-size: 2rem; color: #333; margin: 10px 0; font-weight: bold; }
        .razorpay-payment-button { display: none !important; }
        .custom-pay-btn { background-color: #D9A299; color: white; border: none; padding: 15px 40px; border-radius: 25px; font-weight: bold; cursor: pointer; font-size: 1.1rem; transition: 0.3s; width: 100%; }
        .custom-pay-btn:hover { background-color: #c58d83; }
    </style>
</head>
<body>

<div class="payment-card">
    <img src="logo.png" class="logo" alt="Shivi's Stylevana">
    <h2 style="color: #555;">Final Step: Secure Payment</h2>
    <p style="color: #888;">Order for: <?php echo htmlspecialchars($data['name']); ?></p>
    <div class="amount">₹<?php echo $display_amount; ?></div>
    
    <form action="verify_payment.php" method="POST">
        <script
            src="https://checkout.razorpay.com/v1/checkout.js"
            data-key="<?php echo $api_key; ?>"
            data-amount="<?php echo $razorpay_amount; ?>"
            data-currency="INR"
            data-name="Shivi's Stylevana"
            data-description="Product Order"
            /* FIX: Localhost path error se bachne ke liye standard icon use kiya hai */
            data-image="https://cdn-icons-png.flaticon.com/512/1162/1162456.png" 
            data-prefill.name="<?php echo htmlspecialchars($data['name']); ?>"
            data-prefill.email="<?php echo $user_email; ?>"
            data-prefill.contact="<?php echo $data['mobile']; ?>"
            data-theme.color="#D9A299"
        ></script>
        
        <input type="hidden" name="shopping_order_id" value="ORD<?php echo time(); ?>">
        
        <button type="button" onclick="document.querySelector('.razorpay-payment-button').click()" class="custom-pay-btn">
            PAY NOW
        </button>
    </form>
    
    <p style="font-size: 0.8rem; color: #aaa; margin-top: 20px;">
        <i class="fas fa-lock"></i> Secure SSL Encrypted Payment
    </p>
</div>

</body>
</html>
