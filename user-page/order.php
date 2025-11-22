<?php
include("config.php"); // db connection

if (!isset($_GET['pid'])) {
    die("No product selected.");
}

$pid = intval($_GET['pid']);

// Fetch product details
$query = "SELECT * FROM shop WHERE pid = $pid";
$result = mysqli_query($cn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Product not found.");
}

$product = mysqli_fetch_assoc($result);
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
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['productName']; ?> - Order</title>
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="afterl-style.css">
   
    <style>
        .product-container {
            width: 60%;
            margin: auto;
            text-align: center;
        }
        .product-container img {
            width: 300px;
            border-radius: 10px;
        }
        .order-btn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 18px;
            background: #ff6666;
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }
        .order-btn:hover {
            background: #e05555;
        }
    </style>
</head>
<body>
    <?php include("header.php") ?>
    <main>
        
    </main>

<div class="product-container">
    <h2><?php echo $product['productName']; ?></h2>
    <img src="<?php echo $product['productphoto']; ?>" alt="Product">
    <p><strong>Price:</strong> ₹<?php echo $product['productprice']; ?></p>
    <p><strong>Description:</strong> <?php echo $product['productdescription']; ?></p>
    <p><strong>Reviews:</strong> ⭐⭐⭐⭐☆ (4.0/5)</p> 

    
    <form action="checkout.php" method="GET">
        <input type="hidden" name="pid" value="<?php echo $product['pid']; ?>">
        <button type="submit" class="order-btn">Order Now</button>
    </form>
</div>

<?php  include("footer.php") ?>
</body>
</html>
