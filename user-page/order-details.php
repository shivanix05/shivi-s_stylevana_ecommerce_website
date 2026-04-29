<?php 
require_once __DIR__ . "/config.php";
session_start();

if (!isset($_SESSION["user"])){ 
    header("location:login.php"); 
    exit(); 
}

if(isset($_GET['id'])) {
    $order_id = mysqli_real_escape_string($cn, $_GET['id']);
} else {
    header("location:myorder.php");
    exit();
}

$user = $_SESSION['user'];

$query = "SELECT * FROM orders WHERE order_id = '$order_id' AND user_email = '$user'";
$result = mysqli_query($cn, $query);
$order = mysqli_fetch_assoc($result);

if(!$order) { 
    echo "<script>alert('Order not found!'); window.location.href='myorder.php';</script>"; 
    exit(); 
}

// Current status check
$s = isset($order['status']) ? $order['status'] : 'Order Placed'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary - Stylevana</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="afterl-style.css">
    <style>
        .order-details-container { max-width: 1100px; margin: 100px auto 50px auto; padding: 20px; font-family: 'Poppins', sans-serif; }
        .details-card { background: #fff; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); padding: 30px; border: 1px solid #eee; position: relative; }

        .status-line-container { display: flex; justify-content: space-between; margin-bottom: 40px; padding: 20px 0; border-bottom: 1px solid #f5f5f5; }
        .status-item { flex: 1; text-align: center; position: relative; }
        .status-item:not(:last-child)::after { content: ''; position: absolute; top: 10px; left: 50%; width: 100%; height: 2px; background: #eee; z-index: 1; }
        .indicator { width: 20px; height: 20px; background: #fff; border: 2px solid #eee; border-radius: 50%; display: inline-block; position: relative; z-index: 2; }
        
        .status-item.active .indicator { background: #D9A299; border-color: #D9A299; box-shadow: 0 0 10px rgba(217, 162, 153, 0.5); }
        .status-item.active p { color: #333; font-weight: bold; }
        
        .status-item.cancelled .indicator { background: #e74c3c; border-color: #e74c3c; box-shadow: 0 0 10px rgba(231, 76, 60, 0.3); }
        .status-item.cancelled p { color: #e74c3c; font-weight: bold; }
        
        .status-item p { font-size: 0.75rem; margin-top: 8px; color: #888; font-weight: 600; }

        .summary-grid { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 30px; }
        .prod-display { display: flex; gap: 20px; background: #fdfaf9; padding: 20px; border-radius: 12px; align-items: center; border: 1px solid #f9ecea; }
        
        .cancelled-overlay { filter: grayscale(1); opacity: 0.7; }
        
        .prod-display img { width: 140px; height: 180px; object-fit: cover; border-radius: 10px; border: 1px solid #eee; }
        .form-data-box { background: #fff; border: 1px dashed #D9A299; padding: 20px; border-radius: 12px; }
        .form-data-box h4 { margin-bottom: 15px; color: #D9A299; font-size: 1rem; border-bottom: 1px solid #f9ecea; padding-bottom: 5px; }
        .form-data-box p { font-size: 0.9rem; margin-bottom: 8px; color: #555; }
        .tracking-id-badge { display: inline-block; background: #333; color: #fff; padding: 2px 10px; border-radius: 5px; font-size: 0.8rem; margin-top: 5px; }

        .cancel-badge { background: #feeaea; color: #e74c3c; padding: 5px 15px; border-radius: 20px; font-weight: bold; display: inline-block; margin-bottom: 15px; border: 1px solid #fabebb; }
        
        .pay-now-btn { display: inline-block; background: #D9A299; color: #fff; padding: 8px 20px; border-radius: 5px; text-decoration: none; font-size: 0.9rem; font-weight: 600; margin-top: 10px; transition: 0.3s; }
        .pay-now-btn:hover { background: #c68e84; box-shadow: 0 4px 10px rgba(217, 162, 153, 0.3); }

        @media (max-width: 768px) { .summary-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <?php include("header.php"); ?>

    <div class="order-details-container">
        <div class="details-card">
            
            <h2 style="font-family: 'Playfair Display', serif; margin-bottom: 10px;">Order Details</h2>
            
            <?php if($s == 'Cancelled'): ?>
                <div class="cancel-badge"><i class="fas fa-times-circle"></i> This order has been cancelled</div>
            <?php endif; ?>

            <div class="status-line-container">
                <?php if($s != 'Cancelled'): ?>
                    <div class="status-item active">
                        <span class="indicator"></span>
                        <p>PLACED</p>
                    </div>
                    <div class="status-item <?php echo ($s=='Shipped' || $s=='Delivered')?'active':''; ?>">
                        <span class="indicator"></span>
                        <p>SHIPPED</p>
                    </div>
                    <div class="status-item <?php echo ($s=='Delivered')?'active':''; ?>">
                        <span class="indicator"></span>
                        <p>DELIVERED</p>
                    </div>
                <?php else: ?>
                    <div class="status-item active">
                        <span class="indicator"></span>
                        <p>PLACED</p>
                    </div>
                    <div class="status-item cancelled">
                        <span class="indicator"></span>
                        <p>CANCELLED</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="summary-grid <?php echo ($s=='Cancelled')?'cancelled-overlay':''; ?>">
                <div class="prod-display">
                    <img src="<?php echo $order['productphoto']; ?>" 
                         onerror="this.src='admin/<?php echo $order['productphoto']; ?>';" 
                         alt="Product Photo">
                    
                    <div>
                        <h3 style="margin-bottom: 10px; color: #333;">Order ID: #STV-<?php echo $order['order_id']; ?></h3>
                        <p style="color: #D9A299; font-weight: bold; font-size: 1.4rem;">₹<?php echo number_format($order['productprice']); ?></p>
                        <p style="font-size: 0.95rem; margin-top: 10px; color: #666;">Quantity: <strong><?php echo $order['qty']; ?></strong></p>
                        <p style="font-size: 0.85rem; color: #888; margin-top: 5px;">Order Date: <?php echo date('d M, Y', strtotime($order['order_date'])); ?></p>
                        <p style="font-size: 0.85rem; color: #666;">Shipping: <strong><?php echo ($order['shipping_charge'] > 0) ? '₹'.$order['shipping_charge'] : 'FREE'; ?></strong></p>
                    </div>
                </div>

                <div class="form-data-box">
                    <h4><i class="fas fa-truck"></i> Delivery Details</h4>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['adddress']); ?></p>
                    <p><strong>Mobile:</strong> <?php echo $order['mobilenumber']; ?></p>
                    
                    <?php if(!empty($order['tracking_id']) && $s != 'Cancelled'): ?>
                        <p><strong>Tracking ID:</strong> <span class="tracking-id-badge"><?php echo $order['tracking_id']; ?></span></p>
                    <?php endif; ?>

                    <h4 style="margin-top: 20px;"><i class="fas fa-credit-card"></i> Payment Information</h4>
                    <p><strong>Method:</strong> <?php echo $order['payment_method']; ?></p>
                    <p><strong>Payment Status:</strong> 
                        <span style="color: <?php 
                            if($s == 'Cancelled') echo '#888';
                            elseif(stripos($order['payment_method'], 'Online') !== false || $s == 'Delivered') echo '#27ae60'; 
                            else echo '#f39c12'; 
                        ?>; font-weight: bold;">
                            <?php 
                                if($s == 'Cancelled' && stripos($order['payment_method'], 'COD') !== false) {
                                    echo 'Void';
                                } else {
                                    if(stripos($order['payment_method'], 'Online') !== false || $s == 'Delivered') {
                                        echo 'Paid';
                                    } else {
                                        echo 'Unpaid';
                                    }
                                }
                            ?>
                        </span>
                    </p>

                    <?php if(stripos($order['payment_method'], 'COD') !== false && $s != 'Cancelled' && $s != 'Delivered'): ?>
                        <a href="checkout.php?id=<?php echo $order['order_id']; ?>" class="pay-now-btn">
                            <i class="fas fa-wallet"></i> Pay Online Now
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div style="margin-top: 40px; text-align: center;">
                <a href="myorder.php" style="text-decoration: none; color: #D9A299; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-chevron-left"></i> Back to My Orders
                </a>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>

</body>
</html>
