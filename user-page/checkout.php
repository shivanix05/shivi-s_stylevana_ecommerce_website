<?php 
require_once __DIR__ . "/config.php"; 
session_start();

if (!$cn) die("Connection failed: Please check your config.php settings.");

if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();    
}

$user = $_SESSION['user'];

// Fetch user details
$user_info_query = mysqli_query($cn, "SELECT * FROM userdetail WHERE gmail = '$user'");
$user_data       = mysqli_fetch_assoc($user_info_query);

// ── Identify source ──────────────────────────────────────────────────────────
$buy_pid  = isset($_GET['buy_pid'])  ? $_GET['buy_pid']  : null;
$buy_qty  = isset($_GET['qty'])      ? (int)$_GET['qty'] : 1;
$is_cart  = (isset($_POST['checkout_from']) && $_POST['checkout_from'] === 'cart');

$order_items = [];
$final_total = 0;
$order_type  = "";

// ── DATA FETCHING ────────────────────────────────────────────────────────────

if ($is_cart) {
    /*
     * BUG FIX: Fetch ONLY the cart rows whose IDs were sent via selected_items[].
     * Previously this fetched ALL cart items for the user — now it filters by
     * the checked IDs that addcart.php sends via the form.
     */
    $raw_ids = isset($_POST['selected_items']) ? $_POST['selected_items'] : [];

    if (empty($raw_ids)) {
        // Nothing selected — send back
        header("location:addcart.php?error=no_items_selected");
        exit();
    }

    // Sanitise: make sure every value is a positive integer
    $safe_ids = array_filter(array_map('intval', $raw_ids), fn($v) => $v > 0);

    if (empty($safe_ids)) {
        header("location:addcart.php");
        exit();
    }

    $ids_str = implode(',', $safe_ids);

    $cart_query = "SELECT c.*, s.productname, s.productprice, s.productphoto, s.stock_qty 
                   FROM cart c JOIN shop s ON c.pid = s.pid 
                   WHERE c.id IN ($ids_str) AND c.user_email = '$user'";

    $cart_res = mysqli_query($cn, $cart_query);
    while ($row = mysqli_fetch_assoc($cart_res)) {
        if ($row['stock_qty'] <= 0) {
            echo "<script>alert('\"" . addslashes($row['productname']) . "\" is out of stock!'); window.location.href='addcart.php';</script>";
            exit();
        }
        $order_items[] = $row;
        $final_total  += ($row['productprice'] * $row['qty']);
    }
    $order_type = "0";

} elseif ($buy_pid) {
    // Single product "Buy Now"
    $buy_pid    = mysqli_real_escape_string($cn, $buy_pid);
    $single_res = mysqli_query($cn, "SELECT * FROM shop WHERE pid = '$buy_pid'");
    $row        = mysqli_fetch_assoc($single_res);

    if ($row) {
        if ($row['stock_qty'] <= 0) {
            echo "<script>alert('This product is out of stock!'); window.location.href='after-login.php';</script>";
            exit();
        }
        $row['qty']    = $buy_qty;
        $order_items[] = $row;
        $final_total   = $row['productprice'] * $buy_qty;
        $order_type    = $buy_pid;
    }
}

