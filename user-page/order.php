<?php 
require_once __DIR__ . "/config.php"; 
session_start();

if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();    
}

$user = $_SESSION['user'];

if(isset($_GET['pid'])) {
    $pid = mysqli_real_escape_string($cn, $_GET['pid']);
    $res = mysqli_query($cn, "SELECT * FROM shop WHERE pid = '$pid'");
    $product = mysqli_fetch_array($res);

    if(!$product) {
        echo "<script>alert('Product not found!'); window.location.href='after-login.php';</script>";
        exit();
    }
} else {
    header("location:after-login.php");
    exit();
}

// Discount Logic
$current_p = (float)$product['productprice'];
$old_p = (float)($product['original_price'] ?? $current_p);
$discount = ($old_p > $current_p) ? round((($old_p - $current_p) / $old_p) * 100) : 0;

// FETCH RATINGS
$avg_res = mysqli_query($cn, "SELECT AVG(rating) as avg_rating, COUNT(rid) as total_reviews FROM reviews WHERE pid = '$pid'");
$avg_data = mysqli_fetch_array($avg_res);
$rating = round((float)($avg_data['avg_rating'] ?? 0), 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>✨ <?php echo $product['productname']; ?> - Stylevana ✨</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Quicksand:wght@300;500;700&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            --bubble-pink: #ff85a2;
            --soft-pink: #fff0f3;
            --aesthetic-gray: #555;
            --wishlist-red: #ff4d6d;
        }

        body { 
            background: #fff; 
            font-family: 'Quicksand', sans-serif; 
            margin: 0;
            color: var(--aesthetic-gray);
        }

        .order-wrapper {
            max-width: 1050px;
            margin: 40px auto;
            padding: 20px;
        }

        .pinterest-card {
            display: flex;
            gap: 50px;
            background: white;
            padding: 40px;
            border-radius: 40px;
            box-shadow: 0 15px 45px rgba(255, 182, 193, 0.1);
        }

        .image-container { 
            flex: 1; 
            border-radius: 30px;
            overflow: hidden;
            max-height: 500px;
        }
        .image-container img { 
            width: 100%; 
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.8s ease;
        }
        .image-container:hover img { transform: scale(1.05); }

        .content-container { flex: 1.2; }
        
        .brand-text { 
            font-family: 'Dancing Script', cursive; 
            font-size: 30px; 
            color: var(--bubble-pink); 
            margin-bottom: 10px;
        }
        .product-title { 
            font-size: 28px; 
            font-weight: 700; 
            color: #333;
            margin: 0 0 5px 0;
        }

        .offer-highlight {
            color: #ff4d6d;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            display: block;
        }

        .price-section { margin-bottom: 10px; display: flex; align-items: baseline; gap: 15px; }
        .price-now { font-size: 36px; font-weight: 700; color: #222; }
        .price-old { font-size: 20px; text-decoration: line-through; color: #bbb; }
        .discount-tag { color: var(--wishlist-red); font-weight: 700; font-size: 16px; }

        .delivery-box {
            background: #f0fdf9;
            color: #03a685;
            padding: 12px 18px;
            border-radius: 15px;
            font-size: 14px;
            margin: 20px 0;
            border: 1px solid #d1fae5;
        }

        .rating-chip {
            display: inline-flex;
            align-items: center;
            background: var(--soft-pink);
            padding: 6px 18px;
            border-radius: 50px;
            font-weight: 700;
            color: var(--wishlist-red);
            margin-bottom: 25px;
        }

        .btn-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-base {
            padding: 18px;
            border-radius: 20px;
            border: none;
            font-family: 'Quicksand', sans-serif;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-cart { background: var(--bubble-pink); color: white; box-shadow: 0 10px 20px rgba(255, 133, 162, 0.2); }
        .btn-wish { background: white; border: 2px solid var(--soft-pink); color: var(--wishlist-red); }
        .btn-order { grid-column: span 2; background: #282c3f; color: white; }
        .btn-disabled { grid-column: span 2; background: #ccc; color: #666; cursor: not-allowed; }

        .qty-box { margin-bottom: 20px; }
        #qtySelect { padding: 10px 15px; border-radius: 12px; border: 1px solid #eee; background: #fafafa; font-weight: 700; cursor: pointer; }

        .review-container { 
            margin-top: 50px; 
            background: #fffcfd; 
            padding: 40px; 
            border-radius: 40px; 
            border: 1px solid var(--soft-pink);
        }
        .review-row { border-bottom: 1px solid #f9f9f9; padding: 20px 0; }

        @media (max-width: 768px) { .pinterest-card { flex-direction: column; } }
    </style>
</head>
<body>

<?php include("header.php"); ?>

<div class="order-wrapper">
    <div class="pinterest-card">
        <div class="image-container">
            <img src="<?php echo $product['productphoto']; ?>" alt="Product">
        </div>

        <div class="content-container">
            <p class="brand-text">Shivi's Stylevana ✨</p>
            <h1 class="product-title"><?php echo $product['productname']; ?></h1>
            
            <?php if(!empty($product['offer_text'])): ?>
                <span class="offer-highlight"><i class="fas fa-magic"></i> <?php echo $product['offer_text']; ?></span>
            <?php endif; ?>

            <div class="rating-chip">
                <i class="fas fa-heart"></i>&nbsp; <?php echo ($rating > 0) ? $rating : "New Glow"; ?> 
                <span style="font-weight: 400; font-size: 12px; margin-left: 10px;">| <?php echo $avg_data['total_reviews']; ?> Reviews</span>
            </div>

            <div class="price-section">
                <span class="price-now">₹<?php echo $product['productprice']; ?></span>
                <?php if($discount > 0): ?>
                    <span class="price-old">₹<?php echo $product['original_price']; ?></span>
                    <span class="discount-tag"><?php echo $discount; ?>% OFF</span>
                <?php endif; ?>
            </div>

            <div class="delivery-box">
                <i class="fas fa-truck"></i> Delivery Charges: 
                <strong><?php echo (is_numeric($product['delivery_type'])) ? "₹".$product['delivery_type'] : $product['delivery_type']; ?></strong> <br>
                <i class="fas fa-calendar-check" style="margin-top:5px;"></i> Delivery in 3-5 working days
            </div>

            <?php if($product['stock_qty'] > 0): ?>
                <div class="qty-box">
                    <label style="font-size: 12px; font-weight: 700; color: #bbb;">QUANTITY</label><br>
                    <select id="qtySelect" onchange="updateCheckoutLink()">
                        <?php 
                        $max_qty = min($product['stock_qty'], 5); 
                        for($i=1; $i<=$max_qty; $i++) { echo "<option value='$i'>$i</option>"; } 
                        ?>
                    </select>
                </div>

                <div class="btn-group">
                    <a href="addcart.php?pid=<?php echo $pid; ?>" class="btn-base btn-cart">
                        <i class="fas fa-shopping-bag"></i> ADD TO BAG
                    </a>
                    
                    <button onclick="addToWishlist('<?php echo $pid; ?>')" class="btn-base btn-wish">
                        <i class="far fa-heart"></i> WISHLIST
                    </button>

                    <a href="checkout.php?buy_pid=<?php echo $pid; ?>&qty=1" id="checkoutBtn" class="btn-base btn-order">
                        ORDER NOW 🎀
                    </a>
                </div>
            <?php else: ?>
                <div class="btn-group">
                    <button class="btn-base btn-disabled" disabled>
                        <i class="fas fa-moon"></i> SOLD OUT 🌙
                    </button>
                    <button onclick="addToWishlist('<?php echo $pid; ?>')" class="btn-base btn-wish" style="grid-column: span 2;">
                        NOTIFY ME / WISHLIST
                    </button>
                </div>
            <?php endif; ?>

            <div style="margin-top: 30px;">
                <h4 style="font-family: 'Playfair Display', serif;">About this Glow</h4>
                <p style="font-size: 14px; line-height: 1.8; color: #777;"><?php echo $product['productdescription']; ?></p>
            </div>
        </div>
    </div>

    <div class="review-container">
        <h3 style="font-family: 'Dancing Script', cursive; font-size: 32px; color: var(--bubble-pink);">Community Love 🌸</h3>
        <div style="margin: 25px 0;">
            <form action="save_review.php" method="POST">
                <input type="hidden" name="pid" value="<?php echo $pid; ?>">
                <select name="rating" required style="padding:10px; border-radius:10px; border:1px solid #eee; margin-bottom:10px;">
                    <option value="5">⭐⭐⭐⭐⭐ (Obsessed!)</option>
                    <option value="4">⭐⭐⭐⭐ (Love it)</option>
                    <option value="3">⭐⭐⭐ (Nice)</option>
                </select>
                <textarea name="comment" placeholder="Write your experience..." required style="width:100%; height:80px; padding:15px; border-radius:15px; border:1px solid #f9f9f9; background:#fff;"></textarea>
                <button type="submit" name="btn_review" style="background:var(--bubble-pink); color:white; border:none; padding:10px 25px; border-radius:50px; margin-top:10px; cursor:pointer; font-weight:700;">Post Review</button>
            </form>
        </div>
        <?php 
        $rev_list = mysqli_query($cn, "SELECT * FROM reviews WHERE pid = '$pid' ORDER BY rid DESC LIMIT 4");
        while($r = mysqli_fetch_array($rev_list)) {
            echo "<div class='review-row'>";
            echo "<span style='color:var(--wishlist-red); font-size:13px; font-weight:700;'>" . $r['rating'] . " <i class='fas fa-star'></i></span>";
            echo "<p style='margin: 8px 0; font-size: 14px; color:#555;'>" . htmlspecialchars($r['comment']) . "</p>";
            echo "<small style='color:#bbb;'>- " . htmlspecialchars($r['user_email']) . "</small>";
            echo "</div>";
        }
        ?>
    </div>
</div>

<script>
    function updateCheckoutLink() {
        var qty = document.getElementById('qtySelect').value;
        var pid = "<?php echo $pid; ?>";
        document.getElementById('checkoutBtn').href = "checkout.php?buy_pid=" + pid + "&qty=" + qty;
    }

    function addToWishlist(pid) {
        fetch('add_to_wishlist.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'product_id=' + pid
        })
        .then(response => response.text())
        .then(data => {
            let res = data.trim(); 
            // Fixed: Check if 'success' is present in the response
            if(res.includes('success')) {
                alert('Added to your Wishlist! 🖤');
            } else if(res.includes('already_exists')) {
                alert('This item is already in your wishlist!');
            } else if(res.includes('login_required')) {
                window.location.href = 'login.php';
            } else {
                // If there's an error, it will show the raw response
                alert('Alert: ' + res);
            }
        });
    }
</script>

<?php include("footer.php"); ?>

</body>
</html>
