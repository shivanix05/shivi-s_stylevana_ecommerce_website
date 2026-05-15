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
    <title>Makeup Products</title>
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="afterl-style.css">
   
    <style>
       
    </style>
</head>
<body>
    <?php include("header.php")?>
    <main>
    <div class="page-container">
        <h1 class="page-title">Top Makeup Picks for You!</h1>

        <div class="product-grid">
             <?php 
                $str = "select * from shop where category='skincare'" ;
                $result = mysqli_query($cn,$str);
                while ( $row = mysqli_fetch_array($result))
                {
            ?>
           
            <div class="product-card">
                <div class="image-container">
                    <img src="<?php echo $row['productphoto']; ?>" alt="Lipstick Set" width="100" class="product-image" >
                     <span class="more-images-tag">+2 More</span>
                </div>
                <div class="product-details">
                    <span class="brand-name">Dot&Key</span>
                    <h3 class="product-name"><?php echo $row['productname'] ; ?></h3>
                    <div class="price-section">
                        <span class="current-price"><?php echo $row['productprice'];?></span>
                        <span class="original-price">₹1,999</span>
                        <span class="discount-percentage">55% off</span>
                    </div>
                    <p class="delivery-info">Free Delivery</p>
                    <div class="rating-section">
                        <span class="star-icon">★ 4.2</span>
                        <span class="review-count">2,104 Reviews</span>
                        <span class="mall-tag">Mall</span>
                    </div>
                </div>
            </div>
          <?php } ?>
          
        </div>
    </div>
   </main>
    <?php include("footer.php")?>
</body>
</html>
