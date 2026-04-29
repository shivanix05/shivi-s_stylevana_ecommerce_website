<?php
include("function.php");
session_start();

// Admin login check
if(!isset($_SESSION["admin"])) { 
    header("location:adminlogin.php"); 
    exit(); 
}

$cn = make_connection();

// 1. URL se Product ID (pid) 
if(isset($_GET["pid"])) {
    $pid = mysqli_real_escape_string($cn, $_GET["pid"]);
    $res = mysqli_query($cn, "SELECT * FROM shop WHERE pid='$pid'");
    $row = mysqli_fetch_array($res);
    
    if(!$row) { 
        header("location:product.php"); 
        exit(); 
    }
} else {
    header("location:product.php");
    exit();
}

// 2. Update Button logic
if(isset($_POST["btn_update"])) {
    $pname = mysqli_real_escape_string($cn, $_POST["pname"]);
    $brand = mysqli_real_escape_string($cn, $_POST["brand"]);
    $cat = mysqli_real_escape_string($cn, $_POST["pcat"]);
    $price = mysqli_real_escape_string($cn, $_POST["pprice"]);
    
    // --- ERROR FIX: Khali price ko 0 set karna ---
    $old_price = $_POST["old_price"];
    if ($old_price == "") {
        $old_price = 0; 
    } else {
        $old_price = mysqli_real_escape_string($cn, $old_price);
    }

    $stock = mysqli_real_escape_string($cn, $_POST["pstock"]); 
    $offer = mysqli_real_escape_string($cn, $_POST["offer"]);
    $delivery = mysqli_real_escape_string($cn, $_POST["delivery"]);
    $desc = mysqli_real_escape_string($cn, $_POST["pdesc"]);

    // Image Upload Logic
    if($_FILES["pimage"]["name"] != "") {
        $fn = $_FILES["pimage"]["name"];
        $path = "product_images/" . time() . "_" . $fn;
        move_uploaded_file($_FILES["pimage"]["tmp_name"], $path);
        $img_query = ", productphoto='$path'";
    } else {
        $img_query = ""; 
    }

    // Database UPDATE query
    $up_q = "UPDATE shop SET 
            productname='$pname', 
            brand_name='$brand', 
            category='$cat', 
            productprice='$price', 
            original_price='$old_price', 
            stock_qty='$stock', 
            offer_text='$offer',
            delivery_type='$delivery',
            productdescription='$desc' 
            $img_query 
            WHERE pid='$pid'";

    if(mysqli_query($cn, $up_q)) {
        echo "<script>alert('Product Updated Successfully!'); window.location='product.php';</script>";
    } else {
        echo "Error: " . mysqli_error($cn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Stylevana Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
       <link rel="stylesheet" href="modifyproduct.css" />

</head>
<body>

    <?php include("header.php"); ?>

    <div class="admin-wrapper">
        <?php include("sidebar.php"); ?>

        <main class="main-content">
            <div class="edit-card">
                <h1><i class="fas fa-edit" style="color:var(--rose); margin-right:10px;"></i> Edit Product</h1>
                
                <form method="post" enctype="multipart/form-data">
                    <div class="form-grid">
                        
                        <div class="full-width">
                            <label>Product Name</label>
                            <input type="text" name="pname" value="<?php echo htmlspecialchars($row['productname']); ?>" required>
                        </div>

                        <div>
                            <label>Brand Name</label>
                            <input type="text" name="brand" value="<?php echo htmlspecialchars($row['brand_name']); ?>">
                        </div>

                        <div>
                            <label>Category</label>
                            <select name="pcat">
                                <option value="jewellery" <?php if($row['category']=='jewellery') echo 'selected'; ?>>Jewellery</option>
                                <option value="skincare" <?php if($row['category']=='skincare') echo 'selected'; ?>>Skincare</option>
                                <option value="clothing" <?php if($row['category']=='clothing') echo 'selected'; ?>>Clothing</option>
                                
                                 <option value="Makeup" <?php if($row['category']=='Makeup') echo 'selected'; ?>>Makeup</option>
                            </select>
                        </div>

                        <div>
                            <label>Selling Price (₹)</label>
                            <input type="number" step="0.01" name="pprice" value="<?php echo $row['productprice']; ?>" required>
                        </div>

                        <div>
                            <label>MRP / Old Price (₹)</label>
                            <input type="number" step="0.01" name="old_price" value="<?php echo $row['original_price']; ?>">
                        </div>

                        <div class="stock-box">
                            <label style="color: var(--rose);">Stock Quantity</label>
                            <input type="number" name="pstock" value="<?php echo $row['stock_qty']; ?>" required>
                        </div>

                        <div>
                            <label>Delivery Option</label>
                            <select name="delivery">
                                <option value="Free Shipping" <?php if($row['delivery_type']=='Free Shipping') echo 'selected'; ?>>Free Shipping</option>
                                <option value="Standard Delivery (₹50)" <?php if($row['delivery_type']=='Standard Delivery (₹50)') echo 'selected'; ?>>Standard Delivery (₹50)</option>
                                <option value="Express Delivery (₹100)" <?php if($row['delivery_type']=='Express Delivery (₹100)') echo 'selected'; ?>>Express Delivery (₹100)</option>
                            </select>
                        </div>

                        <div class="full-width">
                            <label>Offer Text</label>
                            <input type="text" name="offer" value="<?php echo htmlspecialchars($row['offer_text']); ?>" placeholder="e.g. Flat 20% OFF">
                        </div>

                        <div class="full-width">
                            <label>Product Description</label>
                            <textarea name="pdesc" rows="4"><?php echo htmlspecialchars($row['productdescription']); ?></textarea>
                        </div>

                        <div class="full-width">
                            <div style="display:flex; gap:20px; align-items:flex-end;">
                                <div>
                                    <label>Current Image</label>
                                    <img src="<?php echo $row['productphoto']; ?>" class="current-img" onerror="this.src='../images/no-image.png'">
                                </div>
                                <div style="flex:1;">
                                    <label>Replace Image (Optional)</label>
                                    <input type="file" name="pimage">
                                </div>
                            </div>
                        </div>

                    </div>

                    <button type="submit" name="btn_update" class="btn-update">Save Changes</button>
                </form>
                
                <a href="product.php" class="back-btn"><i class="fas fa-arrow-left"></i> Discard and Return</a>
            </div>
        </main>
    </div>

    <?php include("footer.php"); ?>

</body>
</html>