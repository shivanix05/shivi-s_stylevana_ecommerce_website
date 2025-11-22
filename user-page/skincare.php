<?php include("config.php");
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
        /* Basic Reset & Body Styling */
       

        /* Page Title */
        .page-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 25px;
            text-align: center;
            margin-top: 20px;
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Responsive grid */
            gap: 20px; /* Space between cards */
            justify-content: center;
        }

        /* Product Card Styling */
        .product-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease-in-out;
            border: 1px solid #e0e0e0;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .image-container {
            position: relative;
            width: 100%;
            padding-top: 100%; /* Makes the container square for image */
            overflow: hidden;
            border-bottom: 1px solid #eee;
        }

        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; /* Covers the area, might crop */
            display: block;
        }

        .more-images-tag {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .product-details {
            padding: 15px;
            display: flex;
            flex-direction: column;
            flex-grow: 1; /* Allows details section to take remaining space */
        }

        .brand-name {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .product-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .price-section {
            display: flex;
            align-items: baseline;
            margin-bottom: 10px;
            flex-wrap: wrap; /* Allows prices to wrap on smaller screens */
        }

        .current-price {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-right: 8px;
        }

        .original-price {
            font-size: 14px;
            color: #888;
            text-decoration: line-through;
            margin-right: 8px;
        }

        .discount-percentage {
            font-size: 14px;
            font-weight: bold;
            color: #28a745; /* Green for discount */
        }

        .delivery-info {
            font-size: 13px;
            color: #555;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .rating-section {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #555;
            margin-top: auto; /* Pushes rating to the bottom */
            padding-top: 10px;
            border-top: 1px solid #eee;
            margin-top: 15px; /* Space from above elements */
        }

        .star-icon {
            color: #28a745; /* Green star */
            margin-right: 5px;
            font-weight: bold;
        }

        .review-count {
            color: #777;
        }

        /* Mall Tag (if applicable) */
        .mall-tag {
            background-color: #9c27b0; /* Purple for Mall */
            color: #fff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            margin-left: auto; /* Pushes to the right */
            align-self: flex-start; /* Aligns to top if flex-direction is column */
            margin-top: -10px; /* Adjust to position correctly if needed */
            margin-right: -10px;
        }
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
                    <h3 class="product-name"><?php echo $row['productName'] ; ?></h3>
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