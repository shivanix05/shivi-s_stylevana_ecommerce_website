<?php require_once __DIR__ . "/config.php";
session_start();
if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();
}
?>
<?php
    if (isset($_POST["logoutbtn"])){
        session_destroy();
        header("location:login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trending Clothes & Apparel</title>
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="afterl-style.css">
    <link rel="stylesheet" href="category.css">

    <style>
        .page-title { font-size: 28px; font-weight: bold; color: #333; text-align: center; margin: 20px 0 25px; }
        
        /* Action Buttons Layout */
        .action-buttons { 
            display: flex; 
            gap: 10px; 
            margin-top: 15px; 
        }
        .btn-cart, .btn-buy { 
            flex: 1; 
            padding: 10px; 
            border: none; 
            border-radius: 4px; 
            font-size: 14px; 
            font-weight: bold; 
            cursor: pointer; 
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        .btn-cart { 
            background-color: #ff9f00; 
            color: #fff; 
        }
        .btn-buy { 
            background-color: #fb641b; 
            color: #fff; 
        }
        .btn-cart:hover { background-color: #f39700; }
        .btn-buy:hover { background-color: #e85d18; }
        
        .image-container a { display: block; }
        .product-image { cursor: pointer; transition: transform 0.2s; }
        .product-image:hover { transform: scale(1.02); }
        .no-data { text-align: center; font-size: 18px; color: #777; padding: 40px; width: 100%; }
    </style>
</head>
<body>
    <?php include("header.php")?>
    <main>
    <div class="page-container">
        <h1 class="page-title">Top Clothing Picks for You!</h1>

        <div class="product-grid">
             <?php 
                // Yahan query badal kar LIKE lagaya hai taaki spelling mismatch na ho
                $str = "select * from shop where category LIKE '%cloth%'" ;
                $result = mysqli_query($cn,$str);
                
                if (mysqli_num_rows($result) > 0) {
                    while ( $row = mysqli_fetch_array($result))
                    {
                        $product_id = $row['pid']; 
                ?>
               
                <div class="product-card">
                    <!-- Photo click link to order.php -->
                    <div class="image-container">
                        <a href="order.php?id=<?php echo $product_id; ?>">
                            <img src="../admin-page/<?php echo $row['productphoto']; ?>" alt="<?php echo $row['productname']; ?>" width="100" class="product-image" >
                        </a>
                         <span class="more-images-tag">+2 More</span>
                    </div>
                    
                    <div class="product-details">
                        <span class="brand-name">Fashion Wear</span>
                        <h3 class="product-name"><?php echo $row['productname'] ; ?></h3>
                        <div class="price-section">
                            <span class="current-price">₹<?php echo $row['productprice'];?></span>
                            <span class="original-price">₹1,999</span>
                            <span class="discount-percentage">55% off</span>
                        </div>
                        <p class="delivery-info">Free Delivery</p>
                        <div class="rating-section">
                            <span class="star-icon">★ 4.4</span>
                            <span class="review-count">1,850 Reviews</span>
                            <span class="mall-tag">Mall</span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <!-- Add to Cart Form -->
                            <form action="cart.php" method="POST" style="flex: 1; display: inline;">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <input type="hidden" name="product_name" value="<?php echo $row['productname']; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $row['productprice']; ?>">
                                <input type="hidden" name="product_photo" value="<?php echo $row['productphoto']; ?>">
                                <button type="submit" name="add_to_cart" class="btn-cart">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </form>
                            
                            <!-- Buy Now Button -->
                            <a href="order.php?id=<?php echo $product_id; ?>" class="btn-buy">
                                <i class="fas fa-bolt"></i> Buy Now
                            </a>
                        </div>
                    </div>
                </div>
              <?php 
                    }
                } else {
                    echo "<div class='no-data'><i class='fas fa-shopping-bag'></i><br><br>No products found matching 'cloth' in database table.</div>";
                }
              ?>
          
        </div>
    </div>
    </main>
    <?php include("footer.php")?>
</body>
</html>