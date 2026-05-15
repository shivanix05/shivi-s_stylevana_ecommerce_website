<?php
/**
 * filter_products.php
 * ───────────────────
 * AJAX endpoint called by after-login.php filter system.
 * Returns HTML product cards based on filter params.
 * 
 * GET params:
 *   sort        = default | price_asc | price_desc | newest | rating | discount
 *   categories  = comma-separated category names
 *   price_min   = number
 *   price_max   = number
 *   rating      = minimum star rating (2|3|4)
 *   brands      = || separated brand names
 *   instock     = 1
 *   featured    = 1
 *   sale        = 1
 *   search      = keyword
 */

require_once __DIR__ . "/config.php";
session_start();

if (!isset($_SESSION["user"])) {
    http_response_code(401);
    exit('Not authenticated');
}

$current_user_email = $_SESSION['user'];

// ── Wishlist check ──
function isWishlisted($cn, $pid, $email) {
    $e = mysqli_real_escape_string($cn, $email);
    $p = mysqli_real_escape_string($cn, $pid);
    $r = mysqli_query($cn, "SELECT 1 FROM wishlist WHERE user_email='$e' AND pid='$p'");
    return $r && mysqli_num_rows($r) > 0;
}

// ══ Build WHERE conditions ══
$conditions = ["1=1"];

// Search
if (!empty($_GET['search'])) {
    $sq = '%' . mysqli_real_escape_string($cn, $_GET['search']) . '%';
    $conditions[] = "(productname LIKE '$sq' OR brand_name LIKE '$sq' OR category LIKE '$sq')";
}

// Categories
if (!empty($_GET['categories'])) {
    $raw_cats = explode(',', $_GET['categories']);
    $safe_cats = array_map(function($c) use ($cn) {
        return "'" . mysqli_real_escape_string($cn, trim($c)) . "'";
    }, $raw_cats);
    if (!empty($safe_cats)) {
        $conditions[] = "category IN (" . implode(',', $safe_cats) . ")";
    }
}

// Price
if (!empty($_GET['price_min'])) {
    $pmin = (float)$_GET['price_min'];
    $conditions[] = "productprice >= $pmin";
}
if (!empty($_GET['price_max'])) {
    $pmax = (float)$_GET['price_max'];
    $conditions[] = "productprice <= $pmax";
}

// In stock
if (!empty($_GET['instock']) && $_GET['instock'] == '1') {
    $conditions[] = "stock_qty > 0";
}

// Featured
if (!empty($_GET['featured']) && $_GET['featured'] == '1') {
    $conditions[] = "is_featured = 1";
}

// On sale (has original_price > productprice)
if (!empty($_GET['sale']) && $_GET['sale'] == '1') {
    $conditions[] = "original_price > productprice AND original_price IS NOT NULL AND original_price != ''";
}

// Brands
if (!empty($_GET['brands'])) {
    $raw_brands = explode('||', $_GET['brands']);
    $safe_brands = array_map(function($b) use ($cn) {
        return "'" . mysqli_real_escape_string($cn, trim($b)) . "'";
    }, $raw_brands);
    if (!empty($safe_brands)) {
        $conditions[] = "brand_name IN (" . implode(',', $safe_brands) . ")";
    }
}

// ══ Rating filter (requires JOIN with reviews) ══
$rating_join  = "";
$rating_cond  = "";
$rating_group = "";
$rating_having = "";
$sort_by_rating = false;

$sort = $_GET['sort'] ?? 'default';

if (!empty($_GET['rating'])) {
    $min_rating = (float)$_GET['rating'];
    $rating_join   = "LEFT JOIN (SELECT pid, AVG(rating) as avg_rating FROM reviews GROUP BY pid) rv ON shop.pid = rv.pid";
    $rating_having = "HAVING avg_rating >= $min_rating OR avg_rating IS NULL";
    if ($min_rating > 0) {
        $rating_having = "HAVING avg_rating >= $min_rating";
    }
}

// ══ Sorting ══
$order = "ORDER BY is_featured DESC, pid DESC"; // default

switch ($sort) {
    case 'price_asc':
        $order = "ORDER BY productprice ASC";
        break;
    case 'price_desc':
        $order = "ORDER BY productprice DESC";
        break;
    case 'newest':
        $order = "ORDER BY pid DESC";
        break;
    case 'rating':
        if (empty($rating_join)) {
            $rating_join = "LEFT JOIN (SELECT pid, AVG(rating) as avg_rating FROM reviews GROUP BY pid) rv ON shop.pid = rv.pid";
        }
        $order = "ORDER BY avg_rating DESC, pid DESC";
        break;
    case 'discount':
        $order = "ORDER BY (CASE WHEN original_price > 0 THEN ((original_price - productprice) / original_price) ELSE 0 END) DESC";
        break;
}

$where = "WHERE " . implode(' AND ', $conditions);
$sql = "SELECT DISTINCT shop.* 
        FROM shop 
        $rating_join
        $where
        $rating_having
        $order";

$result = mysqli_query($cn, $sql);

if (!$result) {
    echo '<div class="no-results">
            <div class="emoji">⚠️</div>
            <h3>Query error</h3>
            <p>' . htmlspecialchars(mysqli_error($cn)) . '</p>
          </div>';
    exit;
}

