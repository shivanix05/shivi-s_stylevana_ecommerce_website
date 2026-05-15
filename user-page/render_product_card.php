<?php
// ══ PRODUCT CARD RENDERER ══
function renderCard($cn, $row, $current_user_email) {
    $pid    = $row['pid'];
    $stock  = isset($row['stock_qty']) ? (int)$row['stock_qty'] : 0;
    $is_out = ($stock <= 0);
    $is_low = (!$is_out && $stock <= 5);

    // Rating
    $rr    = @mysqli_query($cn, "SELECT AVG(rating) as avg_r, COUNT(rating) as total_r FROM reviews WHERE pid='$pid'");
    $rd    = mysqli_fetch_assoc($rr);
    $avg_r = round((float)($rd['avg_r'] ?? 0), 1);
    $tot_r = (int)($rd['total_r'] ?? 0);

    // Price
    $sell = (float)$row['productprice'];
    $mrp  = (!empty($row['original_price']) && $row['original_price'] > 0) ? (float)$row['original_price'] : $sell;
    $disc = ($mrp > $sell) ? round((($mrp - $sell) / $mrp) * 100) : 0;

    // Delivery
    $del_val     = $row['delivery_type'] ?? 'Free Shipping';
    $is_free_del = (stripos($del_val, 'free') !== false);
    $del_label   = $is_free_del ? 'Free Delivery' : $del_val;

    // Wishlist — server-side check for initial state
    $wishlisted = isWishlisted($cn, $pid, $current_user_email);
    $wish_class = $wishlisted ? 'wishlisted' : '';
    $wish_icon  = $wishlisted ? 'fas fa-heart' : 'far fa-heart';

    // Featured
    $is_feat = !empty($row['is_featured']) && $row['is_featured'] == 1;

    // Low stock bar
    $bar_pct = $is_low ? min(100, ($stock / 10) * 100) : 0;

    echo '<div class="product-card ' . ($is_out ? 'out-of-stock-card' : '') . '">';

    // Badges
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

    // Image
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

    // Low stock bar
    if ($is_low) {
        echo '<div class="stock-bar-wrap">
                <div class="stock-bar-label">🔥 Selling fast — ' . $stock . ' left</div>
                <div class="stock-bar"><div class="stock-bar-fill" style="width:' . $bar_pct . '%"></div></div>
              </div>';
    }

    // Info
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

    // Cart/Buy buttons
    echo '<div class="card-btns">';
    if (!$is_out) {
        echo '<button class="btn-cart" onclick="addToCartByAjax(' . $pid . ')">
                <i class="fas fa-cart-plus"></i> Cart
              </button>
              <a href="order.php?pid=' . $pid . '" class="btn-buy">Buy Now</a>';
    } else {
        echo '<button class="btn-unavail" disabled>Currently Unavailable</button>';
    }
    echo '</div>'; // /card-btns
    echo '</div>'; // /product-info
    echo '</div>'; // /product-card
}