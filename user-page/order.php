<?php 
require_once __DIR__ . "/config.php"; 
session_start();

if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();    
}

$user = $_SESSION['user'];
$current_user_email = $_SESSION['user'] ?? '';

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

// ── Build photo gallery array (only non-empty) ──
$all_photos = [];
if(!empty($product['productphoto'])) $all_photos[] = $product['productphoto'];
foreach(['photo2','photo3','photo4','photo5'] as $pcol) {
    if(!empty($product[$pcol])) $all_photos[] = $product[$pcol];
}
// Remove duplicates, keep order
$all_photos = array_values(array_unique($all_photos));
$photo_count = count($all_photos);

// Discount Logic
$current_p = (float)$product['productprice'];
$old_p     = (float)($product['original_price'] ?? $current_p);
$discount  = ($old_p > $current_p) ? round((($old_p - $current_p) / $old_p) * 100) : 0;

// Ratings
$avg_res  = mysqli_query($cn, "SELECT AVG(rating) as avg_rating, COUNT(rid) as total_reviews FROM reviews WHERE pid = '$pid'");
$avg_data = mysqli_fetch_array($avg_res);
$rating   = round((float)($avg_data['avg_rating'] ?? 0), 1);

// All reviews
$rev_list = mysqli_query($cn, "SELECT * FROM reviews WHERE pid = '$pid' ORDER BY rid DESC");

// Wishlist check
$wish_res      = mysqli_query($cn, "SELECT 1 FROM wishlist WHERE user_email='" . mysqli_real_escape_string($cn,$current_user_email) . "' AND pid='$pid'");
$is_wishlisted = ($wish_res && mysqli_num_rows($wish_res) > 0);

// Delivery label
$del_raw    = $product['delivery_type'] ?? 'Free Shipping';
$del_label  = is_numeric($del_raw) ? "₹" . $del_raw . " Delivery" : $del_raw;
$is_free_del = stripos($del_raw, 'free') !== false;

// Stock
$stock  = (int)($product['stock_qty'] ?? 0);
$is_out = ($stock <= 0);
$is_low = (!$is_out && $stock <= 5);

