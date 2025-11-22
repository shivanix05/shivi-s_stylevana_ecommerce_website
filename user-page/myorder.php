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
    <title>after login- Shivi's Stylevana</title>
    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="afterl-style.css">
    <style>
        h1{
            text-align: center;
            margin-top: 20px;
        }
     
    </style>
</head>
<body>
    <?php include("header.php") ?>
    <h1>My Orders</h1>
    <main> 
        <?php
            $str="select * from orders where gmail= '$_POST[gmail]'";
            $rs=  mysqli_query($cn,$str);
            while ($row = mysqli_fetch_array($rs)) {
 

         ?>
        <div class="main-container">
             <div class="order-place-container">
                <h2><?php echo $row['product_id']; ?></h2>
                 <P><strong>customer name </strong><?php echo $row['customer_name'] ?></P>
                 <p><strong>Price:</strong> ₹<?php echo $row['payment_method']; ?></p>
               
             </div>
        </div>
        <?php } ?>
    </main>
    <?php include("footer.php") ?>
</body>
</html>