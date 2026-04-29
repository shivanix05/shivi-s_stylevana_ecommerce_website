<?php 
require_once __DIR__ . "/config.php";
session_start();
if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();
}

if (isset($_POST["logoutbtn"])){
    session_destroy();
    header("location:index.php");
    exit();
}

$categoryFilter = "";
if (isset($_GET['category']) && $_GET['category'] != "") {
    $categoryFilter = mysqli_real_escape_string($cn, $_GET['category']);
}

$searchQuery = "";
if (isset($_GET['search']) && $_GET['search'] != "") {
    $searchQuery = mysqli_real_escape_string($cn, $_GET['search']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>after login- Shivi's Stylevana</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="afterl-style.css">
    <style>
        .product-card { background: #fff; border: 1px solid #f5f5f5; transition: all 0.2s; position: relative; }
        .product-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); transform: translateY(-2px); }
        .offer-tag { position: absolute; top: 10px; left: 0; background: #ff905a; color: white; padding: 4px 10px; font-size: 10px; font-weight: 700; border-radius: 0 4px 4px 0; z-index: 2; box-shadow: 2px 2px 5px rgba(0,0,0,0.1); text-transform: uppercase; }
        
        .product-image-container { position: relative; overflow: hidden; }

        /* OUT OF STOCK OVERLAY STYLING */
        .out-of-stock-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 15px;
            font-weight: 700;
            font-size: 12px;
            border-radius: 4px;
            z-index: 3;
            white-space: nowrap;
            letter-spacing: 1px;
            pointer-events: none; /* Isse link pe click karne mein dikkat nahi hogi */
        }
        
        .out-of-stock-card .product-img { 
            filter: grayscale(100%); 
            opacity: 0.6;
        }

        .rating-badge-on-img { position: absolute; bottom: 10px; left: 10px; background: rgba(255,255,255,0.9); padding: 2px 6px; font-size: 11px; font-weight: 700; border-radius: 2px; color: #282c3f; border: 1px solid #eaeaec; z-index: 1; }
        .rating-badge-on-img i { color: #14958f; font-size: 9px; margin-left: 2px; }
        .product-info { padding: 12px; text-align: left; }
        .brand-name-text { font-size: 14px; font-weight: 700; color: #282c3f; margin-bottom: 2px; text-transform: uppercase; }
        .product-name-desc { font-size: 13px; color: #535766; margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 400; }
        .price-container { font-size: 14px; margin-bottom: 4px; display: flex; align-items: center; gap: 6px; }
        .current-price { font-weight: 700; color: #282c3f; }
        .original-price-strike { text-decoration: line-through; color: #7e818c; font-size: 12px; }
        .discount-percent { color: #ff905a; font-size: 12px; font-weight: 700; }
        .delivery-status { color: #03a685; font-size: 11px; font-weight: 600; margin-top: 4px; }
    </style>
</head>
<body>

<?php include("header.php"); ?>

<?php
$categories = [
    "jewellery" => "Jewellery is forever; love is a treasure.",
    "Makeup"    => "Why waste Money, Spend it on Makeup.",
    "skincare"  => "The best self care, is the Skincare!!",
    "clothes"   => "Clothes describe your personality!!"
];

function displayCategory($cn, $category, $title) {
    $str = "SELECT * FROM shop WHERE category='" . mysqli_real_escape_string($cn, $category) . "'";
    $result = mysqli_query($cn, $str);

    if (mysqli_num_rows($result) > 0) {
        echo "<div class='category-section'>";
        echo "<div class='category-header'><h2 class='section-title'>$title</h2><div class='title-underline'></div></div>";
        echo '<div class="product-grid">';
        while ($row = mysqli_fetch_array($result)) {
            $pid = $row['pid'];
            $stock = isset($row['stock_qty']) ? (int)$row['stock_qty'] : 0; 
            $is_out_of_stock = ($stock <= 0);

            $rating_res = mysqli_query($cn, "SELECT AVG(rating) as avg_r, COUNT(rating) as total_r FROM reviews WHERE pid = '$pid'");
            $rating_row = mysqli_fetch_array($rating_res);
            $avg_rating = round((float)($rating_row['avg_r'] ?? 0), 1);

            $selling_p = (float)$row['productprice'];
            $mrp_p = (!empty($row['original_price']) && $row['original_price'] > 0) ? (float)$row['original_price'] : $selling_p;
            $discount_percent = ($mrp_p > $selling_p) ? round((($mrp_p - $selling_p) / $mrp_p) * 100) : 0;
            $delivery_val = $row['delivery_type'] ?? 'Free';
            $delivery_label = (is_numeric($delivery_val)) ? "Delivery: ₹" . $delivery_val : $delivery_val;
?>
            <div class="product-card <?php echo $is_out_of_stock ? 'out-of-stock-card' : ''; ?>">
                <?php if(!empty($row['offer_text'])): ?>
                    <div class="offer-tag"><?php echo $row['offer_text']; ?></div>
                <?php endif; ?>

                <div class="product-image-container">
                    <?php if($is_out_of_stock): ?>
                        <div class="out-of-stock-overlay">OUT OF STOCK</div>
                    <?php endif; ?>
                    
                    <!-- Fix: Hamesha order.php par jayega -->
                    <a href="order.php?pid=<?php echo $row['pid']; ?>">
                        <img src="<?php echo $row['productphoto']; ?>" alt="Product" class="product-img">
                    </a>
                    
                    <?php if($avg_rating > 0): ?>
                        <div class="rating-badge-on-img"><?php echo $avg_rating; ?> <i class="fas fa-star"></i> | <?php echo $rating_row['total_r']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="product-info">
                    <div class="brand-name-text"><?php echo $row['brand_name'] ?? 'Stylevana'; ?></div>
                    <div class="product-name-desc"><?php echo $row['productname']; ?></div>
                    <div class="price-container">
                        <span class="current-price">₹<?php echo $row['productprice']; ?></span>
                        <?php if($mrp_p > $selling_p): ?>
                            <span class="original-price-strike">₹<?php echo $mrp_p; ?></span>
                            <span class="discount-percent">(<?php echo $discount_percent; ?>% OFF)</span>
                        <?php endif; ?>
                    </div>
                    <div class="delivery-status"><i class="fas fa-truck"></i> <?php echo $delivery_label; ?></div>
                    
                    <div class="button-group" style="margin-top:10px;">
                        <?php if(!$is_out_of_stock): ?>
                            <button class="add-to-cart-btn" onclick="addToCartByAjax(<?php echo $row['pid']; ?>)"><i class="fas fa-cart-plus"></i></button>
                            <a href="order.php?pid=<?php echo $row['pid']; ?>" class="buy-now-btn" style="padding: 8px 15px; font-size: 12px;">Buy Now</a>
                        <?php else: ?>
                            <!-- Button click pe bhi page order.php pe jayega, bus dikhne mein disabled lagega -->
                            <a href="order.php?pid=<?php echo $row['pid']; ?>" class="buy-now-btn" style="background:#ccc; cursor:pointer; width:100%; display:inline-block; text-align:center;">Unavailable</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
<?php
        }
        echo "</div></div>";
    }
}

function showOriginalBrands() {
?>
    <div class="original-brands-section">
        <div class="section-header">
            <h2>Shop by Popular Categories <span class="verified-icon"><i class="fas fa-check-circle"></i></span></h2>
            <a href="viewall.php" class="view-all-link">VIEW ALL <i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="brand-story-container">
            <div class="brand-story-item"><a href="after-login.php?category=skincare"><div class="brand-circle"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcTijPt6vdS-qeUguYMJSvSeaWdPVlYtve4w&s"></div><span>Skincare</span></a></div>
            <div class="brand-story-item"><a href="after-login.php?category=clothes"><div class="brand-circle"><img src="https://www.shutterstock.com/image-photo/fashionable-clothes-boutique-store-london-600nw-589577570.jpg"></div><span>Fashion</span></a></div>
            <div class="brand-story-item"><a href="after-login.php?category=Makeup"><div class="brand-circle"><img src="https://cdn.britannica.com/35/222035-050-C68AD682/makeup-cosmetics.jpg"></div><span>Makeup</span></a></div>
            <div class="brand-story-item"><a href="after-login.php?category=jewellery"><div class="brand-circle"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRqeEhTWhCyXWBsN8P6mMNrTlS7NOkCgB8M7w&s"></div><span>Jewelry</span></a></div>
        </div>
    </div>
<?php
}
?>

<main class="container main-content">
<?php
if ($searchQuery != "") {
    $search_res = mysqli_query($cn, "SELECT * FROM shop WHERE productname LIKE '%$searchQuery%'");
    if (mysqli_num_rows($search_res) > 0) {
        echo "<div class='category-section'><h2 class='section-title'>Search Results</h2><div class='product-grid'>";
        while ($row = mysqli_fetch_array($search_res)) {
            $stock_search = isset($row['stock_qty']) ? (int)$row['stock_qty'] : 0;
            $search_out = ($stock_search <= 0);
            ?>
            <div class="product-card <?php echo $search_out ? 'out-of-stock-card' : ''; ?>">
                <div class="product-image-container">
                    <?php if($search_out): ?>
                        <div class="out-of-stock-overlay">OUT OF STOCK</div>
                    <?php endif; ?>
                    <!-- Fix for Search: Hamesha order.php par jayega -->
                    <a href="order.php?pid=<?php echo $row['pid']; ?>">
                        <img src="<?php echo $row['productphoto']; ?>" class="product-img">
                    </a>
                </div>
                 <div class="product-info">
                    <div class="brand-name-text"><?php echo $row['brand_name'] ?? 'Stylevana'; ?></div>
                    <div class="product-name-desc"><?php echo $row['productname']; ?></div>
                    <div class="price-container">
                        <span class="current-price">₹<?php echo $row['productprice']; ?></span>
                    </div>
                    <div class="button-group" style="margin-top:10px;">
                        <?php if(!$search_out): ?>
                            <button class="add-to-cart-btn" onclick="addToCartByAjax(<?php echo $row['pid']; ?>)"><i class="fas fa-cart-plus"></i></button>
                            <a href="order.php?pid=<?php echo $row['pid']; ?>" class="buy-now-btn" style="padding: 8px 15px; font-size: 12px;">Buy Now</a>
                        <?php else: ?>
                            <a href="order.php?pid=<?php echo $row['pid']; ?>" class="buy-now-btn" style="background:#ccc; width:100%; display:inline-block; text-align:center;">Sold Out</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }
        echo "</div></div><hr>";
    }
}

if (!$categoryFilter) {
    showOriginalBrands();
    foreach ($categories as $cat => $title) {
        displayCategory($cn, $cat, $title);
    }
} else {
    displayCategory($cn, $categoryFilter, $categories[$categoryFilter] ?? $categoryFilter);
}
?>
</main>

<script>
function addToCartByAjax(pid) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "addcart.php?pid=" + pid, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert("Added to Cart Successfully!");
        }
    };
    xhr.send();
}
</script>

<?php include ("footer.php")?>
<?php include("cartscript.php")?>
</body>
</html>
