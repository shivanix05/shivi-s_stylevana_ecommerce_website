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
$current_user_email = $_SESSION['user'] ?? '';

// ══ Global counts ══
$_total_res = mysqli_query($cn, "SELECT COUNT(*) as c FROM shop");
$_total_row = mysqli_fetch_assoc($_total_res);
$total_products = $_total_row['c'] ?? 0;
$_feat_res = mysqli_query($cn, "SELECT COUNT(*) as c FROM shop WHERE is_featured=1");
$_feat_row = mysqli_fetch_assoc($_feat_res);
$featured_count = $_feat_row['c'] ?? 0;

// ══ Fetch ALL real categories from DB ══
$cat_res = mysqli_query($cn, "SELECT DISTINCT category FROM shop WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
$db_categories = [];
while ($cr = mysqli_fetch_assoc($cat_res)) {
    $db_categories[] = $cr['category'];
}

// ══ Meta for known categories ══
function getCatMeta($cat) {
    $map = [
        'jewellery' => ['label'=>'Jewellery',  'icon'=>'💎', 'tagline'=>'Jewellery is forever; love is a treasure.',
                        'img'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRqeEhTWhCyXWBsN8P6mMNrTlS7NOkCgB8M7w&s',
                        'color'=>'#f5e6fa'],
        'makeup'    => ['label'=>'Makeup',     'icon'=>'💄', 'tagline'=>'Why waste money? Spend it on Makeup.',
                        'img'=>'https://cdn.britannica.com/35/222035-050-C68AD682/makeup-cosmetics.jpg',
                        'color'=>'#fde8e8'],
        'skincare'  => ['label'=>'Skincare',   'icon'=>'✨', 'tagline'=>'The best self-care is Skincare!',
                        'img'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcTijPt6vdS-qeUguYMJSvSeaWdPVlYtve4w&s',
                        'color'=>'#e8f5e9'],
        'clothes'   => ['label'=>'Fashion',    'icon'=>'👗', 'tagline'=>'Clothes describe your personality!',
                        'img'=>'https://www.shutterstock.com/image-photo/fashionable-clothes-boutique-store-london-600nw-589577570.jpg',
                        'color'=>'#fff3e0'],
        'clothing'  => ['label'=>'Fashion',    'icon'=>'👗', 'tagline'=>'Clothes describe your personality!',
                        'img'=>'https://www.shutterstock.com/image-photo/fashionable-clothes-boutique-store-london-600nw-589577570.jpg',
                        'color'=>'#fff3e0'],
    ];
    $key = strtolower(trim($cat));
    return $map[$key] ?? ['label'=>ucfirst($cat), 'icon'=>'🛍️', 'tagline'=>'Explore our collection!',
                          'img'=>'', 'color'=>'#f8f5f2'];
}

// ══ Wishlist check helper ══
function isWishlisted($cn, $pid, $email) {
    $e = mysqli_real_escape_string($cn, $email);
    $p = mysqli_real_escape_string($cn, $pid);
    $r = mysqli_query($cn, "SELECT 1 FROM wishlist WHERE user_email='$e' AND pid='$p'");
    return $r && mysqli_num_rows($r) > 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shivi's Stylevana – Shop</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="afterl-style.css">
  <style>
    :root {
      --bg:      #FAF7F4;
      --card:    #FFFFFF;
      --accent:  #D9A299;
      --accent2: #c48b81;
      --dark:    #282c3f;
      --muted:   #888;
      --border:  #f0e8e4;
      --rose:    #D9A299;
      --green:   #27ae60;
      --tag-bg:  #fce8e6;
    }
    body { background: var(--bg); font-family: 'DM Sans', sans-serif; color: var(--dark); }

    /* ══ HERO BANNER ══ */
    .hero-banner {
      background: linear-gradient(135deg, #282c3f 0%, #3d2c35 50%, #5c3d45 100%);
      color: white;
      padding: 52px 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 30px;
      position: relative;
      overflow: hidden;
      margin-bottom: 0;
    }
    .hero-banner::before {
      content: '';
      position: absolute; inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      pointer-events: none;
    }
    .hero-text { position: relative; z-index: 1; }
    .hero-text .eyebrow {
      font-size: 11px; letter-spacing: 3px; text-transform: uppercase;
      color: var(--accent); margin-bottom: 12px; font-weight: 600;
    }
    .hero-text h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(28px, 4vw, 48px);
      line-height: 1.15;
      margin-bottom: 16px;
    }
    .hero-text h1 span { color: var(--accent); }
    .hero-text p { color: rgba(255,255,255,.65); font-size: 14px; max-width: 380px; line-height: 1.7; }
    .hero-cta {
      display: inline-flex; align-items: center; gap: 8px;
      background: var(--accent); color: white;
      padding: 12px 26px; border-radius: 50px;
      font-weight: 600; font-size: 13px;
      text-decoration: none; margin-top: 22px;
      transition: transform .2s, box-shadow .2s;
    }
    .hero-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(217,162,153,.5); }
    .hero-floats { position: relative; z-index: 1; display: flex; gap: 16px; }
    .hero-float-card {
      background: rgba(255,255,255,.08);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,.12);
      border-radius: 20px; padding: 20px 24px;
      text-align: center; min-width: 110px;
    }
    .hero-float-card .num { font-family: 'Playfair Display', serif; font-size: 28px; color: var(--accent); }
    .hero-float-card .lbl { font-size: 11px; color: rgba(255,255,255,.55); margin-top: 4px; }

    /* ══ FLASH SALE STRIP ══ */
    .flash-strip {
      background: linear-gradient(90deg, #282c3f, #5c3d45, #282c3f);
      color: white; text-align: center;
      padding: 10px; font-size: 13px; font-weight: 500;
      letter-spacing: .5px;
    }
    .flash-strip .timer-pill {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--accent); border-radius: 20px;
      padding: 3px 12px; font-size: 12px; font-weight: 700;
      margin-left: 10px;
    }

    /* ══ CATEGORY STORY BUBBLES ══ */
    .story-section { padding: 32px 30px 20px; }
    .story-section h2 {
      font-family: 'Playfair Display', serif;
      font-size: 20px; margin-bottom: 22px; color: var(--dark);
    }
    .story-section h2 span { color: var(--accent); }
    .stories-row {
      display: flex; gap: 20px; overflow-x: auto;
      padding-bottom: 10px; scrollbar-width: none;
    }
    .stories-row::-webkit-scrollbar { display: none; }
    .story-item { flex-shrink: 0; text-align: center; cursor: pointer; }
    .story-ring {
      width: 80px; height: 80px; border-radius: 50%;
      padding: 3px;
      background: linear-gradient(135deg, var(--accent), #f5b8b0, #c48b81);
      transition: transform .25s;
    }
    .story-ring:hover { transform: scale(1.08); }
    .story-ring.active-cat {
      background: linear-gradient(135deg, #282c3f, var(--accent));
    }
    .story-ring img {
      width: 100%; height: 100%; border-radius: 50%;
      object-fit: cover; border: 3px solid white;
    }
    .story-ring .story-icon {
      width: 100%; height: 100%; border-radius: 50%;
      border: 3px solid white;
      background: var(--tag-bg);
      display: flex; align-items: center; justify-content: center;
      font-size: 26px;
    }
    .story-item span {
      display: block; font-size: 11px; font-weight: 600;
      color: var(--dark); margin-top: 8px; letter-spacing: .3px;
    }

    /* ══ SECTION HEAD ══ */
    .section-head {
      display: flex; align-items: flex-end;
      justify-content: space-between;
      padding: 30px 30px 16px;
    }
    .section-head .left h2 {
      font-family: 'Playfair Display', serif;
      font-size: 22px; color: var(--dark); margin-bottom: 4px;
    }
    .section-head .left p { font-size: 12px; color: var(--muted); }
    .view-all-btn {
      font-size: 12px; font-weight: 600; color: var(--accent2);
      text-decoration: none; display: flex; align-items: center; gap: 4px;
      border: 1.5px solid var(--accent2); padding: 6px 16px;
      border-radius: 20px; transition: all .2s;
    }
    .view-all-btn:hover { background: var(--accent2); color: white; }

    /* ══ PRODUCT GRID ══ */
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
      gap: 20px;
      padding: 0 30px 30px;
    }

    /* ══ PRODUCT CARD ══ */
    .product-card {
      background: var(--card);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 2px 12px rgba(0,0,0,.05);
      transition: transform .3s, box-shadow .3s;
      position: relative;
    }
    .product-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 14px 36px rgba(217,162,153,.2);
    }
    .product-card.out-of-stock-card { opacity: .75; }

    /* Badges */
    .badge-wrap {
      position: absolute; top: 10px; left: 10px;
      display: flex; flex-direction: column; gap: 5px; z-index: 2;
    }
    .badge-tag {
      display: inline-block; font-size: 9px; font-weight: 700;
      text-transform: uppercase; letter-spacing: 1px;
      padding: 3px 9px; border-radius: 20px;
    }
    .badge-tag.offer  { background: var(--accent); color: white; }
    .badge-tag.new    { background: #282c3f; color: white; }
    .badge-tag.low    { background: #ff9800; color: white; }
    .badge-tag.feat   { background: linear-gradient(90deg,#f7971e,#ffd200); color: #333; }

    /* ══ WISHLIST BUTTON ══ */
    .wish-btn {
      position: absolute; top: 10px; right: 10px; z-index: 3;
      width: 34px; height: 34px; border-radius: 50%;
      background: white; border: none; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 2px 10px rgba(0,0,0,.15);
      transition: transform .2s, box-shadow .2s;
      font-size: 15px;
      pointer-events: all;
    }
    .wish-btn:hover {
      transform: scale(1.2);
      box-shadow: 0 4px 16px rgba(231,76,60,.25);
    }
    .wish-btn i { color: #ccc; transition: color .2s, transform .2s; }
    .wish-btn.wishlisted i { color: #e74c3c; }
    .wish-btn.pop i { transform: scale(1.4); }

    /* Image area */
    .product-image-container {
      position: relative; overflow: hidden;
      height: 200px; background: #fafafa;
    }
    .product-image-container img {
      width: 100%; height: 100%; object-fit: cover;
      transition: transform .4s ease;
    }
    .product-card:hover .product-image-container img { transform: scale(1.06); }
    .out-of-stock-overlay {
      position: absolute; inset: 0;
      background: rgba(0,0,0,.42);
      display: flex; align-items: center; justify-content: center;
      color: white; font-weight: 700; font-size: 13px;
      letter-spacing: 2px; z-index: 1;
    }

    /* Rating pill */
    .rating-pill {
      position: absolute; bottom: 8px; left: 8px;
      background: rgba(255,255,255,.92);
      border-radius: 20px; padding: 3px 10px;
      font-size: 11px; font-weight: 600;
      display: flex; align-items: center; gap: 4px;
      color: var(--dark);
    }
    .rating-pill i { color: #f5a623; font-size: 10px; }

    /* Stock bar */
    .stock-bar-wrap { padding: 0 14px; margin-top: -2px; }
    .stock-bar-label { font-size: 10px; color: #ff6b35; font-weight: 600; margin-bottom: 3px; }
    .stock-bar { height: 3px; background: #f0e8e4; border-radius: 10px; overflow: hidden; }
    .stock-bar-fill { height: 100%; background: linear-gradient(90deg, #ff6b35, var(--accent)); border-radius: 10px; }

    /* Product info */
    .product-info { padding: 12px 14px 14px; }
    .brand-name-text {
      font-size: 10px; font-weight: 600; color: var(--accent2);
      text-transform: uppercase; letter-spacing: 1px; margin-bottom: 3px;
    }
    .product-name-desc {
      font-size: 13px; font-weight: 500; color: var(--dark);
      margin-bottom: 8px; line-height: 1.4;
      display: -webkit-box; -webkit-line-clamp: 2;
      -webkit-box-orient: vertical; overflow: hidden;
    }
    .price-row {
      display: flex; align-items: center; gap: 6px;
      flex-wrap: wrap; margin-bottom: 6px;
    }
    .current-price { font-size: 15px; font-weight: 700; color: var(--dark); }
    .original-price { font-size: 12px; color: var(--muted); text-decoration: line-through; }
    .disc-pill {
      background: #e8f5e9; color: var(--green);
      font-size: 10px; font-weight: 700;
      padding: 2px 7px; border-radius: 20px;
    }
    .delivery-tag {
      font-size: 11px; color: var(--muted);
      display: flex; align-items: center; gap: 4px;
      margin-bottom: 10px;
    }
    .delivery-tag i { color: var(--accent); }
    .delivery-tag.free-del { color: var(--green); font-weight: 600; }
    .delivery-tag.free-del i { color: var(--green); }

    /* Buttons */
    .card-btns { display: flex; gap: 8px; }
    .btn-cart {
      flex: 1; padding: 9px; border: 1.5px solid var(--accent);
      background: white; color: var(--accent);
      border-radius: 10px; font-size: 12px; font-weight: 600;
      cursor: pointer; transition: all .2s;
      display: flex; align-items: center; justify-content: center; gap: 5px;
    }
    .btn-cart:hover { background: var(--accent); color: white; }
    .btn-buy {
      flex: 1; padding: 9px; border: none;
      background: var(--dark); color: white;
      border-radius: 10px; font-size: 12px; font-weight: 600;
      text-decoration: none; cursor: pointer; transition: background .2s;
      display: flex; align-items: center; justify-content: center;
    }
    .btn-buy:hover { background: var(--accent); }
    .btn-unavail {
      width: 100%; padding: 9px; border: none;
      background: #eee; color: #999; border-radius: 10px;
      font-size: 12px; font-weight: 600; cursor: not-allowed;
    }

    /* ══ FEATURED STRIP ══ */
    .featured-strip {
      background: linear-gradient(135deg, #282c3f 0%, #3d2c35 100%);
      padding: 28px 30px; margin: 10px 30px 30px;
      border-radius: 24px; color: white;
      display: flex; align-items: center; justify-content: space-between; gap: 20px;
    }
    .featured-strip h3 { font-family: 'Playfair Display', serif; font-size: 20px; margin-bottom: 6px; }
    .featured-strip p { font-size: 13px; color: rgba(255,255,255,.6); }
    .featured-strip a {
      background: var(--accent); color: white;
      padding: 10px 24px; border-radius: 50px;
      text-decoration: none; font-size: 13px; font-weight: 600;
      white-space: nowrap; transition: opacity .2s;
    }
    .featured-strip a:hover { opacity: .85; }

    .category-section { margin-bottom: 10px; }
    .search-header { padding: 24px 30px 10px; font-family: 'Playfair Display', serif; font-size: 22px; }
    .search-header span { color: var(--accent); }

    .empty-state { text-align: center; padding: 80px 30px; color: var(--muted); }
    .empty-state .emoji { font-size: 52px; margin-bottom: 16px; }
    .empty-state h3 { font-family: 'Playfair Display', serif; font-size: 22px; margin-bottom: 8px; color: var(--dark); }

    .sec-divider { height: 1px; background: var(--border); margin: 0 30px 10px; }

    /* ══ TOAST ══ */
    #toast {
      position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%) translateY(80px);
      background: var(--dark); color: white;
      padding: 12px 24px; border-radius: 50px;
      font-size: 13px; font-weight: 500;
      z-index: 9999; transition: transform .35s ease, opacity .35s ease;
      opacity: 0; pointer-events: none; white-space: nowrap;
    }
    #toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }

    .swiper-button-next, .swiper-button-prev {
      color: #ff85a2 !important; background: white;
      width: 35px !important; height: 35px !important;
      border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .swiper-button-next:after, .swiper-button-prev:after { font-size: 14px !important; font-weight: bold; }
    #sv-recommendations { padding: 20px; border-radius: 25px; margin: 20px 0 40px 0; }

    @media (max-width: 768px) {
      .hero-banner { padding: 36px 20px; flex-direction: column; }
      .hero-floats { display: none; }
      .product-grid { grid-template-columns: repeat(2, 1fr); padding: 0 16px 20px; gap: 12px; }
      .story-section, .section-head { padding-left: 16px; padding-right: 16px; }
      .featured-strip { flex-direction: column; margin: 10px 16px 20px; }
    }
  </style>
</head>
<body>

<?php include("header.php"); ?>

<!-- ══ FLASH SALE STRIP ══ -->
<div class="flash-strip">
  🌸 Free Shipping on orders above ₹499 &nbsp;|&nbsp; New arrivals every week!
  <span class="timer-pill"><i class="fas fa-bolt"></i> <span id="flash-timer">Loading…</span></span>
</div>

<?php if (!$searchQuery && !$categoryFilter): ?>
<!-- ══ HERO BANNER ══ -->
<div class="hero-banner">
  <div class="hero-text">
    <div class="eyebrow">✦ New Season, New You</div>
    <h1>Your Style,<br>Your <span>Story</span></h1>
    <p>Discover curated fashion, skincare, makeup & jewellery — all in one beautiful place.</p>
    <a href="#main-products" class="hero-cta">Shop Now <i class="fas fa-arrow-right"></i></a>
  </div>
  <div class="hero-floats">
    <div class="hero-float-card">
      <div class="num"><?= $total_products ?>+</div>
      <div class="lbl">Products</div>
    </div>
    <div class="hero-float-card">
      <div class="num"><?= count($db_categories) ?></div>
      <div class="lbl">Categories</div>
    </div>
    <div class="hero-float-card">
      <div class="num"><?= $featured_count ?></div>
      <div class="lbl">Featured</div>
    </div>
  </div>
</div>
<?php endif; ?>

<main id="main-products">

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
            <img src="' . htmlspecialchars($row['productphoto']) . '" 
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
    echo '</div>';
    echo '</div>'; // /product-info
    echo '</div>'; // /product-card
}

// ══ SEARCH RESULTS ══
if ($searchQuery != "") {
    $sq = '%' . $searchQuery . '%';
    $search_res = mysqli_query($cn, "SELECT * FROM shop WHERE productname LIKE '$sq' OR brand_name LIKE '$sq' OR category LIKE '$sq'");
    echo '<div class="search-header">Results for "<span>' . htmlspecialchars($searchQuery) . '</span>"</div>';
    if (mysqli_num_rows($search_res) > 0) {
        echo '<div class="product-grid">';
        while ($row = mysqli_fetch_array($search_res)) {
            renderCard($cn, $row, $current_user_email);
        }
        echo '</div>';
    } else {
        echo '<div class="empty-state">
                <div class="emoji">🔍</div>
                <h3>No products found</h3>
                <p>Try searching for something else!</p>
              </div>';
    }
}

// ══ SINGLE CATEGORY VIEW ══
elseif ($categoryFilter) {
    $meta = getCatMeta($categoryFilter);
    echo '<div class="section-head">
            <div class="left">
              <h2>' . $meta['icon'] . ' ' . $meta['label'] . '</h2>
              <p>' . $meta['tagline'] . '</p>
            </div>
          </div>';
    $res = mysqli_query($cn, "SELECT * FROM shop WHERE category='" . $categoryFilter . "' ORDER BY is_featured DESC, pid DESC");
    if (mysqli_num_rows($res) > 0) {
        echo '<div class="product-grid">';
        while ($row = mysqli_fetch_array($res)) {
            renderCard($cn, $row, $current_user_email);
        }
        echo '</div>';
    } else {
        echo '<div class="empty-state">
                <div class="emoji">🛍️</div>
                <h3>Coming Soon!</h3>
                <p>We\'re adding new ' . $meta['label'] . ' products soon.</p>
              </div>';
    }
}

// ══ HOME PAGE ══
else {
    // Category Story Bubbles
    echo '<div class="story-section">
            <h2>Shop by <span>Category</span></h2>
            <div class="stories-row">';
    echo '<div class="story-item" onclick="window.location=\'viewall.php\'">
            <div class="story-ring"><div class="story-icon">🛍️</div></div>
            <span>All</span>
          </div>';
    foreach ($db_categories as $cat) {
        $m = getCatMeta($cat);
        echo '<div class="story-item" onclick="window.location=\'after-login.php?category=' . urlencode($cat) . '\'">
                <div class="story-ring"><div class="story-icon">' . $m['icon'] . '</div></div>
                <span>' . $m['label'] . '</span>
              </div>';
    }
    echo '</div></div>';

    // AI Recommendations
    echo '<div id="sv-recommendations"></div>';

    // Featured Products
    $feat_res = mysqli_query($cn, "SELECT * FROM shop WHERE is_featured=1 ORDER BY pid DESC LIMIT 8");
    if (mysqli_num_rows($feat_res) > 0) {
        echo '<div class="section-head">
                <div class="left">
                  <h2>⭐ Featured Picks</h2>
                  <p>Handpicked just for you</p>
                </div>
              </div>
              <div class="product-grid">';
        while ($row = mysqli_fetch_array($feat_res)) {
            renderCard($cn, $row, $current_user_email);
        }
        echo '</div>';
        echo '<div class="featured-strip">
                <div>
                  <h3>✨ Explore Everything</h3>
                  <p>Over ' . $total_products . ' products across ' . count($db_categories) . ' categories</p>
                </div>
                <a href="after-login.php#all-products">Browse All →</a>
              </div>';
    }

    // All categories section
    echo '<div id="all-products">';
    foreach ($db_categories as $cat) {
        $meta = getCatMeta($cat);
        $res  = mysqli_query($cn, "SELECT * FROM shop WHERE category='" . mysqli_real_escape_string($cn, $cat) . "' ORDER BY is_featured DESC, pid DESC");
        if (mysqli_num_rows($res) == 0) continue;

        echo '<div class="sec-divider"></div>';
        echo '<div class="section-head">
                <div class="left">
                  <h2>' . $meta['icon'] . ' ' . $meta['label'] . '</h2>
                  <p>' . $meta['tagline'] . '</p>
                </div>
                <a href="after-login.php?category=' . urlencode($cat) . '" class="view-all-btn">View All <i class="fas fa-chevron-right"></i></a>
              </div>';
        echo '<div class="product-grid">';
        $count = 0;
        while ($row = mysqli_fetch_array($res)) {
            renderCard($cn, $row, $current_user_email);
            $count++;
            if ($count >= 8) break;
        }
        echo '</div>';
    }
    echo '</div>'; // /all-products
}
?>

</main>

<div id="toast"></div>

<script>
// ══════════════════════════════════════════════════
// ✅ WISHLIST TOGGLE — FIXED
// fetch() API use ki — reliable across all browsers
// data-pid string/number mismatch fix kiya
// ══════════════════════════════════════════════════
function toggleWish(pid) {
    // String cast force karo — data-pid always string hota hai DOM mein
    var btn = document.querySelector('.wish-btn[data-pid="' + String(pid) + '"]');
    if (!btn) return;

    var icon = btn.querySelector('i');
    if (!icon) return;

    var isCurrentlyWishlisted = btn.classList.contains('wishlisted');

    // Optimistic UI update immediately
    if (isCurrentlyWishlisted) {
        btn.classList.remove('wishlisted');
        icon.className = 'far fa-heart';
        btn.title = 'Add to Wishlist';
    } else {
        btn.classList.add('wishlisted');
        icon.className = 'fas fa-heart';
        btn.title = 'Remove from Wishlist';
        icon.style.transform = 'scale(1.5)';
        setTimeout(function(){ icon.style.transform = 'scale(1)'; }, 300);
    }

    // fetch() — more reliable than XHR
    fetch('wishlist_toggle.php?pid=' + encodeURIComponent(pid))
        .then(function(res) {
            if (!res.ok) throw new Error('Server error: ' + res.status);
            return res.json();
        })
        .then(function(d) {
            // Sync with actual server state
            if (d.wishlisted) {
                btn.classList.add('wishlisted');
                icon.className = 'fas fa-heart';
                btn.title = 'Remove from Wishlist';
                showToast('💖 Added to Wishlist!');
            } else {
                btn.classList.remove('wishlisted');
                icon.className = 'far fa-heart';
                btn.title = 'Add to Wishlist';
                showToast('🤍 Removed from Wishlist');
            }
        })
        .catch(function(err) {
            // Revert optimistic update on error
            if (isCurrentlyWishlisted) {
                btn.classList.add('wishlisted');
                icon.className = 'fas fa-heart';
                btn.title = 'Remove from Wishlist';
            } else {
                btn.classList.remove('wishlisted');
                icon.className = 'far fa-heart';
                btn.title = 'Add to Wishlist';
            }
            showToast('❌ Kuch error aaya, dobara try karo');
        });
}

// ══ Add to Cart ══
function addToCartByAjax(pid) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "addcart.php?pid=" + pid, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            showToast('🛒 Added to Cart Successfully!');
            var badge = document.querySelector('.cart-badge');
            if (badge) {
                badge.textContent = (parseInt(badge.textContent) || 0) + 1;
            }
        }
    };
    xhr.send();
}

// ══ Toast ══
function showToast(msg, duration) {
    duration = duration || 2500;
    var t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(function(){ t.classList.remove('show'); }, duration);
}

// ══ Flash Sale Countdown ══
(function () {
    var now = new Date();
    var end = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 0, 0, 0);
    var el  = document.getElementById('flash-timer');
    if (!el) return;
    setInterval(function() {
        var diff = Math.max(0, end - new Date());
        var h = String(Math.floor(diff / 3600000)).padStart(2, '0');
        var m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
        var s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        el.textContent = h + ':' + m + ':' + s;
    }, 1000);
})();
</script>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="recommendation_widget.js"></script>
<script>
StylevanaRec.init({
    userEmail:  '<?php echo $current_user_email; ?>',
    currentPid: null,
    title:      '💖 Picked Just For You',
    subtitle:   'Slide for more style inspiration',
    limit:      15,
    renderType: 'slider'
});
</script>

<?php include("footer.php"); ?>
<?php include("cartscript.php"); ?>
</body>
</html>