// Thumb view labels (up to 5)
$thumb_labels = ['Front', 'Side', 'Back', 'Detail', 'Lifestyle'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($product['productname']); ?> — Shivi's Stylevana</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,500&family=DM+Sans:wght@300;400;500;600&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg:       #FAF7F4;
      --card:     #FFFFFF;
      --accent:   #D9A299;
      --accent2:  #c48b81;
      --dark:     #282c3f;
      --muted:    #888;
      --border:   #f0e8e4;
      --green:    #27ae60;
      --red:      #e74c3c;
      --tag-bg:   #fce8e6;
      --soft:     #fff7f6;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: var(--bg); font-family: 'DM Sans', sans-serif; color: var(--dark); }

    .page-wrap { max-width: 1100px; margin: 36px auto; padding: 0 20px 60px; }

    /* Breadcrumb */
    .breadcrumb { font-size: 12px; color: var(--muted); margin-bottom: 24px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .breadcrumb a { color: var(--muted); text-decoration: none; }
    .breadcrumb a:hover { color: var(--accent); }
    .breadcrumb i { font-size: 9px; }

    /* Main product card */
    .product-main {
      display: grid; grid-template-columns: 1fr 1fr; gap: 48px;
      background: var(--card); border-radius: 32px; padding: 40px;
      box-shadow: 0 4px 32px rgba(0,0,0,.06);
    }

    /* ── GALLERY ── */
    .gallery-col { display: flex; flex-direction: column; gap: 12px; }

    .main-img-wrap {
      width: 100%; aspect-ratio: 1/1; border-radius: 24px; overflow: hidden;
      background: var(--soft); position: relative;
    }
    .main-img-wrap img {
      width: 100%; height: 100%; object-fit: cover;
      transition: transform .5s ease; display: block;
    }
    .main-img-wrap:hover img { transform: scale(1.04); }

    /* Badge on image */
    .img-badge {
      position: absolute; top: 14px; left: 14px;
      background: var(--accent); color: white;
      font-size: 10px; font-weight: 700;
      padding: 4px 12px; border-radius: 20px;
      text-transform: uppercase; letter-spacing: 1px; z-index: 2;
    }
    .img-badge.out  { background: #888; }
    .img-badge.low  { background: #ff9800; }
    .img-badge.feat { background: linear-gradient(90deg,#f7971e,#ffd200); color: #333; }

    /* Photo counter dot (top-right) */
    .photo-counter {
      position: absolute; top: 14px; right: 14px;
      background: rgba(0,0,0,.55); color: white;
      font-size: 11px; font-weight: 700; padding: 4px 10px;
      border-radius: 20px; z-index: 2;
      display: <?php echo $photo_count > 1 ? 'block' : 'none'; ?>;
    }

    /* Thumbnails row — responsive, up to 5 */
    .thumbs-row {
      display: grid;
      grid-template-columns: repeat(<?php echo min($photo_count, 5); ?>, 1fr);
      gap: 10px;
    }
    .thumb-wrapper { display: flex; flex-direction: column; }
    .thumb {
      aspect-ratio: 1/1; border-radius: 14px; overflow: hidden;
      border: 2px solid transparent; cursor: pointer;
      transition: border-color .2s, transform .2s;
      background: var(--soft);
    }
    .thumb:hover, .thumb.active { border-color: var(--accent); transform: scale(1.03); }
    .thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .thumb-label {
      font-size: 9px; color: var(--muted); text-align: center;
      margin-top: 4px; font-weight: 600; letter-spacing: .5px; text-transform: uppercase;
    }

    /* No extra photos message */
    .no-extra-photos {
      text-align: center; padding: 12px; font-size: 11px; color: var(--muted);
      background: var(--soft); border-radius: 12px; border: 1px dashed var(--border);
    }

    /* ── INFO COL ── */
    .info-col { display: flex; flex-direction: column; gap: 0; }

    .brand-script { font-family: 'Dancing Script', cursive; font-size: 20px; color: var(--accent); margin-bottom: 6px; }
    .prod-title { font-family: 'Playfair Display', serif; font-size: clamp(22px,3vw,32px); font-weight: 700; line-height: 1.2; color: var(--dark); margin-bottom: 14px; }

    .rating-row { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; }
    .stars-display { color: #f5a623; font-size: 14px; letter-spacing: 1px; }
    .rating-num { font-size: 14px; font-weight: 700; color: var(--dark); }
    .rating-count { font-size: 12px; color: var(--muted); }
    .rating-divider { color: var(--border); }

    .offer-ribbon {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--tag-bg); color: var(--accent2);
      font-size: 12px; font-weight: 700; padding: 5px 14px;
      border-radius: 20px; margin-bottom: 16px;
      text-transform: uppercase; letter-spacing: .5px;
    }

    .price-block { margin-bottom: 18px; }
    .price-main { font-size: 36px; font-weight: 700; color: var(--dark); line-height: 1; }
    .price-meta { display: flex; align-items: center; gap: 10px; margin-top: 6px; }
    .price-old { font-size: 16px; color: var(--muted); text-decoration: line-through; }
    .disc-chip { background: #e8f5e9; color: var(--green); font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }

    .delivery-info {
      display: flex; align-items: center; gap: 10px;
      background: #f0fdf9; border: 1px solid #d1fae5;
      border-radius: 14px; padding: 12px 16px; margin-bottom: 18px;
      font-size: 13px; color: #03a685;
    }
    .delivery-info i { font-size: 16px; }
    .delivery-info.paid { background: var(--soft); border-color: var(--border); color: var(--muted); }
    .delivery-info.paid i { color: var(--accent); }

    .stock-info { font-size: 12px; font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 6px; }
    .stock-info.ok  { color: var(--green); }
    .stock-info.low { color: #ff9800; }
    .stock-info.out { color: var(--red); }
    .stock-dot { width: 8px; height: 8px; border-radius: 50%; }
    .stock-dot.ok  { background: var(--green); }
    .stock-dot.low { background: #ff9800; }
    .stock-dot.out { background: var(--red); }

    .qty-row { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
    .qty-label { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; }
    .qty-ctrl { display: flex; align-items: center; border: 1.5px solid var(--border); border-radius: 12px; overflow: hidden; }
    .qty-btn { width: 36px; height: 36px; border: none; background: var(--soft); font-size: 16px; cursor: pointer; color: var(--dark); transition: background .15s; display: flex; align-items: center; justify-content: center; }
    .qty-btn:hover { background: var(--tag-bg); }
    .qty-num { width: 44px; text-align: center; font-size: 15px; font-weight: 700; border: none; outline: none; background: white; color: var(--dark); }

    .btn-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px; }
    .btn-row-full { margin-bottom: 10px; }
    .btn { padding: 14px 20px; border-radius: 14px; border: none; font-family: 'DM Sans', sans-serif; font-weight: 700; font-size: 13px; cursor: pointer; transition: all .25s; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; letter-spacing: .3px; }
    .btn-cart  { background: var(--accent); color: white; box-shadow: 0 6px 20px rgba(217,162,153,.35); }
    .btn-cart:hover  { background: var(--accent2); transform: translateY(-2px); }
    .btn-wish  { background: white; color: var(--red); border: 1.5px solid #fce8e6; }
    .btn-wish:hover  { background: #fce8e6; }
    .btn-wish.wishlisted { background: #fce8e6; }
    .btn-order { width: 100%; background: var(--dark); color: white; box-shadow: 0 6px 20px rgba(40,44,63,.2); }
    .btn-order:hover { background: #1a1e2d; transform: translateY(-2px); }
    .btn-sold  { width: 100%; background: #eee; color: #999; cursor: not-allowed; }
    .btn-notify { width: 100%; background: white; color: var(--accent2); border: 1.5px solid var(--accent); }

    .sec-divider { height: 1px; background: var(--border); margin: 20px 0; }
    .highlights { display: flex; flex-direction: column; gap: 10px; }
    .highlight-row { display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: var(--muted); }
    .highlight-row i { color: var(--accent); margin-top: 2px; font-size: 13px; }

    /* ── TABS ── */
    .tabs-section { margin-top: 36px; }
    .tab-nav { display: flex; gap: 0; border-bottom: 2px solid var(--border); margin-bottom: 28px; }
    .tab-btn { padding: 12px 24px; background: none; border: none; font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 14px; cursor: pointer; color: var(--muted); border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all .2s; }
    .tab-btn.active { color: var(--accent2); border-bottom-color: var(--accent2); }
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    .desc-text { font-size: 14px; line-height: 1.9; color: #666; background: var(--soft); border-radius: 20px; padding: 24px; }

    /* Reviews */
    .reviews-summary { display: grid; grid-template-columns: auto 1fr; gap: 32px; align-items: center; background: var(--soft); border-radius: 20px; padding: 28px; margin-bottom: 24px; }
    .big-rating .num { font-family: 'Playfair Display', serif; font-size: 56px; font-weight: 700; color: var(--dark); line-height: 1; }
    .big-rating .stars { color: #f5a623; font-size: 18px; margin: 6px 0; }
    .big-rating .count { font-size: 12px; color: var(--muted); }
    .rating-bars { flex: 1; display: flex; flex-direction: column; gap: 8px; }
    .rbar-row { display: flex; align-items: center; gap: 10px; font-size: 12px; color: var(--muted); }
    .rbar-row span:first-child { width: 30px; text-align: right; font-weight: 600; }
    .rbar { flex: 1; height: 6px; background: var(--border); border-radius: 10px; overflow: hidden; }
    .rbar-fill { height: 100%; background: linear-gradient(90deg, #f5a623, #f7c948); border-radius: 10px; transition: width .6s; }

    .review-card { background: white; border: 1px solid var(--border); border-radius: 18px; padding: 20px; margin-bottom: 14px; transition: box-shadow .2s; }
    .review-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.06); }
    .review-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; gap: 8px; }
    .reviewer-info { display: flex; align-items: center; gap: 10px; }
    .reviewer-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--tag-bg); display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; color: var(--accent2); text-transform: uppercase; }
    .reviewer-name { font-size: 13px; font-weight: 600; color: var(--dark); }
    .review-date { font-size: 11px; color: var(--muted); margin-top: 2px; }
    .review-stars { color: #f5a623; font-size: 12px; }
    .review-text { font-size: 13px; color: #666; line-height: 1.7; margin-bottom: 10px; }
    .admin-reply-box { background: var(--soft); border-left: 3px solid var(--accent); border-radius: 0 12px 12px 0; padding: 10px 14px; font-size: 12px; color: var(--muted); margin-top: 10px; }
    .admin-reply-box strong { color: var(--accent2); }

    /* Toast */
    #toast { position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%) translateY(80px); background: var(--dark); color: white; padding: 12px 28px; border-radius: 50px; font-size: 13px; font-weight: 600; z-index: 9999; transition: transform .3s, opacity .3s; opacity: 0; pointer-events: none; white-space: nowrap; }
    #toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }

    /* Swiper sections */
    #sv-recommendations, #sv-similar { margin-top: 40px; }
    .swiper-button-next, .swiper-button-prev { color: var(--accent) !important; background: white; width: 34px !important; height: 34px !important; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
    .swiper-button-next:after, .swiper-button-prev:after { font-size: 13px !important; font-weight: 800; }

    @media (max-width: 768px) {
      .product-main { grid-template-columns: 1fr; gap: 28px; padding: 20px; }
      .reviews-summary { grid-template-columns: 1fr; }
      .btn-row { grid-template-columns: 1fr 1fr; }
    }
  </style>
</head>
<body>

<?php include("header.php"); ?>

<div class="page-wrap">

  <!-- Breadcrumb -->
  <div class="breadcrumb">
    <a href="after-login.php">Home</a>
    <i class="fas fa-chevron-right"></i>
    <a href="after-login.php?category=<?php echo urlencode($product['category']); ?>"><?php echo htmlspecialchars(ucfirst($product['category'])); ?></a>
    <i class="fas fa-chevron-right"></i>
    <span><?php echo htmlspecialchars($product['productname']); ?></span>
  </div>

  <!-- ══ MAIN PRODUCT CARD ══ -->
  <div class="product-main">

    <!-- GALLERY COL -->
    <div class="gallery-col">
      <div class="main-img-wrap" id="mainImgWrap">

        <!-- Status badge -->
        <?php if($is_out): ?>
          <div class="img-badge out">Sold Out</div>
        <?php elseif($is_low): ?>
          <div class="img-badge low">Only <?php echo $stock; ?> Left</div>
        <?php elseif(!empty($product['is_featured']) && $product['is_featured']==1): ?>
          <div class="img-badge feat">⭐ Featured</div>
        <?php elseif(!empty($product['offer_text'])): ?>
          <div class="img-badge"><?php echo htmlspecialchars($product['offer_text']); ?></div>
        <?php endif; ?>

        <!-- Photo counter (e.g. 1/3) -->
        <div class="photo-counter" id="photoCounter">1 / <?php echo $photo_count; ?></div>

        <img src="<?php echo htmlspecialchars($all_photos[0]); ?>"
             alt="<?php echo htmlspecialchars($product['productname']); ?>"
             id="mainImg"
             onerror="this.src='https://via.placeholder.com/500x500/faf7f4/D9A299?text=Image+Not+Found'">
      </div>

      <!-- Thumbnails — all real photos from DB -->
      <?php if($photo_count > 0): ?>
      <div class="thumbs-row">
        <?php foreach($all_photos as $ti => $photo_src):
          $tlabel = $thumb_labels[$ti] ?? ('Photo ' . ($ti+1));
        ?>
        <div class="thumb-wrapper">
          <div class="thumb <?php echo $ti===0?'active':''; ?>"
               onclick="switchPhoto(this, <?php echo $ti; ?>)">
            <img src="<?php echo htmlspecialchars($photo_src); ?>"
                 alt="<?php echo htmlspecialchars($tlabel); ?>"
                 onerror="this.parentElement.style.display='none'; this.parentElement.nextElementSibling.style.display='none';">
          </div>
          <div class="thumb-label"><?php echo htmlspecialchars($tlabel); ?></div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

    </div><!-- /gallery-col -->

    <!-- INFO COL -->
    <div class="info-col">
      <div class="brand-script">
        <?php echo !empty($product['brand_name']) ? htmlspecialchars($product['brand_name']) : "Shivi's Stylevana"; ?> ✨
      </div>
      <h1 class="prod-title"><?php echo htmlspecialchars($product['productname']); ?></h1>

      <!-- Rating row -->
      <div class="rating-row">
        <div class="stars-display">
          <?php
          $full = floor($rating); $half = ($rating - $full) >= 0.5;
          for($s=1;$s<=5;$s++){
            if($s<=$full) echo '<i class="fas fa-star"></i>';
            elseif($s==$full+1 && $half) echo '<i class="fas fa-star-half-alt"></i>';
            else echo '<i class="far fa-star"></i>';
          }
          ?>
        </div>
        <span class="rating-num"><?php echo $rating > 0 ? $rating : '—'; ?></span>
        <span class="rating-divider">|</span>
        <span class="rating-count"><?php echo $avg_data['total_reviews']; ?> Reviews</span>
      </div>

      <?php if(!empty($product['offer_text'])): ?>
      <div class="offer-ribbon">
        <i class="fas fa-tag"></i> <?php echo htmlspecialchars($product['offer_text']); ?>
      </div>
      <?php endif; ?>

      <!-- Price -->
      <div class="price-block">
        <div class="price-main">₹<?php echo number_format($current_p); ?></div>
        <?php if($discount > 0): ?>
        <div class="price-meta">
          <span class="price-old">₹<?php echo number_format($old_p); ?></span>
          <span class="disc-chip"><?php echo $discount; ?>% OFF</span>
        </div>
        <?php endif; ?>
      </div>

      <!-- Delivery -->
      <div class="delivery-info <?php echo $is_free_del ? '' : 'paid'; ?>">
        <i class="fas fa-<?php echo $is_free_del ? 'gift' : 'truck'; ?>"></i>
        <div>
          <strong><?php echo htmlspecialchars($del_label); ?></strong>
          <div style="font-size:11px; margin-top:2px; opacity:.8;">Delivery in 3–5 working days</div>
        </div>
      </div>

      <!-- Stock status -->
      <?php if($is_out): ?>
        <div class="stock-info out"><span class="stock-dot out"></span> Out of Stock</div>
      <?php elseif($is_low): ?>
        <div class="stock-info low"><span class="stock-dot low"></span> Only <?php echo $stock; ?> left — order soon!</div>
      <?php else: ?>
        <div class="stock-info ok"><span class="stock-dot ok"></span> In Stock</div>
      <?php endif; ?>

      <!-- Qty & Buttons -->
      <?php if(!$is_out): ?>
      <div class="qty-row">
        <span class="qty-label">Qty</span>
        <div class="qty-ctrl">
          <button class="qty-btn" onclick="changeQty(-1)">−</button>
          <input class="qty-num" type="number" id="qtyInput" value="1" min="1" max="<?php echo min($stock,5); ?>" readonly>
          <button class="qty-btn" onclick="changeQty(1)">+</button>
        </div>
        <span style="font-size:12px; color:var(--muted);">(Max <?php echo min($stock,5); ?>)</span>
      </div>

      <div class="btn-row">
        <button class="btn btn-cart" onclick="addToCartByAjax(<?php echo $pid; ?>)">
          <i class="fas fa-shopping-bag"></i> Add to Bag
        </button>
        <button class="btn btn-wish <?php echo $is_wishlisted ? 'wishlisted' : ''; ?>" id="wishBtn" onclick="toggleWish(<?php echo $pid; ?>)">
          <i class="<?php echo $is_wishlisted ? 'fas' : 'far'; ?> fa-heart"></i>
          <?php echo $is_wishlisted ? 'Wishlisted' : 'Wishlist'; ?>
        </button>
      </div>
      <div class="btn-row-full">
        <a href="checkout.php?buy_pid=<?php echo $pid; ?>&qty=1" id="checkoutBtn" class="btn btn-order">
          <i class="fas fa-bolt"></i> Order Now
        </a>
      </div>

      <?php else: ?>
      <div class="btn-row-full" style="margin-bottom:10px;">
        <button class="btn btn-sold" disabled>
          <i class="fas fa-moon"></i> Currently Unavailable
        </button>
      </div>
      <div class="btn-row-full">
        <button class="btn btn-notify" onclick="toggleWish(<?php echo $pid; ?>)">
          <i class="far fa-bell"></i> Notify Me / Wishlist
        </button>
      </div>
      <?php endif; ?>

      <div class="sec-divider"></div>

      <div class="highlights">
        <div class="highlight-row"><i class="fas fa-shield-alt"></i> 100% Authentic Products</div>
        <div class="highlight-row"><i class="fas fa-undo"></i> Easy 7-Day Returns</div>
        <div class="highlight-row"><i class="fas fa-lock"></i> Secure Checkout</div>
        <div class="highlight-row"><i class="fas fa-headset"></i> 24/7 Customer Support</div>
      </div>
    </div><!-- /info-col -->

  </div><!-- /product-main -->

  <!-- ══ TABS ══ -->
  <div class="tabs-section">
    <div class="tab-nav">
      <button class="tab-btn active" onclick="switchTab('desc', this)">Description</button>
      <button class="tab-btn" onclick="switchTab('reviews', this)">
        Reviews (<?php echo $avg_data['total_reviews']; ?>)
      </button>
    </div>

    <!-- Description -->
    <div class="tab-panel active" id="tab-desc">
      <div class="desc-text">
        <?php echo nl2br(htmlspecialchars($product['productdescription'] ?? 'No description available.')); ?>
      </div>
    </div>

    <!-- Reviews -->
    <div class="tab-panel" id="tab-reviews">
      <?php
      $dist = [5=>0,4=>0,3=>0,2=>0,1=>0];
      $all_rev_for_dist = mysqli_query($cn, "SELECT rating FROM reviews WHERE pid='$pid'");
      while($dr = mysqli_fetch_assoc($all_rev_for_dist)){
        $r = (int)$dr['rating'];
        if(isset($dist[$r])) $dist[$r]++;
      }
      $total_rev = array_sum($dist);
      ?>
      <?php if($total_rev > 0): ?>
      <div class="reviews-summary">
        <div class="big-rating">
          <div class="num"><?php echo $rating; ?></div>
          <div class="stars"><?php for($s=1;$s<=5;$s++) echo ($s<=$rating)?'★':'☆'; ?></div>
          <div class="count"><?php echo $total_rev; ?> ratings</div>
        </div>
        <div class="rating-bars">
          <?php for($star=5; $star>=1; $star--): ?>
          <?php $pct = $total_rev > 0 ? round(($dist[$star]/$total_rev)*100) : 0; ?>
          <div class="rbar-row">
            <span><?php echo $star; ?> ★</span>
            <div class="rbar"><div class="rbar-fill" style="width:<?php echo $pct; ?>%"></div></div>
            <span><?php echo $dist[$star]; ?></span>
          </div>
          <?php endfor; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php
      mysqli_data_seek($rev_list, 0);
      $rev_count = 0;
      while($r = mysqli_fetch_array($rev_list)):
        $rev_count++;
        $initials = strtoupper(substr($r['user_email'], 0, 1));
        $stars_r  = (int)$r['rating'];
        $rev_date = !empty($r['review_date']) ? date("d M Y", strtotime($r['review_date'])) : '';
      ?>
      <div class="review-card">
        <div class="review-top">
          <div class="reviewer-info">
            <div class="reviewer-avatar"><?php echo $initials; ?></div>
            <div>
              <div class="reviewer-name"><?php echo htmlspecialchars(explode('@',$r['user_email'])[0]); ?></div>
              <div class="review-date"><?php echo $rev_date; ?></div>
            </div>
          </div>
          <div class="review-stars">
            <?php for($s=1;$s<=5;$s++) echo ($s<=$stars_r)?'<i class="fas fa-star"></i>':'<i class="far fa-star"></i>'; ?>
          </div>
        </div>
        <div class="review-text"><?php echo htmlspecialchars($r['comment']); ?></div>
        <?php if(!empty($r['rev_photo'])): ?>
        <div style="margin-top:12px;">
          <img src="<?php echo htmlspecialchars($r['rev_photo']); ?>"
               alt="Review photo"
               style="width:120px;height:120px;object-fit:cover;border-radius:10px;border:1px solid var(--border);"
               onerror="this.style.display='none'">
        </div>
        <?php endif; ?>
        <?php if(!empty($r['admin_reply'])): ?>
        <div class="admin-reply-box">
          <strong>✦ Stylevana replied:</strong> <?php echo htmlspecialchars($r['admin_reply']); ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endwhile; ?>

      <?php if($rev_count === 0): ?>
      <div style="text-align:center;padding:40px;color:var(--muted);">
        <div style="font-size:40px;margin-bottom:12px;">🌸</div>
        <div style="font-weight:600;">No reviews yet for this product.</div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Similar & Recommendations -->
  <div id="sv-similar"></div>
  <div id="sv-recommendations"></div>

</div><!-- /page-wrap -->

<div id="toast"></div>

<!-- Pass all photos to JS as a JSON array -->
<script>
var productPhotos = <?php echo json_encode($all_photos); ?>;
var photoCount    = <?php echo $photo_count; ?>;

// Switch main image on thumbnail click
function switchPhoto(thumbEl, index) {
  document.querySelectorAll('.thumb').forEach(function(t){ t.classList.remove('active'); });
  thumbEl.classList.add('active');
  var mainImg = document.getElementById('mainImg');
  mainImg.src = productPhotos[index];
  // Update counter
  document.getElementById('photoCounter').textContent = (index+1) + ' / ' + photoCount;
}

// Quantity
function changeQty(delta) {
  var inp = document.getElementById('qtyInput');
  var max = parseInt(inp.max) || 5;
  var val = parseInt(inp.value) || 1;
  val = Math.min(max, Math.max(1, val + delta));
  inp.value = val;
  document.getElementById('checkoutBtn').href = 'checkout.php?buy_pid=<?php echo $pid; ?>&qty=' + val;
}

// Tabs
function switchTab(name, btn) {
  document.querySelectorAll('.tab-panel').forEach(function(p){ p.classList.remove('active'); });
  document.querySelectorAll('.tab-btn').forEach(function(b){ b.classList.remove('active'); });
  document.getElementById('tab-' + name).classList.add('active');
  btn.classList.add('active');
}

// Add to Cart
function addToCartByAjax(pid) {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "addcart.php?pid=" + pid, true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      showToast('🛒 Added to Bag!');
      var badge = document.querySelector('.cart-badge');
      if (badge) badge.textContent = (parseInt(badge.textContent) || 0) + 1;
    }
  };
  xhr.send();
}

// ══════════════════════════════════════════════════
// ✅ WISHLIST TOGGLE — FIXED (order.php)
// id="wishBtn" wala button hai yahan
// fetch() use ki — reliable, error handling proper
// ══════════════════════════════════════════════════
function toggleWish(pid) {
  var btn = document.getElementById('wishBtn');
  if (!btn) return;

  var isWishlisted = btn.classList.contains('wishlisted');

  // Optimistic UI update
  if (isWishlisted) {
    btn.classList.remove('wishlisted');
    btn.innerHTML = '<i class="far fa-heart"></i> Wishlist';
  } else {
    btn.classList.add('wishlisted');
    btn.innerHTML = '<i class="fas fa-heart"></i> Wishlisted';
  }

  // fetch() to server
  fetch('wishlist_toggle.php?pid=' + encodeURIComponent(pid))
    .then(function(res) {
      if (!res.ok) throw new Error('Server error: ' + res.status);
      return res.json();
    })
    .then(function(d) {
      if (d.wishlisted) {
        btn.classList.add('wishlisted');
        btn.innerHTML = '<i class="fas fa-heart"></i> Wishlisted';
        showToast('💖 Added to Wishlist!');
      } else {
        btn.classList.remove('wishlisted');
        btn.innerHTML = '<i class="far fa-heart"></i> Wishlist';
        showToast('💔 Removed from Wishlist');
      }
    })
    .catch(function() {
      // Revert on error
      if (isWishlisted) {
        btn.classList.add('wishlisted');
        btn.innerHTML = '<i class="fas fa-heart"></i> Wishlisted';
      } else {
        btn.classList.remove('wishlisted');
        btn.innerHTML = '<i class="far fa-heart"></i> Wishlist';
      }
      showToast('❌ Kuch error aaya, dobara try karo');
    });
}

// Toast
function showToast(msg) {
  var t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(function(){ t.classList.remove('show'); }, 2500);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="recommendation_widget.js"></script>
<script>
StylevanaRec.initSimilar({
  currentPid:  '<?php echo $pid; ?>',
  containerId: 'sv-similar',
  title:       '🛍️ Customers Also Bought These',
  limit:       6
});
StylevanaRec.init({
  userEmail:   '<?php echo $current_user_email; ?>',
  currentPid:  '<?php echo $pid; ?>',
  containerId: 'sv-recommendations',
  title:       '✨ Picked Just For You',
  subtitle:    'Based on your style',
  limit:       8
});
</script>

<?php include("footer.php"); ?>
<?php include("cartscript.php"); ?>
</body>
</html>