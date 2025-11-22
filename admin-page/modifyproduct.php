<?php

include("config.php");

if (isset($_POST["btnsave"])) {
    
    $pid = isset($_POST["productid"]) ? (int)$_POST["productid"] : 0;
    $productname = $_POST["productname"];
    $productdescription = $_POST["productdescription"];
    $productprice = (float)$_POST["productprice"];
    $str_fetch_current = "SELECT productphoto FROM shop WHERE pid = " . $pid;
    $rs_fetch_current = mysqli_query($cn, $str_fetch_current);
    $row_current = mysqli_fetch_array($rs_fetch_current);
    $target = $row_current['productphoto']; 
    if (isset($_FILES["productphoto"]) && $_FILES["productphoto"]["error"] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES["productphoto"]["tmp_name"];
        $file_name = basename($_FILES["productphoto"]["name"]);
        $target = "product_images/" . $file_name;
        move_uploaded_file($file_tmp, $target);
    }
    $str_update = "UPDATE shop SET productname='$productname', productphoto='$target', productdescription='$productdescription', productprice='$productprice' WHERE pid='$pid'";
    if (mysqli_query($cn, $str_update)) {
        echo "<script>alert('Product updated successfully!'); window.location.href='product.php';</script>";
    } else {
        echo "Error updating record: " . mysqli_error($cn);
    }
}
$s = isset($_REQUEST['r']) ? (int)$_REQUEST['r'] : 0;
if ($s === 0) {
    die("No product ID specified.");
}
$str_select = "SELECT * FROM shop WHERE pid=" . $s;
$rs_select = mysqli_query($cn, $str_select);

if (!$rs_select || mysqli_num_rows($rs_select) == 0) {
    die("Product not found.");
}
$row = mysqli_fetch_array($rs_select);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Product | Shivi's Stylevana Admin</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <div class="container">
        <h1>Shivi's Stylevana Admin Panel</h1>

        <div class="admin-section">
            <h2>Modify Product Details</h2>
            <form id="modifyProductForm" method="post" enctype="multipart/form-data">
                <input type="hidden" name="productid" value="<?php echo $row["pid"]; ?>">

                <div class="form-group">
                    <label>Product ID:</label>
                    <input type="text" value="<?php echo $row["pid"]; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="productname" value="<?php echo $row["productName"]; ?>" required>
                </div>
                <div class="form-group">
                    <label>Product Image</label>
                    <img src="<?php echo  $row["productphoto"]; ?>" alt="Product Image" width="100">
                    <br>
                    <input type="file" name="productphoto">
                </div>
                <div class="form-group">
                    <label>Product Description</label>
                    <textarea name="productdescription" required><?php echo $row["productdescription"]; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Product Price:</label>
                    <input type="number" step="0.01" name="productprice" value="<?php echo $row["productprice"]; ?>" required>
                </div>
                <div class="form-group">
                    <label>Category:</label>
                    <select name="category" required>
                        <option value="jewellery" <?php if($row['category'] == 'jewellery') echo 'selected'; ?>>Jewellery</option>
                        <option value="clothes" <?php if($row['category'] == 'clothes') echo 'selected'; ?>>Clothes</option>
                        <option value="makeup" <?php if($row['category'] == 'makeup') echo 'selected'; ?>>Makeup</option>
                        <option value="skincare" <?php if($row['category'] == 'skincare') echo 'selected'; ?>>Skincare</option>
                    </select>
                </div>
                <input type="submit" name="btnsave" value="Save Changes">
                <button type="button" class="cancel-button"><a href="product.php">Cancel</a></button>
            </form>
        </div>
    </div>
</body>
</html>