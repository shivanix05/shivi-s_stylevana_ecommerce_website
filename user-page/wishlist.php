<?php
require_once __DIR__ . "/config.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit();
}

$u_mail = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorites — Stylevana</title>
     <link rel="stylesheet" href="wishlist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

    <style>
        <?php
        // Generate stagger delays for each card via PHP
        $delay_css = '';
        for ($d = 1; $d <= 20; $d++) {
            $delay_css .= ".wish-card:nth-child({$d}) { animation-delay: " . ($d * 0.06) . "s; }\n";
        }
        echo $delay_css;
        ?>
    </style>
</head>
<body>

<?php include("header.php"); ?>

<div class="wishlist-page">

    <?php
    $sql = "SELECT shop.*, wishlist.wid FROM wishlist 
            JOIN shop ON wishlist.pid = shop.pid 
            WHERE wishlist.user_email = '$u_mail'
            ORDER BY wishlist.wid DESC";
    $res   = mysqli_query($cn, $sql);
    $count = mysqli_num_rows($res);
    ?>

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>My Favorites</h1>
            <p>Items you've saved for later</p>
        </div>
        <?php if ($count > 0): ?>
        <span class="count-pill">
            <i class="fas fa-heart"></i>
            <?php echo $count; ?> item<?php echo $count !== 1 ? 's' : ''; ?>
        </span>
        <?php endif; ?>
    </div>

    <?php if ($count > 0): ?>

    <div class="wishlist-grid">
        <?php while ($row = mysqli_fetch_assoc($res)):
            $imgPath = $row['productphoto'];
        ?>
        <div class="wish-card">

            <!-- Product image -->
            <div class="wish-img">
                <?php if (!empty($imgPath)): ?>
                    <img src="../admin-page/<?php echo htmlspecialchars($imgPath); ?>"
                         alt="<?php echo htmlspecialchars($row['productname']); ?>">
                <?php else: ?>
                    <div class="wish-img-placeholder">
                        <i class="fas fa-image"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Content -->
            <div class="wish-content">
                <div class="wish-meta">
                    <div class="wish-name"><?php echo htmlspecialchars($row['productname']); ?></div>
                    <div class="wish-price">₹<?php echo number_format($row['productprice']); ?></div>
                    <div class="wish-price-label">Inclusive of all taxes</div>
                </div>

                <div class="wish-actions">
                    <!-- Add to cart link (existing: addcart.php?pid=...) -->
                    <a href="addcart.php?pid=<?php echo $row['pid']; ?>" class="btn-add-cart">
                        <i class="fas fa-shopping-bag"></i> Add to bag
                    </a>

                    <!-- View / Order -->
                    <a href="order.php?pid=<?php echo $row['pid']; ?>" class="btn-view">
                        View <i class="fas fa-arrow-right"></i>
                    </a>

                    <!-- Remove -->
                    <a href="remove_wishlist.php?id=<?php echo $row['pid']; ?>"
                       class="btn-remove"
                       title="Remove from favorites"
                       onclick="return confirm('Remove from favorites?')">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>

        </div>
        <?php endwhile; ?>
    </div>

    <?php else: ?>
    <!-- Empty state -->
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-heart"></i></div>
        <h3>Nothing saved yet</h3>
        <p>Browse our collection and tap the heart to save your favorites here</p>
        <a href="shop.php" class="btn-shop">
            <i class="fas fa-store"></i> Explore Collection
        </a>
    </div>
    <?php endif; ?>

</div>

<?php include("footer.php"); ?>
</body>
</html>