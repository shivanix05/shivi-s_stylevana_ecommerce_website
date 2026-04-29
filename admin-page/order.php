<?php 
include("function.php"); 
session_start();
// Admin protection
if(!isset($_SESSION["admin"])) { header("location:adminlogin.php"); exit(); } 
$cn = make_connection();

// Saare orders fetch karna - Latest order sabse upar
$str = "SELECT * FROM orders ORDER BY order_id DESC";
$rs = mysqli_query($cn, $str);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stylevana | Order Management</title>
    <link rel="stylesheet" href="order.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <div class="admin-wrapper">
        
        <?php include("sidebar.php"); ?>

        <main class="main-content">
            <h1>Order Shipments</h1>

            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_array($rs)) { 
                            $st = strtolower($row['status']); 
                        ?>
                        <tr>
                            <td><b style="color: #999;">#SV-<?php echo $row['order_id']; ?></b></td>
                            <td>
                                <span style="display:block; font-weight:500; color: #333;"><?php echo $row['name']; ?></span>
                                <small style="color:#AAA; font-size: 0.75rem;"><?php echo $row['user_email']; ?></small>
                            </td>
                            <td style="font-weight:600; color: var(--rose);">₹<?php echo number_format($row['productprice']); ?></td>
                            <td style="font-size: 0.75rem; color: #777; font-weight: 500;"><?php echo $row['payment_method']; ?></td>
                            <td>
                                <span class="status <?php echo $st; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="order-details.php?oid=<?php echo $row['order_id']; ?>" class="btn-details">View Details</a>
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