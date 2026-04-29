<?php 
include("function.php"); 
session_start();

// Admin protection
if(!isset($_SESSION["admin"])) { 
    header("location:adminlogin.php"); 
    exit(); 
} 

$cn = make_connection();

// Database se products fetch karna - Latest products first
$str = "SELECT * FROM shop ORDER BY pid DESC";
$rs = mysqli_query($cn, $str);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stylevana | Inventory Management</title>
    <link rel="stylesheet" href="product.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <div class="admin-wrapper">
        
        <?php include("sidebar.php"); ?>

        <main class="main-content">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
                <h1>Product Inventory</h1>
                <a href="addproduct.php" class="btn-add"><i class="fas fa-plus"></i> Add New Product</a>
            </div>

            <div class="inventory-card">
                <table>
                    <thead>
                        <tr>
                            <th>Product Details</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Current Qty</th>
                            <th>Stock Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_array($rs)) { 
                            $stock = (int)$row['stock_qty']; 
                            if($stock <= 0) {
                                $badge = "out-of-stock"; $text = "Sold Out";
                            } elseif($stock <= 5) {
                                $badge = "low-stock"; $text = "Low Stock";
                            } else {
                                $badge = "in-stock"; $text = "Available";
                            }
                        ?>
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:15px;">
                                    <img src="<?php echo $row['productphoto']; ?>" class="prod-img" onerror="this.src='../images/no-image.png'">
                                    <div>
                                        <b style="display:block; color: #333;"><?php echo $row['productname']; ?></b>
                                        <small style="color:var(--gray); font-size: 0.75rem;">PID: #<?php echo $row['pid']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><span style="color: #666; font-weight: 500;"><?php echo $row['category']; ?></span></td>
                            <td style="font-weight:600; color: var(--rose);">₹<?php echo number_format($row['productprice']); ?></td>
                            
                            <td style="font-weight:700; color: #444;"><?php echo $stock; ?></td>

                            <td><span class="badge <?php echo $badge; ?>"><?php echo $text; ?></span></td>
                            <td>
                                <a href="modifyproduct.php?pid=<?php echo $row['pid']; ?>" class="action-btn edit">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                
                                <a href="productdelet.php?r=<?php echo $row['pid']; ?>" 
                                   class="action-btn delete" 
                                   onclick="return confirm('Warning: This product will be removed from Stylevana. Proceed?')">
                                   <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <?php include("footer.php"); ?>

</body>
</html>