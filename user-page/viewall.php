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
    
    <style>
        body {
            background-color: #f5f5f0; 
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            padding: 40px 20px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .product-card {
            background: #fff;
            border-radius: 4px;
            overflow: hidden;
            transition: 0.3s;
            position: relative;
            text-align: center;
            border: 1px solid #eee;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        }

        /* Flag Shape Offer Tag */
        .offer-flag {
            position: absolute;
            top: 0;
            left: 12px; 
            background: #ff7675; 
            color: white;
            padding: 6px 8px 12px;
            font-size: 10px;
            font-weight: 700;
            z-index: 5;
            clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 85%, 0 100%);
            text-transform: uppercase;
            line-height: 1.1;
            min-width: 35px;
        }

        .image-container {
            width: 100%;
            height: 300px;
            background: #f9f9f9;
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.5s;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .out-of-stock-img { filter: grayscale(1); opacity: 0.6; }

        .product-details {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand-name {
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 4px;
            color: #282c3f;
            display: block;
        }

        .product-name {
            font-size: 13px;
            color: #535766;
            margin: 0 0 8px 0;
            font-weight: 400;
            height: 18px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .price-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
        }

        .current-price { font-size: 15px; font-weight: 700; color: #282c3f; }
        .original-price { color: #7e818c; text-decoration: line-through; font-size: 12px; }
        .discount-text { color: #ff905a; font-size: 12px; font-weight: 600; }

        .shipping-tag {
            color: #03a685;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 12px;
            display: block;
        }

        .button-group {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }

        .cart-icon-btn {
            flex: 0.3;
            background: #fff;
            border: 1px solid #d4d5d9;
            color: #ff3f6c;
            padding: 8px;
            border-radius: 4px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .buy-now-btn {
            flex: 1;
            background: #d5a69d; 
            color: #fff;
            padding: 8px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: 0.2s;
        }

        .out-of-stock-badge {
            background: #999;
            color: white;
            padding: 8px;
            width: 100%;
            font-size: 12px;
            border-radius: 4px;
        }
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
                    <img src="<?php echo $row['productphoto']; ?>" alt="Product" class="product-image <?php echo ($stock <= 0) ? 'out-of-stock-img' : ''; ?>">
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
