<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/config.php"; 

if (isset($_POST["logoutbtn"])){
    session_destroy();
    header("location:index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shivi's Stylevana</title>
    <link rel="stylesheet" href="afterl-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=DM+Sans:wght@300;400;500;600&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<script src="recommendation_widget.js"></script>
<body>

<style>
/* ══════════════════════════════════════════════
   STYLEVANA PREMIUM HEADER
   Luxury fashion-house aesthetic
══════════════════════════════════════════════ */

/* Top announcement bar */
.header-announcement {
    background: #1a1a2e;
    color: rgba(255,255,255,0.75);
    text-align: center;
    font-size: 11px;
    letter-spacing: 1.5px;
    padding: 7px 20px;
    font-weight: 500;
    position: relative;
    overflow: hidden;
}
.header-announcement::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(90deg, transparent, rgba(217,162,153,0.08), transparent);
    animation: shimmer 3s infinite;
}
@keyframes shimmer { 0%{transform:translateX(-100%)} 100%{transform:translateX(100%)} }
.announcement-inner {
    display: flex; align-items: center; justify-content: center; gap: 20px; flex-wrap: wrap;
}
.announcement-inner .sep { color: rgba(217,162,153,0.4); }
.ann-highlight { color: #D9A299; font-weight: 700; }

/* Main header */
.main-header {
    background: #fff;
    border-bottom: 1px solid #f0e8e4;
    position: sticky; top: 0; z-index: 999;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
}

/* Logo row */
.header-logo-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 40px;
    border-bottom: 1px solid #faf5f3;
}

/* Logo */
.logo-group {
    display: flex; align-items: center; gap: 10px; text-decoration: none;
    flex-shrink: 0;
}
.logo-group img.img-logo {
    height: 36px; width: auto; object-fit: contain;
}
.logo-wordmark {
    font-family: 'Playfair Display', serif;
    font-size: 20px; font-weight: 700;
    color: #282c3f; letter-spacing: -0.3px;
    line-height: 1;
}
.logo-wordmark span { color: #D9A299; }
.logo-sub {
    font-family: 'Cormorant Garamond', serif;
    font-size: 9px; letter-spacing: 3px;
    color: #bba; text-transform: uppercase;
    margin-top: 2px; display: block;
}

/* Search */
.header-search-wrap {
    flex: 1; max-width: 480px; margin: 0 32px;
    position: relative;
}
.header-search-form {
    display: flex; align-items: center;
    background: #faf7f4; border: 1.5px solid #f0e8e4;
    border-radius: 50px; overflow: hidden;
    transition: border-color .2s, box-shadow .2s;
}
.header-search-form:focus-within {
    border-color: #D9A299;
    box-shadow: 0 0 0 4px rgba(217,162,153,.1);
}
.header-search-form input {
    flex: 1; border: none; background: transparent; outline: none;
    padding: 11px 18px; font-size: 13px; font-family: 'DM Sans', sans-serif;
    color: #282c3f;
}
.header-search-form input::placeholder { color: #bbb; }
.header-search-btn {
    padding: 11px 18px; background: none; border: none;
    color: #D9A299; cursor: pointer; font-size: 14px;
    transition: color .2s;
}
.header-search-btn:hover { color: #c48b81; }
.suggestion-dropdown {
    position: absolute; top: calc(100% + 6px); left: 0; right: 0;
    background: white; border: 1.5px solid #f0e8e4;
    border-radius: 16px; z-index: 1000; display: none;
    max-height: 280px; overflow-y: auto;
    box-shadow: 0 8px 32px rgba(0,0,0,.1);
    padding: 6px 0;
}
.suggestion-item {
    padding: 10px 16px; cursor: pointer;
    display: flex; align-items: center; gap: 12px;
    font-size: 13px; color: #282c3f;
    transition: background .15s;
    border-bottom: 1px solid #faf5f3;
}
.suggestion-item:last-child { border-bottom: none; }
.suggestion-item:hover { background: #faf7f4; }
.suggestion-item img { width: 36px; height: 36px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
.sugg-name { font-weight: 600; font-size: 13px; }
.sugg-cat { font-size: 10px; color: #D9A299; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }

/* Right icons group */
.header-actions {
    display: flex; align-items: center; gap: 4px; flex-shrink: 0;
}

/* User profile chip */
.user-chip {
    display: flex; align-items: center; gap: 8px;
    text-decoration: none; padding: 6px 14px 6px 6px;
    border-radius: 50px; border: 1.5px solid #f0e8e4;
    background: #faf7f4; transition: all .2s;
    margin-right: 4px;
}
.user-chip:hover { border-color: #D9A299; background: #fff; }
.user-chip .u-avatar {
    width: 30px; height: 30px; border-radius: 50%; object-fit: cover;
    border: 2px solid #D9A299; flex-shrink: 0;
}
.user-chip .u-initials {
    width: 30px; height: 30px; border-radius: 50%;
    background: linear-gradient(135deg, #D9A299, #c48b81);
    color: white; display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 11px; flex-shrink: 0;
}
.user-chip .u-name {
    font-size: 12px; font-weight: 600; color: #282c3f;
    max-width: 90px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}

/* Icon buttons */
.hdr-icon-btn {
    width: 40px; height: 40px; border-radius: 50%;
    background: transparent; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #555; font-size: 16px; position: relative;
    transition: background .2s, color .2s, transform .2s;
    text-decoration: none;
}
.hdr-icon-btn:hover {
    background: #faf7f4; color: #D9A299;
    transform: translateY(-1px);
}

/* Tooltip on icons */
.hdr-icon-btn .tooltip {
    position: absolute; bottom: -28px; left: 50%; transform: translateX(-50%);
    background: #282c3f; color: white;
    font-size: 10px; font-weight: 600; white-space: nowrap;
    padding: 3px 8px; border-radius: 6px; letter-spacing: .5px;
    opacity: 0; pointer-events: none; transition: opacity .15s;
}
.hdr-icon-btn:hover .tooltip { opacity: 1; }

/* Cart badge */
.cart-badge {
    position: absolute; top: 4px; right: 4px;
    background: #D9A299; color: white;
    border-radius: 50%; width: 17px; height: 17px;
    font-size: 9px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid white;
    animation: badgePop .3s ease;
}
@keyframes badgePop { 0%{transform:scale(0)} 60%{transform:scale(1.2)} 100%{transform:scale(1)} }

/* Logout btn */
.hdr-logout {
    width: 36px; height: 36px; border-radius: 50%;
    background: #fdecea; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #e74c3c; font-size: 14px; position: relative;
    transition: background .2s, transform .2s;
}
.hdr-logout:hover { background: #e74c3c; color: white; transform: translateY(-1px); }

/* Nav row */
.header-nav-row {
    display: flex; align-items: center; justify-content: center;
    padding: 0 40px;
    gap: 0;
    background: white;
}
.nav-link {
    padding: 12px 18px; font-size: 12px; font-weight: 600;
    color: #555; text-decoration: none; letter-spacing: .5px;
    text-transform: uppercase; position: relative;
    transition: color .2s;
    white-space: nowrap;
}
.nav-link::after {
    content: ''; position: absolute; bottom: 0; left: 18px; right: 18px;
    height: 2px; background: #D9A299; border-radius: 2px;
    transform: scaleX(0); transition: transform .2s;
}
.nav-link:hover { color: #282c3f; }
.nav-link:hover::after { transform: scaleX(1); }
.nav-link.active { color: #D9A299; }
.nav-link.active::after { transform: scaleX(1); }

/* Category select — styled inline */
.nav-cat-form { display: inline-flex; }
.nav-cat-select {
    appearance: none; -webkit-appearance: none;
    background: transparent; border: none; outline: none;
    padding: 12px 18px 12px 18px; font-size: 12px; font-weight: 600;
    color: #555; letter-spacing: .5px; text-transform: uppercase;
    cursor: pointer; font-family: 'DM Sans', sans-serif;
    transition: color .2s;
}
.nav-cat-select:hover { color: #282c3f; }
.nav-cat-select option { text-transform: none; font-size: 13px; }

/* Nav divider */
.nav-divider {
    width: 1px; height: 16px; background: #f0e8e4; flex-shrink: 0;
}

/* Mobile */
@media (max-width: 768px) {
    .header-announcement { font-size: 10px; letter-spacing: .8px; }
    .header-logo-row { padding: 12px 16px; }
    .header-search-wrap { max-width: none; margin: 0 12px; }
    .header-nav-row { display: none; }
    .u-name { display: none; }
    .logo-sub { display: none; }
}
</style>

<!-- ══ ANNOUNCEMENT BAR ══ -->
<div class="header-announcement">
    <div class="announcement-inner">
        <span>✦ <span class="ann-highlight">Free Shipping</span> on orders above ₹499</span>
        <span class="sep">|</span>
        <span>New arrivals every week</span>
        <span class="sep">|</span>
        <span>✦ Easy <span class="ann-highlight">7-Day Returns</span></span>
    </div>
</div>

<!-- ══ MAIN HEADER ══ -->
<header class="main-header">

    <!-- Logo + Search + Actions -->
    <div class="header-logo-row">

        <!-- Logo -->
        <a href="after-login.php" class="logo-group">
            <img src="logo.png" class="img-logo" alt="Stylevana Logo"
                 onerror="this.style.display='none'">
            <div>
                <div class="logo-wordmark">Shivi's <span>Stylevana</span></div>
                <span class="logo-sub">Luxury · Style · You</span>
            </div>
        </a>

        <!-- Search -->
        <div class="header-search-wrap">
            <form action="after-login.php" method="GET" id="searchForm" class="header-search-form">
                <input type="text" name="search" id="searchInput"
                       autocomplete="off"
                       placeholder="Search products, brands, categories…"
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="header-search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <div id="suggestionBox" class="suggestion-dropdown"></div>
        </div>

        <!-- Right Actions -->
        <div class="header-actions">
            <?php if(isset($_SESSION['user'])): ?>
            <?php
            $u_mail  = $_SESSION['user'];
            $nav_res = mysqli_query($cn, "SELECT name, userphoto FROM userdetail WHERE gmail='$u_mail'");
            $nav_row = mysqli_fetch_assoc($nav_res);
            $user_full_name = $nav_row['name'] ?? 'User';
            $user_photo     = $nav_row['userphoto'] ?? '';

            $count_res   = mysqli_query($cn, "SELECT SUM(qty) as total FROM cart WHERE user_email = '$u_mail'");
            $count_row   = mysqli_fetch_array($count_res);
            $total_cart  = (int)($count_row['total'] ?? 0);
            ?>

            <!-- User chip -->
            <a href="my-profile.php" class="user-chip">
                <?php
                if(!empty($user_photo) && file_exists("uploads/" . $user_photo)){
                    echo '<img src="uploads/'.$user_photo.'" alt="Profile" class="u-avatar">';
                } else {
                    $parts   = explode(" ", trim($user_full_name));
                    $initial = strtoupper(substr($parts[0], 0, 1));
                    $initial2= (count($parts) > 1) ? strtoupper(substr(end($parts), 0, 1)) : '';
                    echo '<div class="u-initials">' . $initial . $initial2 . '</div>';
                }
                ?>
                <span class="u-name"><?php echo htmlspecialchars($user_full_name); ?></span>
            </a>

            <!-- Wishlist -->
            <a href="wishlist.php" class="hdr-icon-btn" title="Wishlist">
                <i class="far fa-heart"></i>
                <span class="tooltip">Wishlist</span>
            </a>

            <!-- My Orders -->
            <a href="myorder.php" class="hdr-icon-btn" title="My Orders">
                <i class="fas fa-shopping-bag"></i>
                <span class="tooltip">My Orders</span>
            </a>

            <!-- Cart -->
            <a href="addcart.php" class="hdr-icon-btn" title="Cart">
                <i class="fa-solid fa-cart-shopping"></i>
                <?php if($total_cart > 0): ?>
                    <span class="cart-badge"><?php echo $total_cart; ?></span>
                <?php endif; ?>
                <span class="tooltip">Cart</span>
            </a>

            <!-- Logout -->
            <form method="post" style="margin:0;">
                <button class="hdr-logout" name="logoutbtn" title="Sign Out">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>

            <?php endif; ?>
        </div>

    </div><!-- /header-logo-row -->

    <!-- Navigation row -->
    <nav class="header-nav-row">
        <?php
        $current_page = basename($_SERVER['PHP_SELF']);
        $current_cat  = $_GET['category'] ?? '';
        ?>
        <a href="after-login.php"
           class="nav-link <?php echo ($current_page === 'after-login.php' && !$current_cat) ? 'active' : ''; ?>">
            Home
        </a>
        <div class="nav-divider"></div>

        <!-- Category nav links -->
        <?php
        $nav_cats = [
            'jewellery' => '💎 Jewellery',
            'makeup'    => '💄 Makeup',
            'skincare'  => '✨ Skincare',
            'clothes'   => '👗 Fashion',
        ];
        foreach($nav_cats as $cval => $clabel):
            $is_active = (strtolower($current_cat) === strtolower($cval));
        ?>
        <a href="after-login.php?category=<?php echo urlencode($cval); ?>"
           class="nav-link <?php echo $is_active ? 'active' : ''; ?>">
            <?php echo $clabel; ?>
        </a>
        <div class="nav-divider"></div>
        <?php endforeach; ?>

        <a href="contact.php"
           class="nav-link <?php echo $current_page === 'contact.php' ? 'active' : ''; ?>">
            Contact
        </a>
    </nav>

</header>

<script>
// ══ Live search suggestions (same logic as original) ══
var searchInput = document.getElementById('searchInput');
var suggBox     = document.getElementById('suggestionBox');
var searchForm  = document.getElementById('searchForm');

if(searchInput) {
    searchInput.addEventListener('input', function() {
        var query = this.value.trim();
        if (query.length > 0) {
            var formData = new FormData();
            formData.append('query', query);
            fetch('fetch_suggestions.php', { method: 'POST', body: formData })
                .then(function(r){ return r.text(); })
                .then(function(data){
                    suggBox.innerHTML = data;
                    suggBox.style.display = data.trim() ? 'block' : 'none';
                })
                .catch(function(){ suggBox.style.display = 'none'; });
        } else {
            suggBox.style.display = 'none';
        }
    });
}

function selectSuggestion(name) {
    if(searchInput) searchInput.value = name;
    if(suggBox)     suggBox.style.display = 'none';
    if(searchForm)  searchForm.submit();
}

window.addEventListener('click', function(e) {
    if (searchForm && !searchForm.contains(e.target)) {
        if(suggBox) suggBox.style.display = 'none';
    }
});
</script>
</body>
</html>