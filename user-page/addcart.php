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

// 1. Delete Logic
if (isset($_GET['remove'])) {
    $remove_id = mysqli_real_escape_string($cn, $_GET['remove']);
    mysqli_query($cn, "DELETE FROM cart WHERE id = '$remove_id' AND user_email = '$user'");
    header("location:addcart.php");
    exit();
}

// 2. Auto-Update Qty Logic
if (isset($_POST['auto_update_qty'])) {
    $qty = mysqli_real_escape_string($cn, $_POST['new_qty']);
    $cart_id = mysqli_real_escape_string($cn, $_POST['cart_id']);
    mysqli_query($cn, "UPDATE cart SET qty = '$qty' WHERE id = '$cart_id'");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - Shivi's Stylevana</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="afterl-style.css">
    <style>
    :root { 
        --primary-color: #D9A299; 
        --bg-soft: #F0E4D3; 
        --dark: #2c3e50; 
        --accent: #f8ece4;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    body { 
        background: linear-gradient(135deg, #F0E4D3 0%, #fff5f2 100%); 
        font-family: 'Poppins', sans-serif; 
        min-height: 100vh;
        color: var(--dark);
    }

    .cart-container { 
        max-width: 1000px; 
        margin: 120px auto 50px; 
        padding: 30px; 
        background: rgba(255, 255, 255, 0.4); 
        backdrop-filter: blur(10px);
        border-radius: 30px;
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .cart-table { width: 100%; border-collapse: separate; border-spacing: 0 15px; }
    
    .cart-item-row { 
        background: #fff; 
        box-shadow: 0 10px 25px rgba(217, 162, 153, 0.15); 
        border-radius: 20px; 
        transition: transform 0.3s ease;
    }

    .cart-item-row td { padding: 20px; vertical-align: middle; }

    /* New Checkbox Styling */
    .item-checkbox {
        width: 20px; height: 20px; accent-color: var(--primary-color); cursor: pointer;
    }

    .img-container { position: relative; width: 100px; height: 100px; }
    .img-box { 
        width: 100%; height: 100%; overflow: hidden; border-radius: 18px; 
        border: 2px solid var(--accent); 
    }
    .img-box img { width: 100%; height: 100%; object-fit: cover; }

    .remove-icon-top {
        position: absolute; top: -8px; left: -8px;
        background: #ff7675; color: white; width: 25px; height: 25px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        text-decoration: none; font-size: 16px; z-index: 10; border: 2px solid #fff;
    }

    .individual-order-btn {
        background: var(--accent); color: var(--primary-color);
        padding: 8px 18px; border-radius: 10px; text-decoration: none;
        font-size: 0.85rem; font-weight: 700; transition: 0.3s;
    }
    .individual-order-btn:hover { background: var(--primary-color); color: white; }

    .qty-input { 
        width: 60px; padding: 8px; border: 2px solid var(--accent); border-radius: 10px; 
        text-align: center; font-weight: bold;
    }

    .price-text { font-weight: 800; color: var(--primary-color); font-size: 1.2rem; }

    .summary-footer { 
        margin-top: 40px; background: #fff; padding: 30px; border-radius: 25px; 
        display: flex; justify-content: space-between; align-items: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .combine-order-btn { 
        background: var(--primary-color); color: #fff; padding: 18px 40px; border: none;
        border-radius: 15px; font-weight: bold; font-size: 1.1rem; cursor: pointer;
    }
    
    .combine-order-btn:disabled { background: #ccc; cursor: not-allowed; }
</style>
</head>
<body>

    <?php include("header.php"); ?>

    <div class="cart-container">
        <h2 style="font-family: serif; color: var(--dark); margin-bottom: 20px;">My Shopping Bag</h2>

        <?php
        $select_cart = mysqli_query($cn, "SELECT c.*, s.productname, s.productprice, s.productphoto 
                                         FROM cart c JOIN shop s ON c.pid = s.pid 
                                         WHERE c.user_email = '$user'");
        
        if (mysqli_num_rows($select_cart) > 0) {
        ?>
        <form action="checkout.php" method="POST" id="cartForm">
            <table class="cart-table">
                <thead>
                    <tr style="text-align: left; color: #888; font-size: 0.85rem;">
                        <th style="padding-left: 20px;"><input type="checkbox" id="selectAll" checked class="item-checkbox"></th>
                        <th colspan="2">Product Details</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($select_cart)) { 
                        $price = $row['productprice'];
                    ?>
                    <tr class="cart-item-row">
                        <td width="50">
                            <input type="checkbox" name="selected_items[]" value="<?php echo $row['id']; ?>" 
                                   class="item-checkbox item-select" checked 
                                   data-price="<?php echo $price; ?>" 
                                   data-cartid="<?php echo $row['id']; ?>">
                        </td>
                        <td width="110">
                            <div class="img-container">
                                <a href="addcart.php?remove=<?php echo $row['id']; ?>" class="remove-icon-top">×</a>
                                <div class="img-box"><img src="<?php echo $row['productphoto']; ?>" alt="item"></div>
                            </div>
                        </td>
                        <td>
                            <h4 style="margin:0; font-size: 1rem;"><?php echo $row['productname']; ?></h4>
                            <p style="color:#888; font-size: 0.85rem;">₹<?php echo $price; ?></p>
                        </td>
                        <td width="100">
                            <input type="number" class="qty-input item-qty" 
                                   value="<?php echo $row['qty']; ?>" min="1" 
                                   onchange="updateCart(this.value, <?php echo $row['id']; ?>)">
                        </td>
                        <td class="price-text" width="130">₹<span class="row-total"><?php echo ($price * $row['qty']); ?></span></td>
                        <td align="right">
                            <a href="checkout.php?buy_pid=<?php echo $row['pid']; ?>&qty=<?php echo $row['qty']; ?>" 
                               class="individual-order-btn">Buy Now</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="summary-footer">
                <div>
                    <p style="color:#888; font-size: 0.9rem;">Total for Selected Items</p>
                    <span style="font-size: 2rem; font-weight: bold; color: var(--primary-color);">₹<span id="grandTotalDisplay">0</span></span>
                </div>
                <input type="hidden" name="checkout_from" value="cart">
                <button type="submit" name="proceed_to_checkout" id="placeOrderBtn" class="combine-order-btn">
                    Place Order Now <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>

        <?php 
        } else { 
            echo "<div style='text-align:center; padding:80px;'>Bag is empty!</div>"; 
        } 
        ?>
    </div>

    <script>
    // 1. Logic to calculate total of ONLY selected items
    function calculateTotal() {
        let grandTotal = 0;
        let selectedCount = 0;
        const rows = document.querySelectorAll('.cart-item-row');
        
        rows.forEach(row => {
            const checkbox = row.querySelector('.item-select');
            const qty = row.querySelector('.item-qty').value;
            const price = checkbox.getAttribute('data-price');
            const subtotalSpan = row.querySelector('.row-total');
            
            let rowTotal = price * qty;
            subtotalSpan.innerText = rowTotal; // Update individual subtotal text

            if (checkbox.checked) {
                grandTotal += rowTotal;
                selectedCount++;
            }
        });

        document.getElementById('grandTotalDisplay').innerText = grandTotal;
        document.getElementById('placeOrderBtn').disabled = (selectedCount === 0);
    }

    // 2. Select All Toggle
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.item-select').forEach(cb => {
            cb.checked = this.checked;
        });
        calculateTotal();
    });

    // 3. Update Cart via AJAX (Restored your exact logic)
    function updateCart(qty, id) {
        let formData = new FormData();
        formData.append('auto_update_qty', true);
        formData.append('new_qty', qty);
        formData.append('cart_id', id);

        fetch('addcart.php', {
            method: 'POST',
            body: formData
        }).then(() => {
            calculateTotal(); // Refresh total locally without full reload if possible
            location.reload(); 
        });
    }

    // Run calculation on page load
    window.onload = calculateTotal;
    // Add event listeners to checkboxes
    document.querySelectorAll('.item-select').forEach(cb => {
        cb.addEventListener('change', calculateTotal);
    });
    </script>

</body>
</html>