// ── ONLINE PAYMENT REDIRECT ───────────────────────────────────────────────────
if (isset($_POST['place_order']) && $_POST['payment_method'] === 'Online') {
    $product_photo = isset($order_items[0]['productphoto']) ? $order_items[0]['productphoto'] : '';
    $_SESSION['temp_order'] = [
        'name'         => $_POST['name'],
        'mobile'       => $_POST['mobile'],
        'address'      => $_POST['address'],
        'total_amount' => $_POST['total_amount'],
        'pid'          => $_POST['pid'],
        'qty'          => $_POST['qty_hidden'],
        'photo'        => $product_photo,
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
    <title>Checkout — Stylevana</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <style>
       
    </style>
        <link rel="stylesheet" href="checkout.css">

</head>
<body>

<?php include("header.php"); ?>

<div class="co-page">

    <div class="co-head">
        <div class="breadcrumb">
            <a href="addcart.php" style="text-decoration:none;color:inherit;">Bag</a>
            <i class="fas fa-chevron-right"></i>
            <span>Checkout</span>
        </div>
        <h1 class="co-title">Secure <span>Checkout</span></h1>
        <p class="co-sub">All transactions are encrypted and secure</p>
    </div>

    <?php if (!empty($order_items)): ?>

    <div class="co-grid">

        <!-- ── LEFT: Shipping + Payment form ── -->
        <div class="form-card">

            <form id="checkoutForm" action="myorder.php" method="POST">
                <input type="hidden" name="pid"          value="<?php echo htmlspecialchars($order_type); ?>">
                <input type="hidden" name="total_amount" value="<?php echo $final_total; ?>">
                <input type="hidden" name="qty_hidden"   value="<?php echo $buy_qty; ?>">

                <!-- Shipping -->
                <div class="card-section">
                    <div class="section-label">Shipping details</div>
                    <div class="fg">
                        <label>Full Name</label>
                        <div class="input-wrap">
                            <i class="fas fa-user"></i>
                            <input type="text" name="name"
                                   value="<?php echo htmlspecialchars($user_data['name'] ?? ''); ?>"
                                   placeholder="Enter your full name" required>
                        </div>
                    </div>
                    <div class="fg">
                        <label>Mobile Number</label>
                        <div class="input-wrap">
                            <i class="fas fa-phone"></i>
                            <input type="tel" name="mobile"
                                   value="<?php echo htmlspecialchars($user_data['mobilenumber'] ?? ''); ?>"
                                   placeholder="+91 XXXXX XXXXX"
                                   pattern="\+91[0-9]{10}" required>
                        </div>
                    </div>
                    <div class="fg">
                        <label>Delivery Address</label>
                        <textarea name="address" rows="3"
                                  placeholder="House no., street, area, city, pincode"
                                  required><?php echo htmlspecialchars($user_data['address'] ?? ''); ?></textarea>
                    </div>
                </div>

                <!-- Payment -->
                <div class="card-section">
                    <div class="section-label">Payment method</div>
                    <div class="pay-options">
                        <label class="pay-opt">
                            <input type="radio" name="payment_method" value="COD"
                                   checked onclick="this.form.action='myorder.php'">
                            <div class="pay-opt-info">
                                <div class="pay-opt-name">Cash on Delivery</div>
                                <div class="pay-opt-desc">Pay when your order arrives</div>
                            </div>
                            <i class="fas fa-money-bill-wave pay-opt-icon"></i>
                        </label>
                        <label class="pay-opt">
                            <input type="radio" name="payment_method" value="Online"
                                   onclick="this.form.action=''">
                            <div class="pay-opt-info">
                                <div class="pay-opt-name">Online Payment</div>
                                <div class="pay-opt-desc">UPI · Card · Net banking</div>
                            </div>
                            <i class="fab fa-cc-visa pay-opt-icon"></i>
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <div class="card-section">
                    <button type="submit" name="place_order" class="confirm-btn">
                        <i class="fas fa-lock" style="font-size:11px;"></i>
                        Confirm &amp; Place Order
                    </button>
                    <div class="safety-row">
                        <i class="fas fa-shield-alt" style="font-size:11px; color:var(--green);"></i>
                        100% secure · Free returns · Easy cancellation
                    </div>
                </div>
            </form>

        </div><!-- /.form-card -->

        <!-- ── RIGHT: Order summary ── -->
        <div class="summary-card">
            <div class="s-title">Your Order</div>

            <?php foreach ($order_items as $item):
                $item_img = !empty($item['productphoto'])
                            ? '../admin-page/' . htmlspecialchars($item['productphoto'])
                            : '';
                $item_sub = $item['productprice'] * $item['qty'];
            ?>
            <div class="order-item">
                <?php if ($item_img): ?>
                    <img src="<?php echo $item_img; ?>"
                         class="oi-img"
                         alt="<?php echo htmlspecialchars($item['productname']); ?>">
                <?php else: ?>
                    <div class="oi-img-ph"><i class="fas fa-image"></i></div>
                <?php endif; ?>
                <div style="flex:1; min-width:0;">
                    <div class="oi-name"><?php echo htmlspecialchars($item['productname']); ?></div>
                    <div class="oi-qty">Qty: <?php echo $item['qty']; ?> unit<?php echo $item['qty'] > 1 ? 's' : ''; ?></div>
                </div>
                <div class="oi-price">₹<?php echo number_format($item_sub); ?></div>
            </div>
            <?php endforeach; ?>

            <div class="s-divider"></div>

            <div class="s-row">
                <span>Subtotal</span>
                <span class="val">₹<?php echo number_format($final_total); ?></span>
            </div>
            <div class="s-row">
                <span>Delivery</span>
                <span class="s-free">Free</span>
            </div>
            <div class="s-row">
                <span>Taxes & fees</span>
                <span class="val">Included</span>
            </div>

            <div class="s-total-row">
                <div class="s-total-label">Total</div>
                <div class="s-total-amt">₹<?php echo number_format($final_total); ?></div>
            </div>
        </div>

    </div><!-- /.co-grid -->

    <?php else: ?>

    <div class="empty-co">
        <h3>Nothing to checkout</h3>
        <p>Please select items from your bag first</p>
        <a href="addcart.php" class="btn-back">
            <i class="fas fa-arrow-left" style="font-size:12px;"></i> Back to Bag
        </a>
    </div>

    <?php endif; ?>

</div><!-- /.co-page -->

<?php include("footer.php"); ?>
</body>
</html>