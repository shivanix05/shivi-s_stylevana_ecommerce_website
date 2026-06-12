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

$_total_res = mysqli_query($cn, "SELECT COUNT(*) as c FROM shop");
$_total_row = mysqli_fetch_assoc($_total_res);
$total_products = $_total_row['c'] ?? 0;

$_feat_res = mysqli_query($cn, "SELECT COUNT(*) as c FROM shop WHERE is_featured=1");
$_feat_row = mysqli_fetch_assoc($_feat_res);
$featured_count = $_feat_row['c'] ?? 0;

$cat_res = mysqli_query($cn, "SELECT DISTINCT category FROM shop WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
$db_categories = [];
while ($cr = mysqli_fetch_assoc($cat_res)) {
    $db_categories[] = $cr['category'];
}

$brand_res = mysqli_query($cn, "SELECT DISTINCT brand_name FROM shop WHERE brand_name IS NOT NULL AND brand_name != '' ORDER BY brand_name ASC");
$db_brands = [];
while ($br = mysqli_fetch_assoc($brand_res)) {
    $db_brands[] = $br['brand_name'];
}

$price_res = mysqli_query($cn, "SELECT MIN(productprice) as min_p, MAX(productprice) as max_p FROM shop");
$price_row = mysqli_fetch_assoc($price_res);
$min_price = (int)($price_row['min_p'] ?? 0);
$max_price = (int)($price_row['max_p'] ?? 10000);

function getCatMeta($cat) {
    $map = [
        'jewellery' => ['label'=>'Jewellery',  'icon'=>'💎', 'tagline'=>'Jewellery is forever; love is a treasure.',
                        'img'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRqeEhTWhCyXWBsN8P6mMNrTlS7NOkCgB8M7w&s',
                        'color'=>'#f5e6fa',  'page'=>'jewellery.php'],
        'makeup'    => ['label'=>'Makeup',     'icon'=>'💄', 'tagline'=>'Why waste money? Spend it on Makeup.',
                        'img'=>'https://cdn.britannica.com/35/222035-050-C68AD682/makeup-cosmetics.jpg',
                        'color'=>'#fde8e8',  'page'=>'makeup.php'],
        'skincare'  => ['label'=>'Skincare',   'icon'=>'✨', 'tagline'=>'The best self-care is Skincare!',
                        'img'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcTijPt6vdS-qeUguYMJSvSeaWdPVlYtve4w&s',
                        'color'=>'#e8f5e9',  'page'=>'skincare.php'],
        'clothes'   => ['label'=>'Fashion',    'icon'=>'👗', 'tagline'=>'Clothes describe your personality!',
                        'img'=>'https://www.shutterstock.com/image-photo/fashionable-clothes-boutique-store-london-600nw-589577570.jpg',
                        'color'=>'#fff3e0',  'page'=>'clothes.php'],
        'clothing'  => ['label'=>'Fashion',    'icon'=>'👗', 'tagline'=>'Clothes describe your personality!',
                        'img'=>'https://www.shutterstock.com/image-photo/fashionable-clothes-boutique-store-london-600nw-589577570.jpg',
                        'color'=>'#fff3e0',  'page'=>'clothes.php'],
    ];
    $key = strtolower(trim($cat));
    return $map[$key] ?? ['label'=>ucfirst($cat), 'icon'=>'🛍️', 'tagline'=>'Explore our collection!',
                          'img'=>'', 'color'=>'#f8f5f2', 'page'=>'after-login.php?category='.urlencode($cat)];
}

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
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="afterl-style.css">
  <style>
    /* ══════════════════════════════════════════
       HERO SLIDER
    ══════════════════════════════════════════ */
    .sv-hero-wrap {
      position: relative;
      width: 100%;
      overflow: hidden;
      border-radius: 0 0 36px 36px;
      margin-bottom: 44px;
      background: #f8f0ee;
    }
    .sv-slides {
      display: flex;
      width: 500%;
      transition: transform 0.9s cubic-bezier(0.77,0,0.18,1);
      will-change: transform;
    }
    .sv-slide {
      width: 20%;
      position: relative;
      height: 560px;
      overflow: hidden;
      flex-shrink: 0;
    }
    @media(max-width:768px){ .sv-slide{height:440px;} .sv-hero-wrap{border-radius:0 0 20px 20px;margin-bottom:28px;} }
    @media(max-width:480px){ .sv-slide{height:380px;} }

    .sv-slide-bg {
      position: absolute;
      inset: 0;
      background-size: cover;
      background-position: center;
      transition: transform 7s ease;
    }
    .sv-slide.active .sv-slide-bg { transform: scale(1); }

    .sv-slide::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(to bottom, transparent 45%, rgba(0,0,0,0.45) 100%);
      z-index: 1;
    }

    /* ── Slide Content ── */
    .sv-slide-content {
      position: absolute;
      bottom: 0; left: 0; right: 0;
      z-index: 3;
      padding: 0 60px 52px;
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.75s ease 0.35s, transform 0.75s ease 0.35s;
    }
    .sv-slide.active .sv-slide-content { opacity: 1; transform: translateY(0); }
    @media(max-width:768px){ .sv-slide-content{padding:0 22px 36px;} }

    .sv-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255,255,255,0.75);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(217,162,153,0.35);
      color: #b06060;
      font-family: 'DM Sans', sans-serif;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.13em;
      text-transform: uppercase;
      padding: 5px 15px;
      border-radius: 50px;
      margin-bottom: 13px;
    }
    .sv-slide-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.85rem, 4vw, 3.2rem);
      font-weight: 700;
      color: #fff;
      line-height: 1.13;
      margin: 0 0 11px;
      max-width: 600px;
      text-shadow: 0 2px 12px rgba(0,0,0,0.55);
    }
    .sv-slide-title em {
      font-style: italic;
      color: #ffd4c8;
    }
    .sv-slide-sub {
      font-family: 'DM Sans', sans-serif;
      font-size: 14.5px;
      font-weight: 400;
      color: rgba(255,255,255,0.92);
      margin: 0 0 26px;
      max-width: 420px;
      line-height: 1.65;
      text-shadow: 0 1px 6px rgba(0,0,0,0.4);
    }
    @media(max-width:480px){ .sv-slide-sub{display:none;} }

    .sv-slide-actions { display:flex; gap:12px; flex-wrap:wrap; align-items:center; }

    .sv-btn-main {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: linear-gradient(135deg,#D9A299,#c47870);
      color: #fff;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 600;
      padding: 13px 28px;
      border-radius: 50px;
      text-decoration: none;
      border: none;
      cursor: pointer;
      letter-spacing: 0.03em;
      box-shadow: 0 4px 20px rgba(217,162,153,0.45);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .sv-btn-main:hover { transform:translateY(-2px); box-shadow:0 8px 28px rgba(217,162,153,0.55); color:#fff; text-decoration:none; }

    .sv-btn-ghost {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: rgba(255,255,255,0.11);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      color: #fff;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      font-weight: 500;
      padding: 12px 22px;
      border-radius: 50px;
      text-decoration: none;
      border: 1px solid rgba(255,255,255,0.26);
      cursor: pointer;
      transition: background 0.2s;
    }
    .sv-btn-ghost:hover { background:rgba(255,255,255,0.20); color:#fff; text-decoration:none; }

    /* ── Stats bar (bottom right, desktop) ── */
    .sv-stats-bar {
      position: absolute;
      bottom: 52px; right: 52px;
      z-index: 4;
      display: flex;
      background: rgba(255,255,255,0.10);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid rgba(255,255,255,0.18);
      border-radius: 18px;
      padding: 14px 22px;
      gap: 0;
      animation: svStatsIn 0.9s ease 1s both;
    }
    @keyframes svStatsIn { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
    @media(max-width:900px){ .sv-stats-bar{display:none;} }

    .sv-stat { padding: 0 18px; border-right:1px solid rgba(255,255,255,0.17); text-align:center; }
    .sv-stat:first-child { padding-left:0; }
    .sv-stat:last-child  { border-right:none; padding-right:0; }
    .sv-stat-num {
      display:block;
      font-family:'Playfair Display',serif;
      font-size:1.3rem; font-weight:700;
      color:#f9bfaf; line-height:1;
    }
    .sv-stat-lbl {
      display:block;
      font-family:'DM Sans',sans-serif;
      font-size:10px; color:rgba(255,255,255,0.60);
      text-transform:uppercase; letter-spacing:0.08em; margin-top:3px;
    }

    /* ── Dots ── */
    .sv-dots {
      position:absolute; bottom:18px; left:50%; transform:translateX(-50%);
      z-index:5; display:flex; gap:8px; align-items:center;
    }
    .sv-dot {
      width:7px; height:7px; border-radius:50%;
      background:rgba(255,255,255,0.35);
      border:none; padding:0; cursor:pointer;
      transition: all 0.3s;
    }
    .sv-dot.active { width:26px; border-radius:4px; background:#D9A299; }

    /* ── Arrows ── */
    .sv-nav {
      position:absolute; top:50%; transform:translateY(-50%);
      z-index:5; width:44px; height:44px; border-radius:50%;
      background:rgba(255,255,255,0.12);
      backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px);
      border:1px solid rgba(255,255,255,0.22);
      color:#fff; font-size:1rem;
      display:flex; align-items:center; justify-content:center;
      cursor:pointer; transition: background 0.2s, transform 0.2s;
    }
    .sv-nav:hover { background:rgba(217,162,153,0.42); transform:translateY(-50%) scale(1.07); }
    .sv-nav-prev { left:18px; }
    .sv-nav-next { right:18px; }
    @media(max-width:540px){ .sv-nav{display:none;} }

    /* ── Slide counter tag ── */
    .sv-count-tag {
      position:absolute; top:20px; right:20px; z-index:5;
      font-family:'DM Sans',sans-serif; font-size:12px;
      color:rgba(255,255,255,0.60);
      background:rgba(0,0,0,0.22); padding:4px 13px;
      border-radius:20px; backdrop-filter:blur(6px);
      -webkit-backdrop-filter:blur(6px); letter-spacing:0.06em;
    }
    .sv-count-cur { color:#f9bfaf; font-weight:600; font-size:14px; }

    /* ── Progress bar ── */
    .sv-progress {
      position:absolute; bottom:0; left:0; height:3px;
      background:linear-gradient(90deg,#D9A299,#f0b5a5);
      z-index:6; width:0%; border-radius:0 2px 2px 0;
    }

    /* ── Floating petals ── */
    .sv-petals { position:absolute; inset:0; z-index:2; pointer-events:none; overflow:hidden; }
    .sv-petal {
      position:absolute; border-radius:50% 0 50% 0; opacity:0;
      animation: svPetalFall linear infinite;
    }
    @keyframes svPetalFall {
      0%   { opacity:0; transform:translateY(-20px) rotate(0deg); }
      10%  { opacity:0.55; }
      90%  { opacity:0.25; }
      100% { opacity:0; transform:translateY(600px) rotate(400deg); }
    }

    /* ══════════════════════════════════════════
       FILTER SIDEBAR & CONTROLS  (unchanged)
    ══════════════════════════════════════════ */
    .shop-layout {
      display:flex; gap:24px;
      max-width:1400px; margin:0 auto;
      padding:0 20px 60px; align-items:flex-start;
    }
    .filter-sidebar {
      width:260px; min-width:260px; background:#fff;
      border-radius:18px; box-shadow:0 4px 24px rgba(0,0,0,0.07);
      padding:24px 20px; position:sticky; top:90px;
      max-height:calc(100vh - 110px); overflow-y:auto;
      scrollbar-width:thin; scrollbar-color:#D9A299 #f8f5f2;
    }
    .filter-sidebar::-webkit-scrollbar{width:4px;}
    .filter-sidebar::-webkit-scrollbar-thumb{background:#D9A299;border-radius:4px;}
    .filter-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:14px;border-bottom:1px solid #f0e8e5;}
    .filter-header h3{font-family:'Playfair Display',serif;font-size:1.15rem;color:#333;margin:0;}
    .filter-clear-btn{background:none;border:1px solid #D9A299;color:#D9A299;font-size:0.75rem;padding:4px 10px;border-radius:20px;cursor:pointer;transition:all 0.2s;font-family:'DM Sans',sans-serif;}
    .filter-clear-btn:hover{background:#D9A299;color:#fff;}
    .filter-section{margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid #f8f5f2;}
    .filter-section:last-child{border-bottom:none;margin-bottom:0;}
    .filter-section-title{font-family:'DM Sans',sans-serif;font-weight:600;font-size:0.85rem;color:#555;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:12px;display:flex;align-items:center;justify-content:space-between;cursor:pointer;}
    .filter-section-title i{font-size:0.7rem;color:#aaa;transition:transform 0.2s;}
    .filter-section-title.collapsed i{transform:rotate(-90deg);}
    .filter-body{overflow:hidden;transition:max-height 0.3s ease;}
    .filter-body.collapsed{max-height:0 !important;}
    .sort-select{width:100%;padding:9px 12px;border:1.5px solid #e8ddd8;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:0.88rem;color:#444;background:#faf7f5;cursor:pointer;outline:none;transition:border-color 0.2s;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23D9A299' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:32px;}
    .sort-select:focus{border-color:#D9A299;}
    .price-range-wrap{padding:4px 0;}
    .price-range-inputs{display:flex;gap:8px;margin-bottom:10px;}
    .price-range-inputs input{width:50%;padding:7px 10px;border:1.5px solid #e8ddd8;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.82rem;color:#444;background:#faf7f5;outline:none;transition:border-color 0.2s;}
    .price-range-inputs input:focus{border-color:#D9A299;}
    .price-range-slider{-webkit-appearance:none;appearance:none;width:100%;height:4px;border-radius:4px;background:linear-gradient(to right,#D9A299 0%,#D9A299 var(--val,100%),#e8ddd8 var(--val,100%),#e8ddd8 100%);outline:none;cursor:pointer;margin-top:4px;}
    .price-range-slider::-webkit-slider-thumb{-webkit-appearance:none;appearance:none;width:18px;height:18px;border-radius:50%;background:#D9A299;cursor:pointer;box-shadow:0 2px 6px rgba(217,162,153,0.4);border:2px solid #fff;}
    .filter-checkbox-list{display:flex;flex-direction:column;gap:8px;}
    .filter-checkbox-item{display:flex;align-items:center;gap:9px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:0.88rem;color:#555;transition:color 0.15s;}
    .filter-checkbox-item:hover{color:#D9A299;}
    .filter-checkbox-item input[type="checkbox"]{width:16px;height:16px;accent-color:#D9A299;cursor:pointer;flex-shrink:0;}
    .filter-checkbox-count{margin-left:auto;font-size:0.75rem;color:#bbb;background:#f8f5f2;padding:1px 7px;border-radius:10px;}
    .rating-filter-list{display:flex;flex-direction:column;gap:8px;}
    .rating-filter-item{display:flex;align-items:center;gap:8px;cursor:pointer;padding:5px 8px;border-radius:8px;transition:background 0.15s;font-family:'DM Sans',sans-serif;font-size:0.85rem;}
    .rating-filter-item:hover,.rating-filter-item.active{background:#fdf0ee;}
    .rating-filter-item input[type="radio"]{accent-color:#D9A299;}
    .rating-stars{color:#f5a623;font-size:0.8rem;letter-spacing:1px;}
    .toggle-wrap{display:flex;align-items:center;justify-content:space-between;font-family:'DM Sans',sans-serif;font-size:0.88rem;color:#555;}
    .toggle-switch{position:relative;width:40px;height:22px;}
    .toggle-switch input{opacity:0;width:0;height:0;}
    .toggle-slider{position:absolute;inset:0;background:#e0d5d0;border-radius:22px;cursor:pointer;transition:background 0.2s;}
    .toggle-slider:before{content:'';position:absolute;width:16px;height:16px;left:3px;top:3px;background:#fff;border-radius:50%;transition:transform 0.2s;box-shadow:0 1px 4px rgba(0,0,0,0.15);}
    .toggle-switch input:checked + .toggle-slider{background:#D9A299;}
    .toggle-switch input:checked + .toggle-slider:before{transform:translateX(18px);}
    .filter-apply-btn{width:100%;padding:11px;background:linear-gradient(135deg,#D9A299,#c48a80);color:#fff;border:none;border-radius:12px;font-family:'DM Sans',sans-serif;font-size:0.9rem;font-weight:600;cursor:pointer;margin-top:18px;transition:all 0.25s;letter-spacing:0.03em;}
    .filter-apply-btn:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(217,162,153,0.45);}
    .shop-main{flex:1;min-width:0;}
    .shop-toolbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;flex-wrap:wrap;gap:10px;}
    .result-count{font-family:'DM Sans',sans-serif;font-size:0.88rem;color:#888;}
    .result-count strong{color:#D9A299;font-size:1rem;}
    .active-filter-pills{display:flex;flex-wrap:wrap;gap:6px;}
    .active-filter-pill{background:#fdf0ee;border:1px solid #f0dbd7;color:#c47a6e;font-size:0.78rem;padding:4px 10px;border-radius:20px;font-family:'DM Sans',sans-serif;display:flex;align-items:center;gap:5px;}
    .active-filter-pill .remove-pill{cursor:pointer;font-size:0.9rem;opacity:0.7;transition:opacity 0.15s;}
    .active-filter-pill .remove-pill:hover{opacity:1;}
    .view-toggle{display:flex;gap:6px;}
    .view-btn{width:34px;height:34px;border:1.5px solid #e8ddd8;background:#fff;border-radius:8px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#aaa;transition:all 0.2s;font-size:0.85rem;}
    .view-btn.active{border-color:#D9A299;color:#D9A299;background:#fdf0ee;}
    .grid-loading-overlay{display:none;position:absolute;inset:0;background:rgba(255,255,255,0.75);border-radius:12px;z-index:10;align-items:center;justify-content:center;}
    .grid-loading-overlay.show{display:flex;}
    .grid-wrap{position:relative;}
    .loading-spinner{width:40px;height:40px;border:3px solid #f0dbd7;border-top-color:#D9A299;border-radius:50%;animation:spin 0.7s linear infinite;}
    @keyframes spin{to{transform:rotate(360deg);}}
    .mobile-filter-btn{display:none;align-items:center;gap:8px;background:#fff;border:1.5px solid #e8ddd8;color:#555;padding:9px 18px;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:0.88rem;cursor:pointer;transition:all 0.2s;box-shadow:0 2px 8px rgba(0,0,0,0.06);}
    .mobile-filter-btn:hover{border-color:#D9A299;color:#D9A299;}
    .filter-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:1000;backdrop-filter:blur(2px);}
    .filter-overlay.show{display:block;}
    .filter-drawer{position:fixed;left:0;top:0;bottom:0;width:300px;background:#fff;z-index:1001;padding:24px 20px;overflow-y:auto;transform:translateX(-100%);transition:transform 0.3s cubic-bezier(0.4,0,0.2,1);box-shadow:4px 0 24px rgba(0,0,0,0.1);}
    .filter-drawer.open{transform:translateX(0);}
    .drawer-close{position:absolute;top:16px;right:16px;background:none;border:none;font-size:1.4rem;cursor:pointer;color:#999;line-height:1;padding:4px;transition:color 0.15s;}
    .drawer-close:hover{color:#D9A299;}
    .no-results{text-align:center;padding:60px 20px;}
    .no-results .emoji{font-size:3.5rem;margin-bottom:16px;}
    .no-results h3{font-family:'Playfair Display',serif;color:#444;margin-bottom:8px;}
    .no-results p{color:#999;font-family:'DM Sans',sans-serif;}
    @media(max-width:900px){
      .filter-sidebar{display:none;}
      .shop-layout{flex-direction:column;padding:0 14px 40px;}
      .mobile-filter-btn{display:flex;}
      .shop-toolbar{margin-top:10px;}
    }
    .story-item{cursor:pointer;}
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

<!-- ══════════════════════════════════════════════
     HERO SLIDER — teeno images ke saath
     Images apne project folder mein rakho:
       image1.png  (beauty/makeup)
       image2.png  (skincare)
       image3.png  (fashion store)
       image4.png  (dreamy boutique)
       image5.png  (bags & jewellery)
══════════════════════════════════════════════ -->
<div class="sv-hero-wrap" id="svHeroWrap">

  <!-- Slide counter -->
  <div class="sv-count-tag">
    <span class="sv-count-cur" id="svCountCur">01</span><span> / 05</span>
  </div>

  <!-- Animated progress bar -->
  <div class="sv-progress" id="svProgress"></div>

  <!-- Floating petals container -->
  <div class="sv-petals" id="svPetals"></div>

  <div class="sv-slides" id="svSlides">

    <!-- SLIDE 1 — Makeup / Beauty -->
    <div class="sv-slide active" data-slide="0">
      <div class="sv-slide-bg" style="background-image:url('image1.png');"></div>
      <div class="sv-slide-content">
        <div class="sv-eyebrow">✦ New Arrivals</div>
        <h1 class="sv-slide-title">Beauty That<br><em>Speaks For You</em></h1>
        <p class="sv-slide-sub">Explore our curated makeup collection — from bold lips to flawless skin, find your glow.</p>
        <div class="sv-slide-actions">
          <a href="makeup.php" class="sv-btn-main">Shop Makeup <i class="fas fa-arrow-right"></i></a>
          <a href="#main-products" class="sv-btn-ghost"><i class="fas fa-th-large"></i> All Products</a>
        </div>
      </div>
    </div>

    <!-- SLIDE 2 — Skincare -->
    <div class="sv-slide" data-slide="1">
      <div class="sv-slide-bg" style="background-image:url('image2.png');background-position:center;"></div>
      <div class="sv-slide-content">
        <div class="sv-eyebrow">✦ Self-Care First</div>
        <h1 class="sv-slide-title">Your Skin<br><em>Deserves This</em></h1>
        <p class="sv-slide-sub">Serums, moisturisers & more — clean beauty for your everyday ritual.</p>
        <div class="sv-slide-actions">
          <a href="skincare.php" class="sv-btn-main">Shop Skincare <i class="fas fa-arrow-right"></i></a>
          <a href="#main-products" class="sv-btn-ghost"><i class="fas fa-th-large"></i> All Products</a>
        </div>
      </div>
    </div>

    <!-- SLIDE 3 — Fashion Store -->
    <div class="sv-slide" data-slide="2">
      <div class="sv-slide-bg" style="background-image:url('image3.png');"></div>
      <div class="sv-slide-content">
        <div class="sv-eyebrow">✦ New Season</div>
        <h1 class="sv-slide-title">Dress Your<br><em>Best Every Day</em></h1>
        <p class="sv-slide-sub">From casual chic to evening glam — clothes that tell your story.</p>
        <div class="sv-slide-actions">
          <a href="clothes.php" class="sv-btn-main">Shop Fashion <i class="fas fa-arrow-right"></i></a>
          <a href="#main-products" class="sv-btn-ghost"><i class="fas fa-th-large"></i> All Products</a>
        </div>
      </div>
    </div>

    <!-- SLIDE 4 — Dreamy Boutique -->
    <div class="sv-slide" data-slide="3">
      <div class="sv-slide-bg" style="background-image:url('image4.png');background-position:center top;"></div>
      <div class="sv-slide-content">
        <div class="sv-eyebrow">✦ Premium Picks</div>
        <h1 class="sv-slide-title">Fashion Is<br><em>An Art Form</em></h1>
        <p class="sv-slide-sub">Step into a world of colour, elegance, and effortless style.</p>
        <div class="sv-slide-actions">
          <a href="clothes.php" class="sv-btn-main">Explore Now <i class="fas fa-arrow-right"></i></a>
          <a href="#main-products" class="sv-btn-ghost"><i class="fas fa-th-large"></i> All Products</a>
        </div>
      </div>
    </div>

    <!-- SLIDE 5 — Bags & Jewellery -->
    <div class="sv-slide" data-slide="4">
      <div class="sv-slide-bg" style="background-image:url('image5.png');background-position:center;"></div>
      <div class="sv-slide-content">
        <div class="sv-eyebrow">✦ Trending Now</div>
        <h1 class="sv-slide-title">Style Is In<br><em>The Details</em></h1>
        <p class="sv-slide-sub">Bags, jewellery & accessories — the finishing touches that complete you.</p>
        <div class="sv-slide-actions">
          <a href="jewellery.php" class="sv-btn-main">Shop Jewellery <i class="fas fa-arrow-right"></i></a>
          <a href="#main-products" class="sv-btn-ghost"><i class="fas fa-th-large"></i> All Products</a>
        </div>
      </div>
    </div>

  </div><!-- /sv-slides -->

  <!-- Stats bar — desktop only -->
  <div class="sv-stats-bar">
    <div class="sv-stat">
      <span class="sv-stat-num"><?php echo $total_products; ?>+</span>
      <span class="sv-stat-lbl">Products</span>
    </div>
    <div class="sv-stat">
      <span class="sv-stat-num"><?php echo count($db_categories); ?></span>
      <span class="sv-stat-lbl">Categories</span>
    </div>
    <div class="sv-stat">
      <span class="sv-stat-num"><?php echo $featured_count; ?></span>
      <span class="sv-stat-lbl">Featured</span>
    </div>
    <div class="sv-stat">
      <span class="sv-stat-num">★ 4.8</span>
      <span class="sv-stat-lbl">Avg Rating</span>
    </div>
  </div>

  <!-- Arrow buttons -->
  <button class="sv-nav sv-nav-prev" onclick="svGo(-1)" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
  <button class="sv-nav sv-nav-next" onclick="svGo(1)"  aria-label="Next"><i class="fas fa-chevron-right"></i></button>

  <!-- Dot indicators -->
  <div class="sv-dots" id="svDots">
    <button class="sv-dot active" onclick="svGoTo(0)"></button>
    <button class="sv-dot"        onclick="svGoTo(1)"></button>
    <button class="sv-dot"        onclick="svGoTo(2)"></button>
    <button class="sv-dot"        onclick="svGoTo(3)"></button>
    <button class="sv-dot"        onclick="svGoTo(4)"></button>
  </div>

</div><!-- /sv-hero-wrap -->

<script>
(function(){
  var total     = 5;
  var current   = 0;
  var autoDelay = 5000;
  var progStart = null;
  var animId    = null;
  var paused    = false;

  var slidesEl  = document.getElementById('svSlides');
  var dotsEl    = document.getElementById('svDots');
  var progEl    = document.getElementById('svProgress');
  var countEl   = document.getElementById('svCountCur');
  var wrap      = document.getElementById('svHeroWrap');

  function goTo(idx){
    var slides = slidesEl.querySelectorAll('.sv-slide');
    var dots   = dotsEl.querySelectorAll('.sv-dot');
    slides[current].classList.remove('active');
    dots[current].classList.remove('active');
    current = (idx + total) % total;
    slides[current].classList.add('active');
    dots[current].classList.add('active');
    slidesEl.style.transform = 'translateX(-' + (current * 20) + '%)';
    countEl.textContent = (current + 1 < 10 ? '0' : '') + (current + 1);
    resetProgress();
  }

  window.svGo   = function(d){ goTo(current + d); };
  window.svGoTo = function(i){ goTo(i); };

  function resetProgress(){
    progStart = null;
    if(animId) cancelAnimationFrame(animId);
    progEl.style.width = '0%';
    if(!paused) runProgress();
  }

  function runProgress(){
    animId = requestAnimationFrame(function tick(ts){
      if(!progStart) progStart = ts;
      var pct = Math.min(((ts - progStart) / autoDelay) * 100, 100);
      progEl.style.width = pct + '%';
      if(pct < 100){ animId = requestAnimationFrame(tick); }
      else { goTo(current + 1); }
    });
  }

  wrap.addEventListener('mouseenter', function(){ paused=true; if(animId) cancelAnimationFrame(animId); });
  wrap.addEventListener('mouseleave', function(){ paused=false; progStart=null; runProgress(); });

  var tx = 0;
  wrap.addEventListener('touchstart', function(e){ tx = e.touches[0].clientX; }, {passive:true});
  wrap.addEventListener('touchend',   function(e){
    var dx = e.changedTouches[0].clientX - tx;
    if(Math.abs(dx) > 50) goTo(current + (dx < 0 ? 1 : -1));
  });

  document.addEventListener('keydown', function(e){
    if(e.key==='ArrowLeft')  goTo(current-1);
    if(e.key==='ArrowRight') goTo(current+1);
  });

  /* petals */
  var colors = ['#f9c0b0','#f7d4cc','#e8b4c4','#fde0de','#f5e0fa','#ffc9c9'];
  var pc = document.getElementById('svPetals');
  for(var i=0;i<20;i++){
    var p = document.createElement('div');
    p.className = 'sv-petal';
    p.style.cssText = 'left:'+Math.random()*100+'%;top:'+(Math.random()*-60)+'px;'
      +'background:'+colors[Math.floor(Math.random()*colors.length)]+';'
      +'width:'+(6+Math.random()*8)+'px;height:'+(6+Math.random()*8)+'px;'
      +'animation-duration:'+(9+Math.random()*10)+'s;'
      +'animation-delay:'+(Math.random()*12)+'s;';
    pc.appendChild(p);
  }

  runProgress();
})();
</script>

<?php endif; ?>

<!-- ══ CATEGORY STORIES ══ -->
<?php if (!$searchQuery && !$categoryFilter): ?>
<div class="story-section">
  <h2>Shop by <span>Category</span></h2>
  <div class="stories-row">
    <div class="story-item active-story" onclick="window.location.href='viewall.php'" data-cat="">
      <div class="story-ring"><div class="story-icon">🛍️</div></div>
      <span>All</span>
    </div>
    <?php foreach ($db_categories as $cat): $m = getCatMeta($cat); ?>
    <div class="story-item" onclick="window.location.href='<?php echo htmlspecialchars($m['page']); ?>'" data-cat="<?php echo htmlspecialchars($cat); ?>">
      <div class="story-ring"><div class="story-icon"><?php echo $m['icon']; ?></div></div>
      <span><?php echo $m['label']; ?></span>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- ══ AI RECOMMENDATIONS ══ -->
<div id="sv-recommendations"></div>

<main id="main-products">
  <div style="max-width:1400px;margin:0 auto;padding:0 20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding-bottom:10px;">
      <button class="mobile-filter-btn" onclick="openFilterDrawer()">
        <i class="fas fa-sliders-h"></i> Filters &amp; Sort
        <span id="filter-badge" style="display:none;background:#D9A299;color:#fff;border-radius:50%;width:18px;height:18px;font-size:0.7rem;display:inline-flex;align-items:center;justify-content:center;margin-left:2px;">0</span>
      </button>
    </div>
  </div>

  <div class="shop-layout">
    <aside class="filter-sidebar" id="filterSidebar">
      <?php include_once 'filter_panel.php'; ?>
      <?php echo renderFilterPanel($db_brands, $min_price, $max_price, $db_categories, $categoryFilter); ?>
    </aside>

    <div class="shop-main">
      <div class="shop-toolbar">
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
          <div class="result-count">Showing <strong id="result-count">…</strong> products</div>
          <div class="active-filter-pills" id="active-pills"></div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
          <div class="view-toggle">
            <button class="view-btn active" id="btn-grid" onclick="setView('grid')" title="Grid view"><i class="fas fa-th"></i></button>
            <button class="view-btn" id="btn-list" onclick="setView('list')" title="List view"><i class="fas fa-list"></i></button>
          </div>
        </div>
      </div>

      <div class="grid-wrap">
        <div class="grid-loading-overlay" id="gridLoader"><div class="loading-spinner"></div></div>
        <div class="product-grid" id="product-grid">
          <?php
          if ($searchQuery != "") {
              $sq = '%' . $searchQuery . '%';
              $init_res = mysqli_query($cn, "SELECT * FROM shop WHERE productname LIKE '$sq' OR brand_name LIKE '$sq' OR category LIKE '$sq'");
          } elseif ($categoryFilter) {
              $init_res = mysqli_query($cn, "SELECT * FROM shop WHERE category='$categoryFilter' ORDER BY is_featured DESC, pid DESC");
          } else {
              $init_res = mysqli_query($cn, "SELECT * FROM shop ORDER BY is_featured DESC, pid DESC");
          }
          if ($init_res && mysqli_num_rows($init_res) > 0) {
              while ($row = mysqli_fetch_array($init_res)) { renderCard($cn, $row, $current_user_email); }
          } else {
              echo '<div class="no-results"><div class="emoji">🔍</div><h3>No products found</h3><p>Try adjusting your filters.</p></div>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- ══ MOBILE FILTER DRAWER ══ -->
<div class="filter-overlay" id="filterOverlay" onclick="closeFilterDrawer()"></div>
<div class="filter-drawer" id="filterDrawer">
  <button class="drawer-close" onclick="closeFilterDrawer()">✕</button>
  <div style="margin-top:20px;">
    <?php echo renderFilterPanel($db_brands, $min_price, $max_price, $db_categories, $categoryFilter); ?>
  </div>
</div>

<div id="toast"></div>

<?php
function renderCard($cn, $row, $current_user_email) {
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
    echo '<button class="wish-btn ' . $wish_class . '" data-pid="' . (int)$pid . '" onclick="toggleWish(' . (int)$pid . ')" title="' . ($wishlisted ? 'Remove from Wishlist' : 'Add to Wishlist') . '"><i class="' . $wish_icon . '"></i></button>';
    echo '<div class="product-image-container">';
    if ($is_out) echo '<div class="out-of-stock-overlay">OUT OF STOCK</div>';
    echo '<a href="order.php?pid=' . $pid . '"><img src="../admin-page/' . htmlspecialchars($row['productphoto']) . '" alt="' . htmlspecialchars($row['productname']) . '" loading="lazy" onerror="this.src=\'https://via.placeholder.com/300x300/faf7f4/D9A299?text=No+Image\'"></a>';
    if ($avg_r > 0) echo '<div class="rating-pill"><i class="fas fa-star"></i> ' . $avg_r . ' <span style="color:#bbb;">(' . $tot_r . ')</span></div>';
    echo '</div>';
    if ($is_low) {
        echo '<div class="stock-bar-wrap"><div class="stock-bar-label">🔥 Selling fast — ' . $stock . ' left</div><div class="stock-bar"><div class="stock-bar-fill" style="width:' . $bar_pct . '%"></div></div></div>';
    }
    echo '<div class="product-info">';
    echo '<div class="brand-name-text">' . htmlspecialchars($row['brand_name'] ?? 'Stylevana') . '</div>';
    echo '<div class="product-name-desc">' . htmlspecialchars($row['productname']) . '</div>';
    echo '<div class="price-row"><span class="current-price">₹' . number_format($sell) . '</span>';
    if ($disc > 0) echo '<span class="original-price">₹' . number_format($mrp) . '</span><span class="disc-pill">' . $disc . '% OFF</span>';
    echo '</div>';
    echo '<div class="delivery-tag ' . ($is_free_del ? 'free-del' : '') . '"><i class="fas fa-truck"></i> ' . htmlspecialchars($del_label) . '</div>';
    echo '<div class="card-btns">';
    if (!$is_out) {
        echo '<button class="btn-cart" onclick="addToCartByAjax(' . $pid . ')"><i class="fas fa-cart-plus"></i> Cart</button><a href="order.php?pid=' . $pid . '" class="btn-buy">Buy Now</a>';
    } else {
        echo '<button class="btn-unavail" disabled>Currently Unavailable</button>';
    }
    echo '</div></div></div>';
}

function renderFilterPanel($db_brands, $min_price, $max_price, $db_categories, $categoryFilter) {
    ob_start();
    ?>
    <div class="filter-header">
      <h3><i class="fas fa-sliders-h" style="color:#D9A299;margin-right:7px;"></i>Filters</h3>
      <button class="filter-clear-btn" onclick="clearAllFilters()">Clear All</button>
    </div>
    <div class="filter-section">
      <div class="filter-section-title" onclick="toggleSection(this)">Sort By <i class="fas fa-chevron-down"></i></div>
      <div class="filter-body" style="max-height:200px;">
        <select class="sort-select" id="sort-select" onchange="loadFiltered()">
          <option value="default">Default (Featured First)</option>
          <option value="price_asc">Price: Low to High</option>
          <option value="price_desc">Price: High to Low</option>
          <option value="newest">Newest Arrivals</option>
          <option value="rating">Highest Rated</option>
          <option value="discount">Biggest Discount</option>
        </select>
      </div>
    </div>
    <?php if (!$categoryFilter): ?>
    <div class="filter-section">
      <div class="filter-section-title" onclick="toggleSection(this)">Category <i class="fas fa-chevron-down"></i></div>
      <div class="filter-body" style="max-height:200px;">
        <div class="filter-checkbox-list">
          <?php foreach ($db_categories as $cat): $m = getCatMeta($cat); ?>
          <label class="filter-checkbox-item">
            <input type="checkbox" class="filter-cat" name="cat_filter" value="<?php echo htmlspecialchars($cat); ?>" onchange="loadFiltered()">
            <?php echo $m['icon']; ?> <?php echo $m['label']; ?>
          </label>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
    <div class="filter-section">
      <div class="filter-section-title" onclick="toggleSection(this)">Price Range <i class="fas fa-chevron-down"></i></div>
      <div class="filter-body" style="max-height:200px;">
        <div class="price-range-wrap">
          <div class="price-range-inputs">
            <input type="number" id="price-min" placeholder="₹ Min" value="<?php echo $min_price; ?>" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" onchange="syncSlider();loadFiltered()">
            <input type="number" id="price-max" placeholder="₹ Max" value="<?php echo $max_price; ?>" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" onchange="syncSlider();loadFiltered()">
          </div>
          <input type="range" class="price-range-slider" id="price-slider" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" value="<?php echo $max_price; ?>" oninput="document.getElementById('price-max').value=this.value;loadFiltered();">
        </div>
      </div>
    </div>
    <div class="filter-section">
      <div class="filter-section-title" onclick="toggleSection(this)">Customer Rating <i class="fas fa-chevron-down"></i></div>
      <div class="filter-body" style="max-height:200px;">
        <div class="rating-filter-list">
          <label class="rating-filter-item"><input type="radio" name="rating_filter" value="" onchange="loadFiltered()" checked> All Ratings</label>
          <label class="rating-filter-item"><input type="radio" name="rating_filter" value="4" onchange="loadFiltered()"><span class="rating-stars">★★★★☆</span> 4+ Stars</label>
          <label class="rating-filter-item"><input type="radio" name="rating_filter" value="3" onchange="loadFiltered()"><span class="rating-stars">★★★☆☆</span> 3+ Stars</label>
          <label class="rating-filter-item"><input type="radio" name="rating_filter" value="2" onchange="loadFiltered()"><span class="rating-stars">★★☆☆☆</span> 2+ Stars</label>
        </div>
      </div>
    </div>
    <?php if (!empty($db_brands)): ?>
    <div class="filter-section">
      <div class="filter-section-title" onclick="toggleSection(this)">Brand <i class="fas fa-chevron-down"></i></div>
      <div class="filter-body" style="max-height:180px;overflow-y:auto;">
        <div class="filter-checkbox-list">
          <?php foreach ($db_brands as $brand): ?>
          <label class="filter-checkbox-item">
            <input type="checkbox" class="filter-brand" name="brand_filter" value="<?php echo htmlspecialchars($brand); ?>" onchange="loadFiltered()">
            <?php echo htmlspecialchars($brand); ?>
          </label>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
    <div class="filter-section">
      <div class="filter-section-title" onclick="toggleSection(this)">Availability <i class="fas fa-chevron-down"></i></div>
      <div class="filter-body" style="max-height:100px;">
        <div style="display:flex;flex-direction:column;gap:10px;">
          <div class="toggle-wrap">In Stock Only<label class="toggle-switch"><input type="checkbox" id="instock-toggle" onchange="loadFiltered()"><span class="toggle-slider"></span></label></div>
          <div class="toggle-wrap">Featured Only<label class="toggle-switch"><input type="checkbox" id="featured-toggle" onchange="loadFiltered()"><span class="toggle-slider"></span></label></div>
          <div class="toggle-wrap">On Sale Only<label class="toggle-switch"><input type="checkbox" id="sale-toggle" onchange="loadFiltered()"><span class="toggle-slider"></span></label></div>
        </div>
      </div>
    </div>
    <button class="filter-apply-btn" onclick="loadFiltered()"><i class="fas fa-check-circle"></i> Apply Filters</button>
    <?php
    return ob_get_clean();
}
?>

<script>
var filterTimeout = null;
var currentView = 'grid';
var initialCategory = '<?php echo htmlspecialchars($categoryFilter); ?>';
var initialSearch   = '<?php echo htmlspecialchars($searchQuery); ?>';

function loadFiltered(delay){
    delay = delay||300;
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(_doFilter, delay);
}
function _doFilter(){
    var params = collectFilterParams();
    var qs = buildQueryString(params);
    document.getElementById('gridLoader').classList.add('show');
    fetch('filter_products.php?'+qs)
        .then(function(r){ if(!r.ok) throw new Error('err'); return r.text(); })
        .then(function(html){
            document.getElementById('product-grid').innerHTML = html;
            document.getElementById('gridLoader').classList.remove('show');
            updateResultCount(); updateActivePills(params); updateFilterBadge(params);
            if(currentView==='list') applyListView();
        })
        .catch(function(){ document.getElementById('gridLoader').classList.remove('show'); });
}
function collectFilterParams(){
    var p={};
    var sortEl=document.getElementById('sort-select'); if(sortEl) p.sort=sortEl.value;
    var catChecks=document.querySelectorAll('.filter-cat:checked'); var cats=[];
    catChecks.forEach(function(c){cats.push(c.value);}); if(cats.length>0) p.categories=cats.join(','); else if(initialCategory) p.categories=initialCategory;
    var pMin=document.getElementById('price-min'); var pMax=document.getElementById('price-max');
    if(pMin) p.price_min=pMin.value; if(pMax) p.price_max=pMax.value;
    var ratingEl=document.querySelector('input[name="rating_filter"]:checked'); if(ratingEl&&ratingEl.value) p.rating=ratingEl.value;
    var brandChecks=document.querySelectorAll('.filter-brand:checked'); var brands=[];
    brandChecks.forEach(function(b){brands.push(b.value);}); if(brands.length>0) p.brands=brands.join('||');
    var instockEl=document.getElementById('instock-toggle'); if(instockEl&&instockEl.checked) p.instock='1';
    var featEl=document.getElementById('featured-toggle'); if(featEl&&featEl.checked) p.featured='1';
    var saleEl=document.getElementById('sale-toggle'); if(saleEl&&saleEl.checked) p.sale='1';
    if(initialSearch) p.search=initialSearch;
    return p;
}
function buildQueryString(params){
    return Object.keys(params).map(function(k){ return encodeURIComponent(k)+'='+encodeURIComponent(params[k]); }).join('&');
}
function updateResultCount(){
    var cards=document.querySelectorAll('#product-grid .product-card');
    var el=document.getElementById('result-count'); if(el) el.textContent=cards.length;
}
function updateActivePills(params){
    var container=document.getElementById('active-pills'); if(!container) return;
    container.innerHTML=''; var pills=[];
    if(params.sort&&params.sort!=='default'){
        var sortLabels={price_asc:'Price ↑',price_desc:'Price ↓',newest:'Newest',rating:'Top Rated',discount:'Sale'};
        pills.push({label:sortLabels[params.sort]||params.sort,key:'sort'});
    }
    if(params.rating) pills.push({label:params.rating+'+ Stars',key:'rating'});
    if(params.instock) pills.push({label:'In Stock',key:'instock'});
    if(params.featured) pills.push({label:'Featured',key:'featured'});
    if(params.sale) pills.push({label:'On Sale',key:'sale'});
    if(params.brands) params.brands.split('||').forEach(function(b){pills.push({label:b,key:'brand_'+b});});
    pills.forEach(function(pill){
        var el=document.createElement('div'); el.className='active-filter-pill';
        el.innerHTML=pill.label+'<span class="remove-pill" onclick="removePill(\''+pill.key+'\')">✕</span>';
        container.appendChild(el);
    });
}
function updateFilterBadge(params){
    var count=0;
    if(params.sort&&params.sort!=='default') count++;
    if(params.rating) count++;
    if(params.instock) count++;
    if(params.featured) count++;
    if(params.sale) count++;
    if(params.categories&&!initialCategory) count++;
    if(params.brands) count+=params.brands.split('||').length;
    var badge=document.getElementById('filter-badge');
    if(badge){ badge.style.display=count>0?'inline-flex':'none'; badge.textContent=count; }
}
function removePill(key){
    if(key==='sort'){var s=document.getElementById('sort-select');if(s)s.value='default';}
    else if(key==='rating'){var r=document.querySelector('input[name="rating_filter"][value=""]');if(r)r.checked=true;}
    else if(key==='instock'){var el=document.getElementById('instock-toggle');if(el)el.checked=false;}
    else if(key==='featured'){var el=document.getElementById('featured-toggle');if(el)el.checked=false;}
    else if(key==='sale'){var el=document.getElementById('sale-toggle');if(el)el.checked=false;}
    else if(key.startsWith('brand_')){var bname=key.replace('brand_','');document.querySelectorAll('.filter-brand').forEach(function(cb){if(cb.value===bname)cb.checked=false;});}
    loadFiltered(0);
}
function clearAllFilters(){
    var s=document.getElementById('sort-select');if(s)s.value='default';
    document.querySelectorAll('.filter-cat').forEach(function(c){c.checked=false;});
    var pMin=document.getElementById('price-min'),pMax=document.getElementById('price-max'),slider=document.getElementById('price-slider');
    if(pMin)pMin.value=pMin.min;if(pMax)pMax.value=pMax.max;if(slider)slider.value=slider.max;
    var r=document.querySelector('input[name="rating_filter"][value=""]');if(r)r.checked=true;
    document.querySelectorAll('.filter-brand').forEach(function(b){b.checked=false;});
    ['instock-toggle','featured-toggle','sale-toggle'].forEach(function(id){var el=document.getElementById(id);if(el)el.checked=false;});
    loadFiltered(0);
}
function syncSlider(){
    var slider=document.getElementById('price-slider'),pMax=document.getElementById('price-max');
    if(slider&&pMax){slider.value=pMax.value;var pct=((pMax.value-slider.min)/(slider.max-slider.min))*100;slider.style.setProperty('--val',pct+'%');}
}
function toggleSection(titleEl){
    var body=titleEl.nextElementSibling,isOpen=!body.classList.contains('collapsed');
    body.classList.toggle('collapsed',isOpen); titleEl.classList.toggle('collapsed',isOpen);
    body.style.maxHeight=isOpen?'0':body.scrollHeight+'px';
}
function setView(v){
    currentView=v;
    document.getElementById('btn-grid').classList.toggle('active',v==='grid');
    document.getElementById('btn-list').classList.toggle('active',v==='list');
    var grid=document.getElementById('product-grid');if(grid)grid.classList.toggle('list-view',v==='list');
}
function applyListView(){var grid=document.getElementById('product-grid');if(grid&&currentView==='list')grid.classList.add('list-view');}
function openFilterDrawer(){document.getElementById('filterDrawer').classList.add('open');document.getElementById('filterOverlay').classList.add('show');document.body.style.overflow='hidden';}
function closeFilterDrawer(){document.getElementById('filterDrawer').classList.remove('open');document.getElementById('filterOverlay').classList.remove('show');document.body.style.overflow='';}

window.addEventListener('DOMContentLoaded',function(){
    updateResultCount(); syncSlider();
    if(initialCategory){document.querySelectorAll('.filter-cat').forEach(function(c){if(c.value===initialCategory)c.checked=true;});}
});

function toggleWish(pid){
    var btn=document.querySelector('.wish-btn[data-pid="'+String(pid)+'"]');
    if(!btn)return;var icon=btn.querySelector('i');if(!icon)return;
    var isW=btn.classList.contains('wishlisted');
    btn.classList.toggle('wishlisted',!isW);icon.className=isW?'far fa-heart':'fas fa-heart';
    btn.title=isW?'Add to Wishlist':'Remove from Wishlist';
    if(!isW){icon.style.transform='scale(1.5)';setTimeout(function(){icon.style.transform='scale(1)';},300);}
    fetch('wishlist_toggle.php?pid='+encodeURIComponent(pid))
        .then(function(res){if(!res.ok)throw new Error('err');return res.json();})
        .then(function(d){
            btn.classList.toggle('wishlisted',d.wishlisted);icon.className=d.wishlisted?'fas fa-heart':'far fa-heart';
            btn.title=d.wishlisted?'Remove from Wishlist':'Add to Wishlist';
            showToast(d.wishlisted?'💖 Added to Wishlist!':'🤍 Removed from Wishlist');
        })
        .catch(function(){btn.classList.toggle('wishlisted',isW);icon.className=isW?'fas fa-heart':'far fa-heart';showToast('❌ Kuch error aaya, dobara try karo');});
}
function addToCartByAjax(pid){
    var xhr=new XMLHttpRequest();xhr.open("GET","addcart.php?pid="+pid,true);
    xhr.onreadystatechange=function(){if(xhr.readyState==4&&xhr.status==200){showToast('🛒 Added to Cart Successfully!');var badge=document.querySelector('.cart-badge');if(badge)badge.textContent=(parseInt(badge.textContent)||0)+1;}};
    xhr.send();
}
function showToast(msg,duration){
    duration=duration||2500;var t=document.getElementById('toast');
    t.textContent=msg;t.classList.add('show');setTimeout(function(){t.classList.remove('show');},duration);
}
(function(){
    var now=new Date(),end=new Date(now.getFullYear(),now.getMonth(),now.getDate()+1,0,0,0);
    var el=document.getElementById('flash-timer');if(!el)return;
    setInterval(function(){
        var diff=Math.max(0,end-new Date());
        var h=String(Math.floor(diff/3600000)).padStart(2,'0');
        var m=String(Math.floor((diff%3600000)/60000)).padStart(2,'0');
        var s=String(Math.floor((diff%60000)/1000)).padStart(2,'0');
        el.textContent=h+':'+m+':'+s;
    },1000);
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