<?php
$cn = mysqli_connect("localhost", "root", "123456789", "shivi-stylevana");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shivi's Stylevana Admin</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <div class="container">
        <h1>Shivi's Stylevana Admin Panel</h1>
        
        <div class="admin-section">
            <h2>Add New Product</h2>
            <form id="addProductForm"  method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Product Name:</label>
                    <input type="text" id="productName" name="productname" required>
                </div>
                <div class="form-group">
                    <label>Product Photo:</label>
                    <input type="file" id="productPhoto" name="productphoto" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label>Product Description:</label>
                    <textarea id="productDescription" name="productdescription" required></textarea>
                </div>
                <div class="form-group">
                    <label>Category:</label>
                    <select id="productCategory" name="category" required>
                        <option value="">Select Category</option>
                        <option value="jewellery">Jewellery</option>
                        <option value="clothes">Clothes</option>
                        <option value="makeup">Makeup</option>
                        <option value="skincare">Skincare</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Product Price:</label>
                    <input type="number" id="productPrice" name="productprice" step="0.01" required>
                </div>
                <button type="submit" name="addproduct">Add Product</button>
            </form>
        </div>
        
        <hr>
        
        <?php
        if (isset($_POST["addproduct"])) 
            {
                 $productname = $_POST["productname"];
                 $productdescription = $_POST["productdescription"];
                 $category = $_POST["category"];
                 $productprice = $_POST["productprice"];
                 $file_name=$_FILES["productphoto"]["name"];
                 $file_tmp = $_FILES["productphoto"]["tmp_name"];
                 $target="product_images/".basename($file_tmp);
                   if(move_uploaded_file($file_tmp, $target)){
                 $str = "insert into shop (productname, productphoto, productdescription, category, productprice) values('$productname', '$target', '$productdescription', '$category', '$productprice')";
                  mysqli_query($cn, $str);
                 }
                  else{
                     echo "image failde uploading";
                      }
            }           
        ?>
        
        <?php 
        $str = "SELECT * FROM shop";
        $rs = mysqli_query($cn, $str);
        
        ?>
        
        <div class="container">
            <table id="productListTable">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Product Photo</th>
                        <th>Product Description</th>
                        <th>Category</th>
                        <th>Product Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_array($rs)) { ?>
                    <tr>
                        <td><?php echo $row['pid']; ?></td>
                        <td><?php echo $row['productName']; ?></td>
                        <td>
                            
                            <img src="<?php echo $row['productphoto']; ?>" alt="Product Image" width="100">
                        </td>
                        <td><?php echo $row['productdescription']; ?></td>
                        <td><?php echo  $row['category']; ?></td>
                        <td><?php echo $row['productprice']; ?></td>
                        <td>
                            <button class="delete"><a href="productdelet.php?r=<?php echo $row['pid']; ?>">Delete</a></button>
                           <a href="modifyproduct.php?r=<?php echo $row['pid']; ?>">
                           <button class="modify">Modify</button>
</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div> 
        
    </div>
</body>
</html>
