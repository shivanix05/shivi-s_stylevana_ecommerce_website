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
        header("location:index.php");
        exit();
    }
?>
<?php
$categoryFilter = "";
if (isset($_GET['category']) && $_GET['category'] != "") {
    $categoryFilter = $_GET['category'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>after login- Shivi's Stylevana</title>
    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="afterl-style.css">
    
</head>
<body>

    <?php include("header.php"); ?>
    <?php
  $categories = [
    "jewellery" => "Jewellery is forever; love is a treasure.",
    "Makeup" => "Why waste Money, Spend it on Makeup.",
    "skincare" => "The best self care, is the Skincare!!",
    "clothes" => "Clothes describe your personality!!"
     ];
    ?>

    <?php

function displayCategory($cn, $category, $title) {
    $str = "SELECT * FROM shop WHERE category='" . mysqli_real_escape_string($cn, $category) . "'";
    $result = mysqli_query($cn, $str);

    if (mysqli_num_rows($result) > 0) {
        echo "<h2 class='section-title'>$title</h2>";
        echo '<div class="product-grid">';
        while ($row = mysqli_fetch_array($result)) {
?>
            <div class="product-card">
                <p class="product-id">Product ID: <?php echo $row['pid']; ?></p>
                <a href="order.php?pid=<?php echo $row['pid']; ?>">
        <img src="<?php echo $row['productphoto']; ?>" alt="Product Image" width="100">
    </a>
                <div class="product-info">
                    <h3 class="productname"><?php echo $row['productName']; ?></h3>
                    <p class="price"><?php echo $row['productprice']; ?></p>
                    <button class="order-button" onclick="addcart(this)">Add to Cart</button>
                </div>
            </div>
<?php
        }
        echo "</div>";
    }
}
?>
  
    
    <?php
function showOriginalBrands() {
?>
    <div class="original-brands-section">
        <div class="section-header">
            <h2>Original Brands <span class="verified-icon">✔</span></h2>
            <a href="viewall.php" class="view-all-link">VIEW ALL ></a>
        </div>
        <div class="card-list-container">
            <ul class="card-list">
                <li class="card">
                    <div class="card-image-wrapper">
                        <a href="skincare.php"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcTijPt6vdS-qeUguYMJSvSeaWdPVlYtve4w&s" alt="Nivea Body Milk" class="card-image"></a>
                    </div>
                    <div class="card-label">Personal Care</div>
                </li>
                <li class="card">
                    <div class="card-image-wrapper">
                        <a href="clothes.php"><img src="https://www.shutterstock.com/image-photo/fashionable-clothes-boutique-store-london-600nw-589577570.jpg" alt="Clothes" class="card-image"></a>
                    </div>
                    <div class="card-label">Essential clothes</div>
                </li>
                <li class="card">
                    <div class="card-image-wrapper">
                        <a href="makeup.php"><img src="https://cdn.britannica.com/35/222035-050-C68AD682/makeup-cosmetics.jpg" alt="Makeup" class="card-image"></a>
                    </div>
                    <div class="card-label">Makeup</div>
                </li>
                <li class="card">
                    <div class="card-image-wrapper">
                        <a href="jewllery.php"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRqeEhTWhCyXWBsN8P6mMNrTlS7NOkCgB8M7w&s" alt="Jewelry" class="card-image"></a>
                    </div>
                    <div class="card-label">Jewelry</div>
                </li>
                <li class="card">
                    <div class="card-image-wrapper">
                        <img src="https://example.com/images/denver.jpg" alt="Perfume" class="card-image">
                    </div>
                    <div class="card-label">Perfume</div>
                </li>
            </ul>
        </div>
    </div>
<?php
}
?>


 <main class="container main-content">
<?php

if (!$categoryFilter) {
    
    showOriginalBrands();
     foreach ($categories as $cat => $title) {
        displayCategory($cn, $cat, $title);
    }

} else {
  
    displayCategory($cn, $categoryFilter, ucfirst($categoryFilter));
    showOriginalBrands();
    foreach ($categories as $cat => $title) {
        if ($cat != $categoryFilter) {
            displayCategory($cn, $cat, $title);
        }
    }
}
?>
</main>
   
   <?php include ("footer.php")?>
   <?php include("cartscript.php")?>
  

</body>
</html>
