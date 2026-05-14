<?php 
require_once __DIR__ . "/config.php"; 
session_start();

// Connection check ($cn use kiya hai)
if (!$cn) {
    die("Connection failed: Please check your config.php settings.");
}

// Login protection
if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();    
}

$user = $_SESSION['user'];

// --- Fetch User Details ---
$user_info_query = mysqli_query($cn, "SELECT * FROM userdetail WHERE gmail = '$user'");
$user_data = mysqli_fetch_assoc($user_info_query);

// Logic to identify if request is from Cart or Single "Buy Now"
$buy_pid = isset($_GET['buy_pid']) ? $_GET['buy_pid'] : null;
$qty = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;
$is_cart = (isset($_POST['checkout_from']) && $_POST['checkout_from'] == 'cart');

$order_items = [];
$final_total = 0;
$order_type = "";

// --- Data Fetching Logic ---
if ($is_cart) {
    $cart_query = "SELECT c.*, s.productname, s.productprice, s.productphoto, s.stock_qty 
                   FROM cart c JOIN shop s ON c.pid = s.pid 
                   WHERE c.user_email = '$user'"; 
    
    $cart_res = mysqli_query($cn, $cart_query);
    while($row = mysqli_fetch_assoc($cart_res)) {
        if($row['stock_qty'] <= 0) {
            echo "<script>alert('Some items in your cart are out of stock!'); window.location.href='cart.php';</script>";
            exit();
        }
        $order_items[] = $row;
        $final_total += ($row['productprice'] * $row['qty']);
    }
    $order_type = "0"; 
} elseif ($buy_pid) {
    $buy_pid = mysqli_real_escape_string($cn, $buy_pid);
    $single_query = "SELECT * FROM shop WHERE pid = '$buy_pid'";
    $res = mysqli_query($cn, $single_query);
    $row = mysqli_fetch_assoc($res);
    
    if($row) {
        if($row['stock_qty'] <= 0) {
            echo "<script>alert('This product is currently out of stock!'); window.location.href='after-login.php';</script>";
            exit();
        }
        $row['qty'] = $qty; 
        $order_items[] = $row;
        $final_total = $row['productprice'] * $qty;
        $order_type = $buy_pid;
    }
}