if (mysqli_num_rows($result) == 0) {
    echo '<div class="no-results">
            <div class="emoji">🔍</div>
            <h3>No products found</h3>
            <p>Try adjusting your filters — we have plenty more!</p>
            <button onclick="clearAllFilters()" style="
              margin-top:14px;background:#D9A299;color:#fff;border:none;
              padding:10px 24px;border-radius:20px;cursor:pointer;
              font-family:\'DM Sans\',sans-serif;font-size:0.88rem;">
              Clear Filters
            </button>
          </div>';
    exit;
}

// ══ Render cards ══
while ($row = mysqli_fetch_array($result)) {
    $pid    = $row['pid'];
    $stock  = isset($row['stock_qty']) ? (int)$row['stock_qty'] : 0;
    $is_out = ($stock <= 0);
    $is_low = (!$is_out && $stock <= 5);

    $rr    = @mysqli_query($cn, "SELECT AVG(rating) as avg_r, COUNT(rating) as total_r FROM reviews WHERE pid='$pid'");
    $rd    = mysqli_fetch_assoc($rr);
    $avg_r = round((float)($rd['avg_r'] ?? 0), 1);
    $tot_r = (int)($rd['total_r'] ?? 0);

    $sell = (float)$row['productprice'];
    $mrp  = (!empty($row['original_price']) && $row['original_price'] > 0) ? (float)$row['original_price'] : $sell;
    $disc = ($mrp > $sell) ? round((($mrp - $sell) / $mrp) * 100) : 0;

    $del_val     = $row['delivery_type'] ?? 'Free Shipping';
    $is_free_del = (stripos($del_val, 'free') !== false);
    $del_label   = $is_free_del ? 'Free Delivery' : $del_val;

    $wishlisted = isWishlisted($cn, $pid, $current_user_email);
    $wish_class = $wishlisted ? 'wishlisted' : '';
    $wish_icon  = $wishlisted ? 'fas fa-heart' : 'far fa-heart';

    $is_feat = !empty($row['is_featured']) && $row['is_featured'] == 1;
    $bar_pct = $is_low ? min(100, ($stock / 10) * 100) : 0;

    echo '<div class="product-card ' . ($is_out ? 'out-of-stock-card' : '') . '">';
    echo '<div class="badge-wrap">';
    if ($is_feat)                   echo '<span class="badge-tag feat">⭐ Featured</span>';
    if (!empty($row['offer_text'])) echo '<span class="badge-tag offer">' . htmlspecialchars($row['offer_text']) . '</span>';
    if ($is_low && !$is_out)        echo '<span class="badge-tag low">Only ' . $stock . ' left!</span>';
    echo '</div>';

    echo '<button class="wish-btn ' . $wish_class . '" 
                  data-pid="' . (int)$pid . '" 
                  onclick="toggleWish(' . (int)$pid . ')" 
                  title="' . ($wishlisted ? 'Remove from Wishlist' : 'Add to Wishlist') . '">
            <i class="' . $wish_icon . '"></i>
          </button>';

    echo '<div class="product-image-container">';
    if ($is_out) echo '<div class="out-of-stock-overlay">OUT OF STOCK</div>';
    echo '<a href="order.php?pid=' . $pid . '">
            <img src="../admin-page/' . htmlspecialchars($row['productphoto']) . '" 
                 alt="' . htmlspecialchars($row['productname']) . '" 
                 loading="lazy"
                 onerror="this.src=\'https://via.placeholder.com/300x300/faf7f4/D9A299?text=No+Image\'">
          </a>';
    if ($avg_r > 0) {
        echo '<div class="rating-pill"><i class="fas fa-star"></i> ' . $avg_r . ' <span style="color:#bbb;">(' . $tot_r . ')</span></div>';
    }
    echo '</div>';

    if ($is_low) {
        echo '<div class="stock-bar-wrap">
                <div class="stock-bar-label">🔥 Selling fast — ' . $stock . ' left</div>
                <div class="stock-bar"><div class="stock-bar-fill" style="width:' . $bar_pct . '%"></div></div>
              </div>';
    }

    echo '<div class="product-info">';
    echo '<div class="brand-name-text">' . htmlspecialchars($row['brand_name'] ?? 'Stylevana') . '</div>';
    echo '<div class="product-name-desc">' . htmlspecialchars($row['productname']) . '</div>';
    echo '<div class="price-row">
            <span class="current-price">₹' . number_format($sell) . '</span>';
    if ($disc > 0) {
        echo '<span class="original-price">₹' . number_format($mrp) . '</span>
              <span class="disc-pill">' . $disc . '% OFF</span>';
    }
    echo '</div>';
    echo '<div class="delivery-tag ' . ($is_free_del ? 'free-del' : '') . '">
            <i class="fas fa-truck"></i> ' . htmlspecialchars($del_label) . '
          </div>';
    echo '<div class="card-btns">';
    if (!$is_out) {
        echo '<button class="btn-cart" onclick="addToCartByAjax(' . $pid . ')">
                <i class="fas fa-cart-plus"></i> Cart
              </button>
              <a href="order.php?pid=' . $pid . '" class="btn-buy">Buy Now</a>';
    } else {
        echo '<button class="btn-unavail" disabled>Currently Unavailable</button>';
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
}