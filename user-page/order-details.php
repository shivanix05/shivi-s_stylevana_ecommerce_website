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

// --- REVIEW LOGIC WITH PHOTO SAVE ---
if (isset($_POST['submit_review'])) {
    $rating = mysqli_real_escape_string($cn, $_POST['rating']);
    $comment = mysqli_real_escape_string($cn, $_POST['comment']);
    $pid = $order['pid']; 
    $review_photo = ""; // Default empty

    // File Upload handling
    if(isset($_FILES['review_photo']) && $_FILES['review_photo']['error'] == 0) {
        $target_dir = "uploads/reviews/"; 
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_ext = pathinfo($_FILES["review_photo"]["name"], PATHINFO_EXTENSION);
        // Creating unique name: time + product_id
        $db_path = $target_dir . time() . "_" . $pid . "." . $file_ext;
        
        if(move_uploaded_file($_FILES["review_photo"]["tmp_name"], $db_path)) {
            $review_photo = $db_path; // Path to save in DB
        }
    }

    $check_review = mysqli_query($cn, "SELECT rid FROM reviews WHERE pid = '$pid' AND user_email = '$user'");
    
    if (mysqli_num_rows($check_review) > 0) {
        echo "<script>alert('You have already reviewed this product!');</script>";
    } else {
        // SQL query updated with rev_photo column
        $ins_review = "INSERT INTO reviews (pid, user_email, rating, comment, rev_photo, review_date) 
                       VALUES ('$pid', '$user', '$rating', '$comment', '$review_photo', NOW())";
        
        if (mysqli_query($cn, $ins_review)) {
            echo "<script>alert('Review posted successfully!'); window.location.href='order-details.php?id=$order_id';</script>";
        } else {
            echo "Error: " . mysqli_error($cn);
        }
    }
}

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
          <link rel="stylesheet" href="order.css">

    <style>
      
    </style>
</head>
<body>

    <?php include("header.php"); ?>

    <div class="order-details-container">
        <div class="details-card">
            <h2 style="font-family: 'Playfair Display', serif; margin-bottom: 10px;">Order Details</h2>

            <!-- Status Line -->
            <div class="status-line-container">
                <?php if($s != 'Cancelled'): ?>
                    <div class="status-item active"><span class="indicator"></span><p>PLACED</p></div>
                    <div class="status-item <?php echo ($s=='Shipped' || $s=='Delivered')?'active':''; ?>"><span class="indicator"></span><p>SHIPPED</p></div>
                    <div class="status-item <?php echo ($s=='Delivered')?'active':''; ?>"><span class="indicator"></span><p>DELIVERED</p></div>
                <?php else: ?>
                    <div class="status-item active"><span class="indicator"></span><p>PLACED</p></div>
                    <div class="status-item cancelled"><span class="indicator"></span><p>CANCELLED</p></div>
                <?php endif; ?>
            </div>

            <div class="summary-grid <?php echo ($s=='Cancelled')?'cancelled-overlay':''; ?>">
                <!-- Product Info -->
                <div class="prod-display">
                    <img src="<?php echo $order['productphoto']; ?>" onerror="this.src='admin/<?php echo $order['productphoto']; ?>';">
                    <div>
                        <h3 style="color: #333;">Order ID: #STV-<?php echo $order['order_id']; ?></h3>
                        <p style="color: #D9A299; font-weight: bold; font-size: 1.4rem;">₹<?php echo number_format($order['productprice']); ?></p>
                        <p>Quantity: <strong><?php echo $order['qty']; ?></strong></p>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="form-data-box">
                    <h4><i class="fas fa-truck"></i> Delivery Details</h4>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['adddress']); ?></p>
                    <p><strong>Payment:</strong> <?php echo $order['payment_method']; ?></p>
                </div>
            </div>

            <!-- Review Section -->
            <div class="review-section">
                <h4 style="color: #D9A299; margin-bottom: 15px;">Rate your experience</h4>
                <?php
                $p_id = $order['pid'];
                $chk = mysqli_query($cn, "SELECT * FROM reviews WHERE pid = '$p_id' AND user_email = '$user'");
                if(mysqli_num_rows($chk) == 0):
                ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="rating-stars">
                        <input type="radio" name="rating" value="5" id="5" required><label for="5">★</label>
                        <input type="radio" name="rating" value="4" id="4"><label for="4">★</label>
                        <input type="radio" name="rating" value="3" id="3"><label for="3">★</label>
                        <input type="radio" name="rating" value="2" id="2"><label for="2">★</label>
                        <input type="radio" name="rating" value="1" id="1"><label for="1">★</label>
                    </div>
                    <textarea name="comment" class="rev-input" rows="3" placeholder="Share your feedback..." required></textarea>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="font-size: 0.85rem; color: #666; display: block; margin-bottom: 5px;">Upload Product Photo:</label>
                        <input type="file" name="review_photo" accept="image/*">
                    </div>

                    <button type="submit" name="submit_review" class="rev-btn">Post Review</button>
                </form>
                <?php else: 
                    $rev_data = mysqli_fetch_assoc($chk); 
                ?>
                    <div style="background: #fdfaf9; padding: 15px; border-radius: 10px; border: 1px solid #f9ecea;">
                        <p style="color: #f39c12; margin-bottom: 5px;">
                            <?php for($i=1; $i<=$rev_data['rating']; $i++) echo "★"; ?>
                        </p>
                        <p style="font-size: 0.9rem; color: #555;">"<?php echo htmlspecialchars($rev_data['comment']); ?>"</p>
                        
                        <?php if(!empty($rev_data['rev_photo'])): ?>
                            <img src="<?php echo $rev_data['rev_photo']; ?>" class="review-img-preview">
                        <?php endif; ?>
                        
                        <p style="font-size: 0.75rem; color: #888; margin-top: 10px;">Reviewed on <?php echo date('d M, Y', strtotime($rev_data['review_date'])); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <div style="margin-top: 40px; text-align: center;">
                <a href="myorder.php" style="text-decoration: none; color: #D9A299; font-weight: 600;">
                    <i class="fas fa-chevron-left"></i> Back to My Orders
                </a>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>

</body>
</html>