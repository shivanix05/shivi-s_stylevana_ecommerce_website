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
    <title>My Wishlist | Stylevana ✨</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">

    <style>
        :root { --bubble-pink: #ff85a2; --wishlist-red: #ff4d6d; }
        body { background: #fdfafb; font-family: 'Quicksand', sans-serif; margin: 0; overflow-x: hidden; }
        
        header { position: sticky; top: 0; z-index: 1000; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }

        .wishlist-container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .wishlist-title { text-align: center; font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 50px; }

        /* Vertical Arrangement */
        .wishlist-grid { 
            display: grid; 
            grid-template-columns: 1fr; 
            gap: 40px; 
        }

        /* Slant Card Design */
        .wishlist-card { 
            display: flex; /* Image aur text side-by-side karne ke liye */
            align-items: center;
            background: #fff; 
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
            position: relative; 
            transition: 0.4s; 
            border: 1px solid #eee;
            transform: skewX(-2deg); /* Stylish Slant Effect */
            margin-left: 20px;
        }
        
        .wishlist-card:hover { 
            transform: skewX(0deg) scale(1.02); 
            box-shadow: 10px 15px 40px rgba(255, 133, 162, 0.15);
        }

        /* Image Box Fix */
        .img-box { 
            width: 250px; 
            height: 200px; 
            background: #f9f9f9; 
            overflow: hidden; 
            flex-shrink: 0;
            transform: skewX(2deg); /* Image ko wapas sidha karne ke liye */
        }
        .img-box img { width: 100%; height: 100%; object-fit: cover; }

        .remove-btn { 
            position: absolute; top: 15px; right: 15px; background: #fff; 
            color: var(--wishlist-red); width: 35px; height: 35px; 
            border-radius: 50%; display: flex; align-items: center; 
            justify-content: center; text-decoration: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1); z-index: 10;
        }

        .card-info { 
            padding: 30px; 
            text-align: left; 
            flex-grow: 1; 
            transform: skewX(2deg); /* Text ko sidha karne ke liye */
        }
        .card-info h3 { font-size: 22px; margin: 0 0 10px; color: #333; font-weight: 700; }
        .price { color: var(--bubble-pink); font-weight: 700; font-size: 24px; margin-bottom: 15px; }
        
        .view-btn { 
            display: inline-block; background: #282c3f; color: #fff; 
            text-decoration: none; padding: 12px 30px; border-radius: 12px; 
            font-size: 14px; font-weight: 700; transition: 0.3s;
        }
        .view-btn:hover { background: var(--bubble-pink); }

        @media (max-width: 600px) {
            .wishlist-card { flex-direction: column; transform: none; }
            .img-box { width: 100%; height: 250px; transform: none; }
            .card-info { transform: none; text-align: center; }
        }
    </style>
</head>
<body>

<?php include("header.php"); ?>

<div class="wishlist-container">
    <h1 class="wishlist-title">My Favorites 🖤</h1>
    <div class="wishlist-grid">
        <?php
        $sql = "SELECT shop.*, wishlist.wid FROM wishlist 
                JOIN shop ON wishlist.pid = shop.pid 
                WHERE wishlist.user_email = '$u_mail'";
        
        $res = mysqli_query($cn, $sql);

        while($row = mysqli_fetch_assoc($res)) {
            // Path direct database se uthaya
            $imgPath = $row['productphoto']; 
        ?>
            <div class="wishlist-card">
                <a href="remove_wishlist.php?id=<?php echo $row['pid']; ?>" class="remove-btn">
                    <i class="fas fa-times"></i>
                </a>
                
                <div class="img-box">
                    <img src="<?php echo $imgPath; ?>" alt="Product">
                </div>

                <div class="card-info">
                    <h3><?php echo htmlspecialchars($row['productname']); ?></h3>
                    <div class="price">₹<?php echo $row['productprice']; ?></div>
                    <a href="order.php?pid=<?php echo $row['pid']; ?>" class="view-btn">VIEW PRODUCT</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>
