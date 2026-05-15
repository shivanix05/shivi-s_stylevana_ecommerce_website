<?php require_once __DIR__ . "/config.php";
session_start();
if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();
}
require_once __DIR__ . "/includes/helpers.php"; // isWishlisted function ke liye
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
    <style> .page-title { font-size: 28px; font-weight: bold; color: #333; text-align: center; margin: 20px 0 25px; } </style>
</head>
<body>
    <?php include("header.php")?>
    <main>
    <div class="page-container">
        <h1 class="page-title">Top Makeup Picks for You!</h1>
        <div class="product-grid">
             <?php 
                $str = "select * from shop where category='clothes'" ;
                $result = mysqli_query($cn,$str);
                require_once __DIR__ . "/includes/render_product_card.php";
                while ( $row = mysqli_fetch_array($result))
                {
                    renderCard($cn, $row, $_SESSION['user']);
            ?>
          <?php } ?>
        </div>
        
    </div>
   </main>
    <?php include("footer.php")?>
</body>
</html>
