<?php
include("config.php");
session_start();
if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();
}
if (isset($_POST["logoutbtn"])){
        session_destroy();
        header("location:login.php");
        exit();
    }


if (!isset($_GET['pid'])) {
    die("No product selected.");
}

$pid = $_GET['pid'];
$query = "SELECT * FROM shop WHERE pid = $pid";
$result = mysqli_query($cn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    die("Product not found.");
}
$product = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - <?php echo $product['productName']; ?></title>
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="afterl-style.css">
   
    <style>
        .checkout-container {
            width: 60%;
            margin: auto;
        }
        .checkout-container img {
            width: 200px;
            border-radius: 10px;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
        }
        .submit-btn {
            background: #28a745;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background: #218838;
        }
        .payment-link {
            background: #007bff;
            padding: 10px;
            color: white;
            display: inline-block;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>
  <?php include("header.php") ?>
<div class="checkout-container">
    <h2>Checkout</h2>
    <img src="<?php echo $product['productphoto']; ?>" alt="Product">
    <h3><?php echo $product['productName']; ?></h3>
    <p>Price: ₹<?php echo $product['productprice']; ?></p>

    <form action="placeorder.php" method="POST">
        <input type="hidden" name="pid" value="<?php echo $product['pid']; ?>">

        <label for="name">Full Name</label>
        <input type="text" name="name" required>
         <label for="mobile">gmail</label>
        <input type="email" name="gmail" required>
        
        <label for="address">Address</label>
        <textarea name="address" required></textarea>

         <label for="mobile">Mobile Number</label>
        <input type="text" name="mobile" required>
 


        <label for="payment">Payment Method</label>
        <select name="payment" id="payment" onchange="togglePaymentLink()" required>
            <option value="cod">Cash on Delivery</option>
            <option value="online">Online Payment</option>
        </select>

        <div id="onlinePayment" style="display:none;">
            <p>Pay securely using PhonePe:</p>
            <a href="https://phonepe.com/pay-link" target="_blank" class="payment-link">Pay with PhonePe</a>
        </div>

        <button type="submit" class="submit-btn">Confirm Order</button>
    </form>
</div>

<script>
function togglePaymentLink() {
    const payment = document.getElementById("payment").value;
    const onlineDiv = document.getElementById("onlinePayment");
    onlineDiv.style.display = (payment === "online") ? "block" : "none";
}
</script>

</body>
</html>
