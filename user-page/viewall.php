<?php 
require_once __DIR__ . "/config.php";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stylevana | All Treasures</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="viewall.css">

    <style>
       
    </style>
</head>
<body>

    <?php include("header.php")?>

    <div class="product-grid">
        <?php 
            // Saare products database se uthayega
            $str = "SELECT * FROM shop ORDER BY pid DESC"; 
            $result = mysqli_query($cn, $str);

            while ($row = mysqli_fetch_array($result)) {
                $pid = $row['pid'];
                $stock = (int)($row['stock_qty'] ?? 0);
                
                // Backend fields setup
                $selling_p = (float)$row['productprice'];
                $mrp_p = (!empty($row['original_price'])) ? (float)$row['original_price'] : 0;
                $offer_tag = $row['offer_text'] ?? ''; // Database column for "B1G2" etc.
                
                // Discount calculation
                $discount_val = 0;
                if ($mrp_p > $selling_p) {
                    $discount_val = round((($mrp_p - $selling_p) / $mrp_p) * 100);
                }
        ?>
        
        <div class="product-card">
            <!-- Priority to offer_tag, then auto-discount -->
            <?php if(!empty($offer_tag) || $discount_val > 0): ?>
                <div class="offer-flag">
                    <?php echo !empty($offer_tag) ? $offer_tag : $discount_val . "%<br>OFF"; ?>
                </div>
            <?php endif; ?>

            <div class="image-container">
                <a href="order.php?pid=<?php echo $pid; ?>">
                    <img src="../admin-page/<?php echo $row['productphoto']; ?>" alt="Product" class="product-image <?php echo ($stock <= 0) ? 'out-of-stock-img' : ''; ?>">
                </a>
            </div>

            <div class="product-details">
                <!-- Brand name backend se -->
                <span class="brand-name"><?php echo strtoupper($row['brand'] ?? 'Stylevana'); ?></span>
                <p class="product-name"><?php echo $row['productname']; ?></p>
                
                <div class="price-section">
                    <span class="current-price">₹<?php echo $selling_p; ?></span>
                    <?php if($mrp_p > $selling_p): ?>
                        <span class="original-price">₹<?php echo $mrp_p; ?></span>
                        <span class="discount-text">(<?php echo $discount_val; ?>% OFF)</span>
                    <?php endif; ?>
                </div>

                <!-- Shipping info backend se -->
                <span class="shipping-tag">
                    <i class="fas fa-truck"></i> <?php echo $row['shipping_status'] ?? 'Free Shipping'; ?>
                </span>

                <?php if($stock > 0): ?>
                    <div class="button-group">
                        <a href="addcart.php?pid=<?php echo $pid; ?>" class="cart-icon-btn">
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                        <a href="order.php?pid=<?php echo $pid; ?>" class="buy-now-btn">Buy Now</a>
                    </div>
                <?php else: ?>
                    <span class="out-of-stock-badge">OUT OF STOCK</span>
                <?php endif; ?>
            </div>
        </div>

        <?php } ?>
    </div>

    <?php include("footer.php")?>

</body>
</html>
