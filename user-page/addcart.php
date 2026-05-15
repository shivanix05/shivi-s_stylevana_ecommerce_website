<?php 
require_once __DIR__ . '/config.php';
session_start();

if (!isset($_SESSION["user"])) {
    header("location:login.php");
    exit();
}

$user = $_SESSION['user'];

// --- INSERT LOGIC ---
if (isset($_GET['pid'])) {
    $pid = mysqli_real_escape_string($cn, $_GET['pid']);
    $check = mysqli_query($cn, "SELECT * FROM cart WHERE pid = '$pid' AND user_email = '$user'");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($cn, "UPDATE cart SET qty = qty + 1 WHERE pid = '$pid' AND user_email = '$user'");
    } else {
        mysqli_query($cn, "INSERT INTO cart (pid, user_email, qty) VALUES ('$pid', '$user', 1)");
    }
    header("location:addcart.php");
    exit();
}

// Delete Logic
if (isset($_GET['remove'])) {
    $remove_id = mysqli_real_escape_string($cn, $_GET['remove']);
    mysqli_query($cn, "DELETE FROM cart WHERE id = '$remove_id' AND user_email = '$user'");
    header("location:addcart.php");
    exit();
}

// Auto-Update Qty Logic (AJAX)
if (isset($_POST['auto_update_qty'])) {
    $qty     = (int) $_POST['new_qty'];
    $cart_id = mysqli_real_escape_string($cn, $_POST['cart_id']);
    if ($qty < 1) $qty = 1;
    mysqli_query($cn, "UPDATE cart SET qty = '$qty' WHERE id = '$cart_id' AND user_email = '$user'");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bag — Stylevana</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="afterl-style.css">
    <link rel="stylesheet" href="addcart.css">
    <style>
       
    </style>
</head>
<body>

<?php include("header.php"); ?>

<div class="pg">

    <?php
    // Fetch all cart items — store in array so we can loop twice
    $sel = mysqli_query($cn,
        "SELECT c.*, s.productname, s.productprice, s.productphoto, s.pid AS spid
         FROM cart c JOIN shop s ON c.pid = s.pid
         WHERE c.user_email = '$user'
         ORDER BY c.id DESC");
    $cart_rows  = [];
    while ($r = mysqli_fetch_assoc($sel)) $cart_rows[] = $r;
    $total_items = count($cart_rows);
    ?>

    <div class="pg-head">
        <h1 class="pg-title">My <span>Bag</span></h1>
        <span class="pg-count"><?php echo $total_items; ?> item<?php echo $total_items != 1 ? 's' : ''; ?></span>
    </div>

    <?php if ($total_items > 0): ?>

    <div class="cart-grid">

        <!-- ══ LEFT: FORM (selected items only go to checkout) ══ -->
        <div class="left-panel">
            <!--
                This form sends selected_items[] (cart row IDs) to checkout.php.
                checkout.php MUST use:
                  $sel_ids = array_map('intval', $_POST['selected_items'] ?? []);
                  WHERE c.id IN (implode(',', $sel_ids)) AND c.user_email = '$user'
                instead of fetching ALL cart items.
            -->
            <form action="checkout.php" method="POST" id="cartForm">
                <input type="hidden" name="checkout_from" value="cart">

                <!-- Select-all bar -->
                <div class="sel-bar">
                    <label class="sel-bar-left">
                        <input type="checkbox" id="selectAll" class="cbox" checked>
                        <span>Select all</span>
                    </label>
                    <span class="sel-bar-right" id="selCount"><?php echo $total_items; ?> selected</span>
                </div>

                <!-- Items -->
                <div class="items-wrap">
                    <?php foreach ($cart_rows as $row):
                        $price    = $row['productprice'];
                        $subtotal = $price * $row['qty'];
                        $pid      = $row['spid'];
                        $img_src  = !empty($row['productphoto'])
                                    ? '../admin-page/' . htmlspecialchars($row['productphoto'])
                                    : '';
                    ?>
                    <div class="c-item" data-id="<?php echo $row['id']; ?>">

                        <!-- Checkbox carries cart row id (not pid) -->
                        <input type="checkbox"
                               name="selected_items[]"
                               value="<?php echo $row['id']; ?>"
                               class="cbox item-select"
                               checked
                               data-price="<?php echo $price; ?>"
                               data-pid="<?php echo $pid; ?>"
                               data-qty="<?php echo $row['qty']; ?>">

                        <!--
                            PRODUCT IMAGE — clicking opens order.php (product view).
                            This is an <a> tag INSIDE the form but it is a link,
                            NOT a submit button, so it will navigate, not submit.
                            The "×" remove link uses event.stopPropagation() to
                            prevent the parent <a> from triggering.
                        -->
                        <a href="order.php?pid=<?php echo $pid; ?>"
                           class="c-img-link"
                           title="View product">
                            <?php if ($img_src): ?>
                                <img src="<?php echo $img_src; ?>"
                                     alt="<?php echo htmlspecialchars($row['productname']); ?>">
                            <?php else: ?>
                                <div style="width:100%;height:100%;background:var(--rose-pale);display:flex;align-items:center;justify-content:center;color:var(--rose-2);font-size:22px;">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                            <div class="img-overlay">
                                <span><i class="fas fa-eye" style="font-size:10px;"></i> VIEW</span>
                            </div>
                            <!-- Remove corner button — stops propagation to parent <a> -->
                            <a href="addcart.php?remove=<?php echo $row['id']; ?>"
                               class="c-remove"
                               title="Remove"
                               onclick="event.stopPropagation(); return confirm('Remove this item from bag?');">
                                <i class="fas fa-times"></i>
                            </a>
                        </a>

                        <!-- Name + view link -->
                        <div class="c-info">
                            <div class="c-name"><?php echo htmlspecialchars($row['productname']); ?></div>
                            <div class="c-unit">₹<?php echo number_format($price); ?> / piece</div>
                            <a href="order.php?pid=<?php echo $pid; ?>" class="c-view-link">
                                View product <i class="fas fa-arrow-right" style="font-size:9px;"></i>
                            </a>
                        </div>

                        <!-- Qty stepper -->
                        <div class="stepper">
                            <button type="button" class="s-btn qty-minus" aria-label="Decrease">−</button>
                            <input type="number"
                                   class="s-num item-qty"
                                   value="<?php echo $row['qty']; ?>"
                                   min="1" max="99"
                                   onchange="updateCart(this.value, <?php echo $row['id']; ?>)">
                            <button type="button" class="s-btn qty-plus" aria-label="Increase">+</button>
                        </div>

                        <!-- Subtotal -->
                        <div class="c-sub">₹<span class="row-total"><?php echo number_format($subtotal); ?></span></div>

                    </div><!-- /.c-item -->
                    <?php endforeach; ?>
                </div><!-- /.items-wrap -->

                <!-- Hidden submit — triggered by Place Order button -->
                <button type="submit" name="proceed_to_checkout" id="formTrigger" style="display:none;"></button>
            </form>
        </div>

        <!-- ══ RIGHT: SUMMARY + BUY-NOW (outside form) ══ -->
        <div class="right-panel">

            <!-- Dark summary card -->
            <div class="summary-card">
                <div class="s-card-title">
                    Order Summary
                    <span class="s-badge" id="selBadge"><?php echo $total_items; ?> items</span>
                </div>
                <div class="s-row">
                    <span>Items subtotal</span>
                    <span class="val">₹<span id="subtotalDisp">0</span></span>
                </div>
                <div class="s-row">
                    <span>Delivery</span>
                    <span class="s-free">Free</span>
                </div>
                <div class="s-row">
                    <span>Discount</span>
                    <span class="val">—</span>
                </div>
                <div class="s-divider"></div>
                <div class="s-total-row">
                    <div class="s-total-label">Total</div>
                    <div class="s-total-amt">₹<span id="grandDisp">0</span></div>
                </div>

                <!--
                    This button triggers the form's hidden submit button.
                    It is OUTSIDE the form element in the DOM (right panel),
                    so it uses onclick to programmatically submit.
                    Only checked items will be in selected_items[].
                -->
                <button type="button" class="place-btn" id="placeBtn" disabled
                        onclick="document.getElementById('formTrigger').click()">
                    Place Order &nbsp;<i class="fas fa-arrow-right"></i>
                </button>
                <p class="secure-txt">
                    <i class="fas fa-lock" style="font-size:9px;"></i>
                    Secure · Free returns · Easy exchange
                </p>
            </div>

            <!-- Buy individually card — plain GET <a> links, NO form relation -->
            <div class="buynow-card">
                <div class="bn-label">Buy individually</div>
                <?php foreach ($cart_rows as $r2):
                    $bn_img = !empty($r2['productphoto'])
                              ? '../admin-page/' . htmlspecialchars($r2['productphoto'])
                              : '';
                ?>
                <!--
                    Direct GET link to checkout.php?buy_pid=...
                    This navigates directly — never submits the cart form.
                -->
                <a href="checkout.php?buy_pid=<?php echo $r2['spid']; ?>&qty=<?php echo $r2['qty']; ?>"
                   class="bn-link">
                    <?php if ($bn_img): ?>
                        <img src="<?php echo $bn_img; ?>"
                             class="bn-thumb"
                             alt="<?php echo htmlspecialchars($r2['productname']); ?>">
                    <?php else: ?>
                        <div class="bn-thumb" style="display:flex;align-items:center;justify-content:center;color:var(--dust);">
                            <i class="fas fa-image" style="font-size:12px;"></i>
                        </div>
                    <?php endif; ?>
                    <span class="bn-name"><?php echo htmlspecialchars($r2['productname']); ?></span>
                    <i class="fas fa-bolt bn-arrow"></i>
                </a>
                <?php endforeach; ?>
            </div>

        </div><!-- /.right-panel -->
    </div><!-- /.cart-grid -->

    <?php else: ?>
    <div class="cart-grid">
        <div class="empty-wrap">
            <div class="empty-icon"><i class="fas fa-shopping-bag"></i></div>
            <h3>Your bag is empty</h3>
            <p>Discover our collection and add something you love</p>
            <a href="shop.php" class="btn-shop">
                <i class="fas fa-store" style="font-size:12px;"></i> Explore Collection
            </a>
        </div>
    </div>
    <?php endif; ?>

</div><!-- /.pg -->

<!-- Toast -->
<div class="toast" id="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toastMsg">Quantity updated</span>
</div>

<script>
/* CALCULATE TOTALS — only for checked items */
function calcTotal() {
    let total = 0, count = 0;
    document.querySelectorAll('.c-item').forEach(row => {
        const cb    = row.querySelector('.item-select');
        const qty   = parseInt(row.querySelector('.item-qty').value) || 1;
        const price = parseFloat(cb.dataset.price) || 0;
        const sub   = price * qty;
        row.querySelector('.row-total').innerText = sub.toLocaleString('en-IN');
        cb.dataset.qty = qty;
        if (cb.checked) { total += sub; count++; }
    });
    const fmt = n => n.toLocaleString('en-IN');
    document.getElementById('grandDisp').innerText    = fmt(total);
    document.getElementById('subtotalDisp').innerText = fmt(total);
    document.getElementById('selBadge').innerText     = count + ' item' + (count !== 1 ? 's' : '');
    document.getElementById('selCount').innerText     = count + ' selected';
    document.getElementById('placeBtn').disabled      = count === 0;
}

/* SELECT ALL */
document.getElementById('selectAll').addEventListener('change', function () {
    document.querySelectorAll('.item-select').forEach(cb => cb.checked = this.checked);
    calcTotal();
});
document.querySelectorAll('.item-select').forEach(cb => {
    cb.addEventListener('change', () => {
        const total   = document.querySelectorAll('.item-select').length;
        const checked = document.querySelectorAll('.item-select:checked').length;
        document.getElementById('selectAll').checked = total === checked;
        calcTotal();
    });
});

/* QTY STEPPER */
document.querySelectorAll('.qty-minus').forEach(btn => {
    btn.addEventListener('click', function () {
        const inp = this.parentElement.querySelector('.s-num');
        if (parseInt(inp.value) > 1) {
            inp.value--;
            updateCart(inp.value, this.closest('.c-item').dataset.id);
        }
    });
});
document.querySelectorAll('.qty-plus').forEach(btn => {
    btn.addEventListener('click', function () {
        const inp = this.parentElement.querySelector('.s-num');
        inp.value++;
        updateCart(inp.value, this.closest('.c-item').dataset.id);
    });
});

/* AJAX QTY UPDATE */
function updateCart(qty, id) {
    calcTotal();
    const fd = new FormData();
    fd.append('auto_update_qty', 1);
    fd.append('new_qty', qty);
    fd.append('cart_id', id);
    fetch('addcart.php', { method:'POST', body:fd })
        .then(r => { if (r.ok) showToast('Quantity updated'); })
        .catch(() => { showToast('Error — refreshing…'); setTimeout(() => location.reload(), 1200); });
}

/* TOAST */
function showToast(msg) {
    const t = document.getElementById('toast');
    document.getElementById('toastMsg').innerText = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2600);
}

window.addEventListener('DOMContentLoaded', calcTotal);
</script>

</body>
</html>