// --- FIXED: Online Payment Redirect Logic ---
if (isset($_POST['place_order']) && $_POST['payment_method'] == 'Online') {
    // Check if we have order items to get the photo
    $product_photo = isset($order_items[0]['productphoto']) ? $order_items[0]['productphoto'] : '';

    $_SESSION['temp_order'] = [
        'name' => $_POST['name'],
        'mobile' => $_POST['mobile'],
        'address' => $_POST['address'],
        'total_amount' => $_POST['total_amount'],
        'pid' => $_POST['pid'],
        'qty' => $_POST['qty_hidden'],
        'photo' => $product_photo // <--- YE FIX HAI: Photo path pass ho raha hai
    ];
    header("location:payment_gateway.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout - Shivi's Stylevana</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@700&display=swap');
        
        body { background-color: #fdfaf9; font-family: 'Poppins', sans-serif; margin:0; }
        .checkout-wrapper { 
            max-width: 1150px; margin: 50px auto; 
            display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px; padding: 20px; 
        }
        .section-card { 
            background: #fff; padding: 35px; border-radius: 20px; 
            box-shadow: 0 8px 25px rgba(0,0,0,0.04); 
        }
        h2 { font-family: 'Playfair Display', serif; margin-bottom: 25px; color: #333; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #666; font-size: 0.9rem; }
        .form-group input, .form-group textarea { 
            width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 10px; background: #fcfcfc;
            font-family: inherit; font-size: 0.95rem; outline: none; transition: 0.3s;
        }
        .form-group input:focus { border-color: #D9A299; }

        .item-list-row { display: flex; gap: 15px; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #f9f9f9; }
        .item-list-row img { width: 65px; height: 65px; object-fit: cover; border-radius: 10px; border: 1px solid #eee; }
        
        .payment-method { 
            margin-top: 15px; padding: 18px; border: 1px solid #f0f0f0; border-radius: 12px; 
            cursor: pointer; display: flex; align-items: center; gap: 12px; transition: 0.3s;
        }
        .payment-method:hover { background: #fffcfb; border-color: #D9A299; }
        .payment-method input { width: auto; accent-color: #D9A299; }

        .confirm-btn { 
            width: 100%; background: #D9A299; color: white; padding: 18px; border: none; 
            border-radius: 35px; font-size: 1.1rem; font-weight: bold; cursor: pointer; 
            margin-top: 30px; transition: 0.4s; box-shadow: 0 10px 20px rgba(217, 162, 153, 0.3);
        }
        .confirm-btn:hover { background: #c58d83; transform: translateY(-2px); }
        
        .total-highlight { 
            font-size: 1.5rem; font-weight: bold; color: #D9A299; 
            display: flex; justify-content: space-between; margin-top: 20px; 
            border-top: 2px solid #f9f9f9; padding-top: 15px; 
        }

        @media (max-width: 900px) { .checkout-wrapper { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <?php include("header.php"); ?>

    <main class="checkout-wrapper">
        <section class="section-card">
            <h2><i class="fas fa-shipping-fast" style="color: #D9A299;"></i> Shipping Details</h2>
            
            <form id="checkoutForm" action="myorder.php" method="POST">
                <input type="hidden" name="pid" value="<?php echo $order_type; ?>">
                <input type="hidden" name="total_amount" value="<?php echo $final_total; ?>">
                <input type="hidden" name="qty_hidden" value="<?php echo $qty; ?>">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user_data['name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="tel" name="mobile" value="<?php echo htmlspecialchars($user_data['mobilenumber'] ?? ''); ?>" pattern="\+91[0-9]{10}" required>
                </div>
                <div class="form-group">
                    <label>Complete Address</label>
                    <textarea name="address" rows="3" required><?php echo htmlspecialchars($user_data['address'] ?? ''); ?></textarea>
                </div>

                <h3 style="margin-top: 30px;">Payment Method</h3>
                <label class="payment-method">
                    <input type="radio" name="payment_method" value="COD" checked onclick="this.form.action='myorder.php'">
                    <span>Cash on Delivery (COD)</span>
                    <i class="fas fa-money-bill-wave" style="margin-left: auto; color: #D9A299;"></i>
                </label>
                <label class="payment-method">
                    <input type="radio" name="payment_method" value="Online" onclick="this.form.action=''">
                    <span>Online Payment (UPI/Card)</span>
                    <i class="fab fa-cc-visa" style="margin-left: auto; color: #aaa;"></i>
                </label>

                <button type="submit" name="place_order" class="confirm-btn">CONFIRM & PLACE ORDER</button>
            </form>
        </section>

        <section class="section-card" style="height: fit-content;">
            <h2>Order Summary</h2>
            <div class="order-items-container">
                <?php foreach($order_items as $item): ?>
                <div class="item-list-row">
                    <img src="<?php echo $item['productphoto']; ?>" alt="Product">
                    <div style="flex: 1;">
                        <h4 style="margin: 0; color: #333; font-size: 0.95rem;"><?php echo $item['productname']; ?></h4>
                        <p style="margin: 5px 0 0; color: #888; font-size: 0.85rem;">Qty: <?php echo $item['qty']; ?> Unit(s)</p>
                    </div>
                    <div style="font-weight: 600; color: #444;">₹<?php echo number_format($item['productprice'] * $item['qty']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="total-highlight">
                <span>Total Amount</span>
                <span>₹<?php echo number_format($final_total); ?></span>
            </div>
        </section>
    </main>

    <?php include("footer.php"); ?>

</body>
</html>
