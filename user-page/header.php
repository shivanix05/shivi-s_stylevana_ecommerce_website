<?php
// Session check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/config.php"; 

// Logout Logic - Wahi purana logic
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
    <title>Document</title>
    <link rel="stylesheet" href="afterl-style.css">
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    

<header class="main-header">
    <div class="container header-content">
        <div class="logo">
            <div><img src="logo.png" class="img-logo"></div>
            <a href="after-login.php">Shivi's<span>Stylevana</span></a>
        </div>

        <div class="header-center">
            <div class="search-container" style="position: relative;">
                <form action="after-login.php" method="GET" id="searchForm" style="display: flex; width: 100%;">
                    <input type="text" name="search" id="searchInput" autocomplete="off" 
                           placeholder="Search for products..." 
                           value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                <div id="suggestionBox" class="suggestion-dropdown"></div>
            </div>
        </div>

        <div class="header-right">
            <nav class="main-nav">
                <a href="after-login.php">Home</a>
                <a href="contact.php">Contact</a>
                
                <form method="get" action="after-login.php" class="category-form">
                    <select name="category" class="categories" onchange="this.form.submit()">
                        <option value="" <?php if(!isset($_GET['category']) || $_GET['category']=="") echo "selected"; ?>>All Products</option>
                        <option value="jewellery" <?php if(isset($_GET['category']) && $_GET['category']=="jewellery") echo "selected"; ?>>Jewellery</option>
                        <option value="skincare" <?php if(isset($_GET['category']) && $_GET['category']=="skincare") echo "selected"; ?>>Skincare</option>
                        <option value="Makeup" <?php if(isset($_GET['category']) && $_GET['category']=="Makeup") echo "selected"; ?>>Makeup</option>
                        <option value="clothes" <?php if(isset($_GET['category']) && $_GET['category']=="clothes") echo "selected"; ?>>Clothes</option>
                    </select>
                </form>
            </nav>

            <?php 
            if(isset($_SESSION['user'])){
                $u_mail = $_SESSION['user'];
                // Syncing with your table: userdetail (name, userphoto, gmail)
                $nav_res = mysqli_query($cn, "SELECT name, userphoto FROM userdetail WHERE gmail='$u_mail'");
                $nav_row = mysqli_fetch_assoc($nav_res);

                // Syncing with your table: cart (qty, user_email)
                $count_query = "SELECT SUM(qty) as total FROM cart WHERE user_email = '$u_mail'";
                $count_res = mysqli_query($cn, $count_query);
                $count_row = mysqli_fetch_array($count_res);
                $total_cart_items = $count_row['total'] ?? 0;
            ?>

            <div class="user-profile" style="display: flex; align-items: center; gap: 8px;">
                <a class="user-edit" href="my-profile.php" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <?php 
                    $user_photo = $nav_row['userphoto'];
                    $user_full_name = $nav_row['name'] ?? 'User';

                    // Photo display logic
                    if(!empty($user_photo) && file_exists("uploads/" . $user_photo)) {
                        echo '<img src="uploads/'.$user_photo.'" alt="User Profile" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd;">';
                    } else {
                        // Initials logic
                        $name_parts = explode(" ", trim($user_full_name));
                        $first_initial = strtoupper(substr($name_parts[0], 0, 1));
                        $last_initial = (count($name_parts) > 1) ? strtoupper(substr(end($name_parts), 0, 1)) : "";
                        $display_initials = $first_initial . $last_initial;

                        echo '<div style="width: 35px; height: 35px; border-radius: 50%; background: #DCC5B2; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px; border: 1px solid #fff;">'.$display_initials.'</div>';
                    }
                    ?>
                    <span style="font-size: 13px; font-weight: 600; color: #444;"><?php echo $user_full_name; ?></span>
                </a> 
            </div>

            <div class="header-icons">
                <a href="wishlist.php" title="Wishlist">
                    <button class="icon-btn"><i class="far fa-heart"></i></button>
                </a>
            </div>

            <div class="header-icons">
                <a href="myorder.php" title="My Orders">
                    <button class="icon-btn"><i class="fas fa-shopping-bag"></i></button>
                </a>
            </div>

            <div class="header-icons" style="position: relative;">
                <a href="addcart.php">
                    <button class="icon-btn">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <?php if($total_cart_items > 0): ?>
                            <span class="cart-badge" style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px;"><?php echo $total_cart_items; ?></span>
                        <?php endif; ?>
                    </button>
                </a>
            </div>

            <div class="header-icons">
                <form method="post"> 
                    <button class="icon-btn" name="logoutbtn"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
                </form>
            </div>
            <?php } ?>
        </div>
    </div>
</header>

<style>
/* Wahi original suggestion style */
.suggestion-dropdown {
    position: absolute; top: 100%; left: 0; width: 100%; background: white;
    border: 1px solid #ddd; border-top: none; z-index: 1000; display: none;
    max-height: 250px; overflow-y: auto; border-radius: 0 0 10px 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.suggestion-item { padding: 10px; cursor: pointer; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #f9f9f9; }
.suggestion-item:hover { background-color: #f0f0f0; }
.suggestion-item img { width: 30px; height: 30px; object-fit: cover; border-radius: 4px; }
</style>

<script>
// Wahi original search script
document.getElementById('searchInput').addEventListener('input', function() {
    let query = this.value;
    let box = document.getElementById('suggestionBox');
    if (query.length > 0) {
        let formData = new FormData();
        formData.append('query', query);
        fetch('fetch_suggestions.php', { method: 'POST', body: formData })
        .then(response => response.text())
        .then(data => { box.innerHTML = data; box.style.display = 'block'; })
        .catch(err => console.error("Search Error:", err));
    } else { box.style.display = 'none'; }
});
function selectSuggestion(name) {
    document.getElementById('searchInput').value = name;
    document.getElementById('suggestionBox').style.display = 'none';
    document.getElementById('searchForm').submit();
}
window.addEventListener('click', function(e) {
    if (!document.getElementById('searchForm').contains(e.target)) {
        document.getElementById('suggestionBox').style.display = 'none';
    }
});
</script>
</body>
</html>
