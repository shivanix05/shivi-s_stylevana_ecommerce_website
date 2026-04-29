<?php 
include("function.php");
session_start();
// Admin protection
if(!isset($_SESSION["admin"])) { header("location:adminlogin.php"); exit(); } 
$cn = make_connection();

// Order ID fetch karna
if(isset($_GET["oid"])) {
    $oid =  $_GET["oid"];
    $res = mysqli_query($cn, "SELECT * FROM orders WHERE order_id='$oid'");
    $row = mysqli_fetch_array($res);
    if(!$row) { echo "Order not found!"; exit(); }
}

// Status Update Logic
if(isset($_POST["btn_status"])) {
    $new_status = $_POST["status_val"];
    $track_id =  $_POST["track_id"];
    $up_q = "UPDATE orders SET status='$new_status', tracking_id='$track_id' WHERE order_id='$oid'";
    if(mysqli_query($cn, $up_q)) {
        echo "<script>alert('Order Status Updated!'); window.location='order.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stylevana | Order #<?php echo $oid; ?></title>
    <link rel="stylesheet" href="order-detail.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <div class="admin-wrapper">
        
        <?php include("sidebar.php"); ?>

        <main class="main-content">
            <a href="order.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Shipments</a>

            <div class="detail-card">
                <div style="background: #FAF7F4; padding: 25px 40px; border-bottom: 1px solid #F2EBE4;">
                    <h1 style="font-family:'Playfair Display'; color: #333;">Order #SV-<?php echo $row['order_id']; ?></h1>
                    <p style="color:#AAA; font-size:0.85rem; margin-top: 5px;">
                        Customer: <b><?php echo $row['name']; ?></b> | 
                        Placed on: <?php echo date("d M, Y", strtotime($row['order_date'])); ?>
                    </p>
                </div>

                <div class="card-body">
                    <div class="grid">
                        <div>
                            <h3 style="font-size: 0.8rem; margin-bottom: 25px; color: var(--rose); letter-spacing: 1px;">SHIPPING DETAILS</h3>
                            
                            <p class="info-label">Full Name</p>
                            <p class="info-val"><?php echo $row['name']; ?></p>
                            
                            <p class="info-label">Contact Number</p>
                            <p class="info-val">+91 <?php echo $row['mobilenumber']; ?></p>
                            
                            <p class="info-label">Shipping Address</p>
                            <p class="info-val" style="line-height: 1.6;"><?php echo $row['adddress']; ?></p>
                            
                            <p class="info-label">Email Address</p>
                            <p class="info-val"><?php echo $row['user_email']; ?></p>
                        </div>

                        <div style="border-left: 1px solid #F9F9F9; padding-left: 30px;">
                            <h3 style="font-size: 0.8rem; margin-bottom: 25px; color: var(--rose); letter-spacing: 1px;">ORDER SUMMARY</h3>
                            
                            <div style="display:flex; align-items:center; gap:15px; background:#FDFBF9; padding:15px; border-radius:15px; border: 1px solid #F2EBE4;">
                                <?php 
                                    $p_photo = $row['productphoto'];
                                    $p_img = (!empty($p_photo)) ? $p_photo : "product_images/placeholder.png";
                                ?>
                                <img src="<?php echo $p_img; ?>" style="width:70px; height:70px; border-radius:12px; object-fit:cover;">
                                <div>
                                    <p style="font-size:0.75rem; color:#888;">Product ID: #<?php echo $row['pid']; ?></p>
                                    <p style="font-weight:600; color:#333; margin: 2px 0;">Stylevana Item</p>
                                    <p style="color:var(--rose); font-weight:700;">₹<?php echo number_format($row['productprice']); ?></p>
                                </div>
                            </div>

                            <div style="margin-top: 30px;">
                                <p class="info-label">Payment Method</p>
                                <p class="info-val"><?php echo $row['payment_method']; ?></p>
                                
                                <p class="info-label">Current Status</p>
                                <p class="info-val"><span style="color:var(--rose); font-weight:700; background: #FFF4F2; padding: 4px 10px; border-radius: 8px;"><?php echo $row['status']; ?></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="status-update-box">
                        <h3 style="font-size: 0.75rem; letter-spacing: 1.5px; margin-bottom:20px; color: #555;">SHIPMENT MANAGEMENT</h3>
                        <form method="post">
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div>
                                    <label class="info-label">Update Status</label>
                                    <select name="status_val">
                                        <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Processing" <?php if($row['status']=='Processing') echo 'selected'; ?>>Processing</option>
                                        <option value="Shipped" <?php if($row['status']=='Shipped') echo 'selected'; ?>>Shipped</option>
                                        <option value="Delivered" <?php if($row['status']=='Delivered') echo 'selected'; ?>>Delivered</option>
                                        <option value="Cancelled" <?php if($row['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="info-label">Tracking ID / AWB</label>
                                    <input type="text" name="track_id" value="<?php echo $row['tracking_id']; ?>" placeholder="Enter Tracking Number">
                                </div>
                            </div>
                            <button type="submit" name="btn_status" class="btn-save">Update Order Status</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php include("footer.php"); ?>

</body>
</html>