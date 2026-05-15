<?php
require_once __DIR__ . "/config.php";
// Fetch featured products for display (no login required)
$featured_res = mysqli_query($cn, "SELECT * FROM shop WHERE is_featured=1 ORDER BY pid DESC LIMIT 8");
$categories_res = mysqli_query($cn, "SELECT DISTINCT category FROM shop WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
$total_res = mysqli_query($cn, "SELECT COUNT(*) as c FROM shop");
$total_row = mysqli_fetch_assoc($total_res);
$total_products = $total_row['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shivi's Stylevana | Your Style, Your Story ✨</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,600&family=Josefin+Sans:wght@300;400;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="index-style.css">
  <style>
  
  </style>
</head>
<body>

<!-- Custom cursor -->
<div class="cursor-dot" id="cursorDot"></div>
<div class="cursor-ring" id="cursorRing"></div>

<!-- ── NAVBAR ── -->
<nav class="navbar" id="navbar">
  <a href="index.php" class="nav-logo">Shivi's <span>Stylevana</span></a>
  <div class="nav-links">
    <a href="#categories">Collections</a>
    <a href="#featured">Featured</a>
    <a href="about.php">About</a>
    <a href="login.php" class="nav-cta">Login / Sign Up</a>
  </div>
</nav>

<!-- ── HERO ── -->
<section class="hero">
  <div class="hero-bg">
    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="orb orb3"></div>
    <div class="hero-grid"></div>
  </div>
  <div class="hero-content">
    <div class="hero-eyebrow">New Season 2026</div>
    <h1 class="hero-title">
      Your Style,<br>
      <em>Your</em> <span class="outline-text">Story</span>
    </h1>
    <p class="hero-subtitle">Fashion · Skincare · Makeup · Jewellery</p>
    <div class="hero-buttons">
      <a href="login.php" class="btn-primary">
        <i class="fas fa-shopping-bag"></i> Shop Now
      </a>
      <a href="#featured" class="btn-outline">
        <i class="fas fa-arrow-down"></i> Explore
      </a>
    </div>
  </div>
  <div class="scroll-indicator">
    <div class="scroll-line"></div>
    <span>Scroll</span>
  </div>
</section>

<!-- ── MARQUEE ── -->
<div class="marquee-strip">
  <div class="marquee-track">
    <?php for($i=0;$i<4;$i++): ?>
    <span class="marquee-item"><span class="dot"></span> Free Shipping Above ₹499</span>
    <span class="marquee-item"><span class="dot"></span> 100% Authentic Products</span>
    <span class="marquee-item"><span class="dot"></span> Easy 7-Day Returns</span>
    <span class="marquee-item"><span class="dot"></span> New Arrivals Every Week</span>
    <span class="marquee-item"><span class="dot"></span> Exclusive Member Offers</span>
    <?php endfor; ?>
  </div>
</div>

<!-- ── STATS ── -->
<div class="stats-section reveal">
  <div class="stat-item">
    <div class="stat-num" data-target="<?= $total_products ?>">0<span>+</span></div>
    <div class="stat-label">Products</div>
  </div>
  <div class="stat-item">
    <?php $cat_count = mysqli_num_rows($categories_res); ?>
    <div class="stat-num" data-target="<?= $cat_count ?>">0<span>+</span></div>
    <div class="stat-label">Categories</div>
  </div>
  <div class="stat-item">
    <div class="stat-num" data-target="5000">0<span>+</span></div>
    <div class="stat-label">Happy Customers</div>
  </div>
  <div class="stat-item">
    <div class="stat-num" data-target="4">0<span>.9 ★</span></div>
    <div class="stat-label">Average Rating</div>
  </div>
</div>

<!-- ── CATEGORIES ── -->
<section class="section reveal" id="categories">
  <div class="section-head">
    <div class="section-eyebrow">Collections</div>
    <h2 class="section-title">Shop by <em>Category</em></h2>
  </div>
  <div class="cat-grid">
    <?php
    $cat_meta = [
      'jewellery' => ['icon'=>'💎','name'=>'Jewellery','tag'=>'Timeless Elegance','img'=>'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&q=80'],
      'makeup'    => ['icon'=>'💄','name'=>'Makeup','tag'=>'Define Your Beauty','img'=>'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=600&q=80'],
      'skincare'  => ['icon'=>'✨','name'=>'Skincare','tag'=>'Glow From Within','img'=>'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=600&q=80'],
      'clothes'   => ['icon'=>'👗','name'=>'Fashion','tag'=>'Express Yourself','img'=>'https://images.unsplash.com/photo-1445205170230-053b83016050?w=600&q=80'],
      'clothing'  => ['icon'=>'👗','name'=>'Fashion','tag'=>'Express Yourself','img'=>'https://images.unsplash.com/photo-1445205170230-053b83016050?w=600&q=80'],
    ];
    mysqli_data_seek($categories_res, 0);
    $shown_cats = [];
    while($cr = mysqli_fetch_assoc($categories_res)):
      $cat = $cr['category'];
      $key = strtolower(trim($cat));
      if(in_array($key, $shown_cats)) continue;
      $shown_cats[] = $key;
      $m = $cat_meta[$key] ?? ['icon'=>'🛍️','name'=>ucfirst($cat),'tag'=>'Explore Now','img'=>'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&q=80'];
    ?>
    <div class="cat-card" onclick="window.location='login.php'">
      <img src="<?= $m['img'] ?>" alt="<?= htmlspecialchars($m['name']) ?>"
           onload="this.closest('.cat-card').classList.add('loaded')"
           onerror="this.closest('.cat-card').classList.add('loaded')">
      <div class="cat-overlay">
        <div class="cat-icon"><?= $m['icon'] ?></div>
        <div class="cat-name"><?= $m['name'] ?></div>
        <div class="cat-tagline"><?= $m['tag'] ?></div>
        <a href="login.php" class="cat-btn">Explore <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</section>

<!-- ── FEATURED PRODUCTS ── -->
<section class="products-section reveal" id="featured">
  <div class="section-head">
    <div class="section-eyebrow">⭐ Handpicked For You</div>
    <h2 class="section-title"><em>Featured</em> Picks</h2>
  </div>
  <div class="prod-grid" id="prodGrid">
    <!-- Shimmer placeholders -->
    <?php for($i=0;$i<8;$i++): ?>
    <div class="shimmer-card" id="shimmer-<?=$i?>">
      <div class="shimmer-img"></div>
      <div class="shimmer-line"></div>
      <div class="shimmer-line short"></div>
    </div>
    <?php endfor; ?>
  </div>
</section>

<!-- ── BANNER ── -->
<section class="banner-section reveal">
  <div class="banner-bg"></div>
  <div class="banner-deco"></div>
  <div class="banner-content">
    <div class="banner-tag">✦ Limited Time Offer</div>
    <div class="banner-offer">UP TO 30% OFF</div>
    <h2 class="banner-title">Jewellery that tells your <em>story</em></h2>
    <p class="banner-sub">Handcrafted pieces, timeless designs. Discover our exclusive jewellery collection.</p>
    <a href="login.php" class="btn-primary"><i class="fas fa-gem"></i> Shop Jewellery</a>
  </div>
</section>

<!-- ── TESTIMONIALS ── -->
<section class="testimonials reveal">
  <div class="section-head">
    <div class="section-eyebrow">Love Notes</div>
    <h2 class="section-title">What our customers <em>say</em></h2>
  </div>
  <div class="test-grid">
    <div class="test-card">
      <div class="test-stars">★★★★★</div>
      <div class="test-quote">Absolutely love the quality! The jewellery pieces are stunning and the packaging was so beautiful. Will definitely shop again.</div>
      <div class="test-author">Priya Sharma</div>
      <div class="test-loc">Mumbai, India</div>
    </div>
    <div class="test-card">
      <div class="test-stars">★★★★★</div>
      <div class="test-quote">The skincare range is incredible. My skin has never felt better. Fast delivery and everything was exactly as described!</div>
      <div class="test-author">Ananya Singh</div>
      <div class="test-loc">Delhi, India</div>
    </div>
    <div class="test-card">
      <div class="test-stars">★★★★★</div>
      <div class="test-quote">Stylevana is my go-to for everything! Fashion, makeup, accessories — all in one place. Best online store hands down.</div>
      <div class="test-author">Riya Patel</div>
      <div class="test-loc">Bangalore, India</div>
    </div>
  </div>
</section>

<!-- ── NEWSLETTER ── -->
<section class="newsletter reveal">
  <h2>Join the <em>Stylevana</em> Family</h2>
  <p>Get exclusive offers, early access to new arrivals & style tips</p>
  <div class="newsletter-form">
    <input type="email" placeholder="Your email address...">
    <button onclick="alert('Thank you! We will keep you updated ✨')">Subscribe</button>
  </div>
</section>

<!-- ── FOOTER ── -->
<footer class="footer">
  <div class="footer-grid">
    <div class="footer-brand">
      <div class="logo-text">Shivi's <span>Stylevana</span></div>
      <p>Your all-in-one destination for fashion, skincare, makeup, and jewellery. Curated with love for every woman.</p>
      <div class="footer-socials">
        <a href="#" class="footer-social"><i class="fab fa-instagram"></i></a>
        <a href="#" class="footer-social"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="footer-social"><i class="fab fa-pinterest-p"></i></a>
        <a href="#" class="footer-social"><i class="fab fa-twitter"></i></a>
      </div>
    </div>
    <div class="footer-col">
      <h4>Shop</h4>
      <ul>
        <li><a href="login.php">All Products</a></li>
        <li><a href="login.php">New Arrivals</a></li>
        <li><a href="login.php">Featured Picks</a></li>
        <li><a href="login.php">Sale</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Categories</h4>
      <ul>
        <li><a href="login.php">Fashion</a></li>
        <li><a href="login.php">Skincare</a></li>
        <li><a href="login.php">Makeup</a></li>
        <li><a href="login.php">Jewellery</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Help</h4>
      <ul>
        <li><a href="login.php">My Orders</a></li>
        <li><a href="#">Returns Policy</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="about.php">About Us</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    &copy; 2026 Shivi's Stylevana. Made with ♥ All Rights Reserved.
  </div>
</footer>

<script>
// ── Custom Cursor ──
var dot = document.getElementById('cursorDot');
var ring = document.getElementById('cursorRing');
document.addEventListener('mousemove', function(e) {
  dot.style.left = e.clientX + 'px';
  dot.style.top  = e.clientY + 'px';
  ring.style.left = e.clientX + 'px';
  ring.style.top  = e.clientY + 'px';
});
document.querySelectorAll('a,button').forEach(function(el) {
  el.addEventListener('mouseenter', function() {
    ring.style.transform = 'translate(-50%,-50%) scale(1.8)';
    ring.style.borderColor = '#D9A299';
  });
  el.addEventListener('mouseleave', function() {
    ring.style.transform = 'translate(-50%,-50%) scale(1)';
    ring.style.borderColor = '#D9A299';
  });
});

// ── Navbar scroll effect ──
window.addEventListener('scroll', function() {
  var nav = document.getElementById('navbar');
  if (window.scrollY > 60) nav.classList.add('scrolled');
  else nav.classList.remove('scrolled');
});

// ── Reveal on scroll ──
var revealEls = document.querySelectorAll('.reveal');
var observer = new IntersectionObserver(function(entries) {
  entries.forEach(function(entry) {
    if (entry.isIntersecting) { entry.target.classList.add('visible'); }
  });
}, { threshold: 0.1 });
revealEls.forEach(function(el) { observer.observe(el); });

// ── Counter animation ──
function animateCounter(el, target, suffix) {
  var start = 0, duration = 1800;
  var startTime = null;
  function step(timestamp) {
    if (!startTime) startTime = timestamp;
    var progress = Math.min((timestamp - startTime) / duration, 1);
    var ease = 1 - Math.pow(1 - progress, 3);
    var current = Math.floor(ease * target);
    el.innerHTML = current.toLocaleString() + suffix;
    if (progress < 1) requestAnimationFrame(step);
  }
  requestAnimationFrame(step);
}
var counterObserver = new IntersectionObserver(function(entries) {
  entries.forEach(function(entry) {
    if (entry.isIntersecting) {
      var el = entry.target;
      var target = parseInt(el.getAttribute('data-target'));
      var suffix = el.querySelector('span') ? el.querySelector('span').outerHTML : '';
      animateCounter(el, target, suffix);
      counterObserver.unobserve(el);
    }
  });
}, { threshold: 0.5 });
document.querySelectorAll('.stat-num[data-target]').forEach(function(el) { counterObserver.observe(el); });

// ── Load featured products (replace shimmer cards) ──
(function() {
  var products = <?php
    $prods = [];
    if($featured_res && mysqli_num_rows($featured_res) > 0) {
      mysqli_data_seek($featured_res, 0);
      while($r = mysqli_fetch_assoc($featured_res)) {
        $prods[] = [
          'pid'   => $r['pid'],
          'name'  => $r['productname'],
          'brand' => $r['brand_name'] ?? 'Stylevana',
          'price' => $r['productprice'],
          'photo' => $r['productphoto'],
          'cat'   => $r['category']
        ];
      }
    }
    echo json_encode($prods);
  ?>;

  var grid = document.getElementById('prodGrid');
  if (!grid || products.length === 0) return;

  // Remove shimmer cards and render real ones
  setTimeout(function() {
    grid.innerHTML = '';
    products.forEach(function(p, i) {
      var card = document.createElement('div');
      card.className = 'prod-card';
      card.style.animationDelay = (i * 0.1) + 's';
      card.innerHTML = `
        <div class="prod-img-wrap">
          <img src="${p.photo}" alt="${p.name}"
               onerror="this.src='https://via.placeholder.com/300x300/faf6f3/D9A299?text=No+Image'">
          <div class="prod-overlay">
            <a href="login.php"><i class="fas fa-lock"></i>&nbsp; Login to Buy</a>
          </div>
          <div class="prod-badge">⭐ Featured</div>
        </div>
        <div class="prod-info">
          <div class="prod-brand">${p.brand}</div>
          <div class="prod-name">${p.name}</div>
          <div class="prod-price">₹${parseInt(p.price).toLocaleString()}</div>
          <div class="prod-price-login">Login to add to cart or wishlist</div>
          <a href="login.php" class="prod-lock-btn"><i class="fas fa-shopping-bag"></i> Shop Now</a>
        </div>
      `;
      grid.appendChild(card);
    });
  }, 800);
})();
</script>
</body>
</html>