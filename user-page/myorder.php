<?php 
require_once __DIR__ . "/config.php";
session_start();

if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();
}

$user = $_SESSION['user'];
$show_thanks = false; 

// ==========================================
// 1. ORDER SAVING & STOCK UPDATE LOGIC
// ==========================================
if (isset($_POST['place_order'])) {
    $name = mysqli_real_escape_string($cn, $_POST['name']);
    $mobile = mysqli_real_escape_string($cn, $_POST['mobile']);
    $address = mysqli_real_escape_string($cn, $_POST['address']); 
    $payment_method = mysqli_real_escape_string($cn, $_POST['payment_method']);
    $pid_val = $_POST['pid']; 
    $total_amount = mysqli_real_escape_string($cn, $_POST['total_amount']);
    $order_date = date("Y-m-d H:i:s");

    $status = "Placed"; 
    $payment_status = ($payment_method == "COD") ? "Pending" : "Completed";
    $shipping_charge = 0; 

    if ($pid_val == "0") {
        // CASE A: Cart Checkout logic
        $cart_items = mysqli_query($cn, "SELECT c.*, s.productphoto, s.productprice FROM cart c JOIN shop s ON c.pid = s.pid WHERE c.user_email = '$user'");
        
        while ($item = mysqli_fetch_assoc($cart_items)) {
            if (isset($_POST['selected_items']) && in_array($item['id'], $_POST['selected_items'])) {
                $current_pid = $item['pid'];
                $price = $item['productprice'];
                $photo = $item['productphoto'];
                $qty = $item['qty'];

                $insert_query = "INSERT INTO orders (user_email, pid, name, adddress, productprice, qty, productphoto, mobilenumber, payment_method, order_date, status, payment_status, shipping_charge) 
                                 VALUES ('$user', '$current_pid', '$name', '$address', '$price', '$qty', '$photo', '$mobile', '$payment_method', '$order_date', '$status', '$payment_status', '$shipping_charge')";
                
                if(mysqli_query($cn, $insert_query)){
                    // --- STOCK UPDATE START ---
                    mysqli_query($cn, "UPDATE shop SET stock_qty = stock_qty - $qty WHERE pid = '$current_pid'");
                    // --- STOCK UPDATE END ---
                } else {
                    die("Error: " . mysqli_error($cn));
                }
            }
        }

        if (isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
            $ids_to_delete = implode(',', array_map('intval', $_POST['selected_items']));
            mysqli_query($cn, "DELETE FROM cart WHERE id IN ($ids_to_delete) AND user_email = '$user'");
        }

    } else {
        // CASE B: Single Item "Buy Now"
        $prod_res = mysqli_query($cn, "SELECT productphoto FROM shop WHERE pid = '$pid_val'");
        $prod_data = mysqli_fetch_assoc($prod_res);
        $photo = $prod_data['productphoto'];
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

        $insert_query = "INSERT INTO orders (user_email, pid, name, adddress, productprice, qty, productphoto, mobilenumber, payment_method, order_date, status, payment_status, shipping_charge) 
                         VALUES ('$user', '$pid_val', '$name', '$address', '$total_amount', '$qty', '$photo', '$mobile', '$payment_method', '$order_date', '$status', '$payment_status', '$shipping_charge')";
        
        if(mysqli_query($cn, $insert_query)){
            // --- STOCK UPDATE START ---
            mysqli_query($cn, "UPDATE shop SET stock_qty = stock_qty - $qty WHERE pid = '$pid_val'");
            // --- STOCK UPDATE END ---
        } else {
            die("Error: " . mysqli_error($cn));
        }
    }
    
    $show_thanks = true; 
}       
?>
   
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Shivi's Stylevana</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="afterl-style.css">
    <style>
        .order-container { max-width: 1000px; margin: 40px auto; padding: 20px; }
        .order-card { 
            background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 25px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 5px solid #D9A299;
        }
        .order-header { display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
        .order-id { font-weight: bold; color: #333; }
        .order-date { color: #888; font-size: 0.85rem; }
        
        .order-body { display: flex; gap: 20px; align-items: center; }
        .order-img-box { 
            width: 100px; height: 120px; overflow: hidden; border-radius: 10px; 
            border: 1px solid #f0f0f0; flex-shrink: 0;
        }
        .order-img-box img { width: 100%; height: 100%; object-fit: cover; }

        .order-info-flex { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; width: 100%; }
        .shipping-info h4 { margin: 0 0 10px 0; color: #D9A299; font-size: 1rem; }
        .shipping-info p { margin: 2px 0; font-size: 0.85rem; color: #666; }
        
        .status-badge { 
            background: #fdf0ee; color: #D9A299; padding: 5px 12px; border-radius: 20px; 
            font-size: 0.75rem; font-weight: bold; border: 1px solid #D9A299;
        }
        .total-amt { font-size: 1.3rem; font-weight: bold; color: #333; }
        
        .view-btn {
            display: inline-block; margin-top: 15px; background: #D9A299; color: white; 
            padding: 8px 18px; border-radius: 20px; text-decoration: none; 
            font-size: 0.8rem; font-weight: 600;
        }

        #thanksModal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; display: flex; justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 40px; border-radius: 20px; text-align: center; max-width: 400px; border: 3px solid #D9A299; animation: pop 0.3s ease; }
        @keyframes pop { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>
</head>
<body>

    <?php include("header.php"); ?>

    <?php if($show_thanks): ?>
    <div id="thanksModal">
        <div class="modal-content">
            <i class="fas fa-heart" style="font-size: 4rem; color: #D9A299; margin-bottom: 20px;"></i>
            <h2 style="font-family: 'Playfair Display', serif; color: #333;">Order Placed!</h2>
            <p style="color: #666; font-size: 1.1rem; margin: 15px 0;">Your order is placed. Thank you for shopping, cutie! ✨</p>
            <button onclick="window.location.href='myorder.php'" style="background: #D9A299; color: white; border: none; padding: 12px 30px; border-radius: 25px; cursor: pointer; font-weight: bold;">View My Orders</button>
        </div>
    </div>
    <?php endif; ?>

    <main class="order-container">
        <h1 style="font-family: 'Playfair Display', serif; margin-bottom: 30px; margin-top: 80px;">Your Order History</h1>

        <?php
        $query = "SELECT * FROM orders WHERE user_email = '$user' ORDER BY order_id DESC";
        $result = mysqli_query($cn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <span class="order-id">Order #<?php echo $row['order_id']; ?></span>
                            <div class="order-date"><i class="far fa-calendar-alt"></i> <?php echo date('d M, Y', strtotime($row['order_date'])); ?></div>
                        </div>
                        <div>
                            <span class="status-badge"><?php echo $row['status']; ?></span>
                        </div>
                    </div>

                    <div class="order-body">
                        <div class="order-img-box">
                            <img src="<?php echo $row['productphoto']; ?>" 
                                 onerror="this.src='admin/<?php echo $row['productphoto']; ?>';" alt="Product">
                        </div>

                        <div class="order-info-flex">
                            <div class="shipping-info">
                                <h4><i class="fas fa-truck"></i> Shipping Address</h4>
                                <p><strong><?php echo $row['name']; ?></strong></p>
                                <p><?php echo $row['adddress']; ?></p> 
                                <p><i class="fas fa-phone-alt"></i> <?php echo $row['mobilenumber']; ?></p>
                            </div>

                            <div style="text-align: right;">
                                <p style="color: #888; font-size: 0.8rem; margin-bottom: 0;">Amount Paid</p>
                                <div class="total-amt">₹<?php echo number_format($row['productprice']); ?></div>
                                <a href="order-details.php?id=<?php echo $row['order_id']; ?>" class="view-btn">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<div style='text-align:center; padding: 50px;'><h3>No orders yet!</h3><a href='after-login.php'>Shop now</a></div>";
        }
        ?>
    </main>

    <?php include("footer.php"); ?>

</body>
</html>
