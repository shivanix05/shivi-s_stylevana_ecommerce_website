<?php
include("function.php");
session_start();
if(!isset($_SESSION["admin"])) { header("location:adminlogin.php"); exit(); }
$cn = make_connection();

if(isset($_POST["btn_save"])) {
    // Basic Details
    $pname =  $_POST["pname"];
    $brand =  $_POST["brand"];
    $cat =  $_POST["pcat"];
    $desc =  $_POST["pdesc"];
    
    // Pricing & Inventory
    $price = $_POST["pprice"];
    $old_price = $_POST["old_price"];
    $offer =  $_POST["offer"];
    $stock = $_POST["pstock"]; 
    $delivery = $_POST["delivery"];

    // Photo Upload Logic
    $fn = $_FILES["pimage"]["name"];
    $tmp = $_FILES["pimage"]["tmp_name"];
    $path = "product_images/" . time() . "_" . $fn; 
    
    if(move_uploaded_file($tmp, $path)) {
        // Purani query (shipping_charge ke bina)
        $q = "INSERT INTO shop (productname, brand_name, productphoto, productdescription, category, productprice, original_price, offer_text, delivery_type, stock_qty) 
              VALUES ('$pname', '$brand', '$path', '$desc', '$cat', '$price', '$old_price', '$offer', '$delivery', '$stock')";
        
        if(mysqli_query($cn, $q)) {
            echo "<script>alert('Product Added to Inventory!'); window.location='product.php';</script>";
        } else {
            echo "Error: " . mysqli_error($cn);
        }
    } else {
        echo "<script>alert('Photo upload failed!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Product | Stylevana Admin</title>
    <link rel="style" href="addproductstyle.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <div class="admin-wrapper">
        <?php include("sidebar.php"); ?>

        <main class="main-content">
            <div class="form-card">
                <h1>Add New Product</h1>
                
                <form method="post" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="full-width">
                            <label>Product Full Name</label>
                            <input type="text" name="pname" placeholder="e.g. Silk Gold Necklace" required>
                        </div>
                        
                        <div>
                            <label>Brand Name</label>
                            <input type="text" name="brand" placeholder="Stylevana Luxe">
                        </div>
                        
                        <div>
                            <label>Category</label>
                            <select name="pcat" required>
                                <option value="jewellery">Jewellery</option>
                                <option value="skincare">Skincare</option>
                                <option value="clothes">Clothing</option>
                                <option value="Makeup">Makeup</option>
                            </select>
                        </div>

                        <div>
                            <label>Selling Price (₹)</label>
                            <input type="number" name="pprice" required>
                        </div>
                        
                        <div>
                            <label>Original Price (₹)</label>
                            <input type="number" name="old_price">
                        </div>

                        <div>
                            <label>Stock Quantity</label>
                            <input type="number" name="pstock" required>
                        </div>

                        <div>
                            <label>Delivery Type</label>
                            <select name="delivery">
                                <option value="Free Shipping">Free Shipping</option>
                                <option value="Standard Delivery (₹50)">Standard Delivery (₹50)</option>
                                <option value="Express Delivery (₹100)">Express Delivery (₹100)</option>
                            </select>
                        </div>

                        <div class="full-width">
                            <label>Offer Text</label>
                            <input type="text" name="offer" placeholder="e.g. Flat 20% OFF">
                        </div>

                        <div class="full-width">
                            <label>Product Description</label>
                            <textarea name="pdesc" rows="4"></textarea>
                        </div>

                        <div class="full-width">
                            <label>Product Main Photo</label>
                            <input type="file" name="pimage" required>
                        </div>
                    </div>

                    <button type="submit" name="btn_save" class="btn-submit">List Product on Stylevana</button>
                </form>
                
                <a href="product.php" class="back-btn">Cancel and return</a>
            </div>
        </main>
    </div>

    <?php include("footer.php"); ?>

</body>
</html>