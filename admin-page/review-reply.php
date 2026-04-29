<?php 
include("function.php"); 
session_start();
// Admin protection
if(!isset($_SESSION["admin"])) { header("location:adminlogin.php"); exit(); } 
$cn = make_connection();

// URL se Review ID lena
if(!isset($_GET['rid']))
     { header("location:review.php"); exit(); }
$rid =  $_GET['rid'];

// Reply update logic
if(isset($_POST['btn_reply'])) {
    $msg = mysqli_real_escape_string($cn, $_POST['reply_msg']);
    $today = date("Y-m-d");
    mysqli_query($cn, "UPDATE reviews SET admin_reply='$msg', reply_date='$today' WHERE rid='$rid'");
    header("location:review.php"); // Updated to match your main review file name
}

// Review data fetch karna
$res = mysqli_query($cn, "SELECT * FROM reviews WHERE rid='$rid'");
$data = mysqli_fetch_array($res);
if(!$data) { echo "Review not found!"; exit(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stylevana | Reply to Review</title>
     <link rel="stylesheet" href="review-reply.css">
</head>
<body>

    <?php include("header.php"); ?>

    <div class="admin-wrapper">
        
        <?php include("sidebar.php"); ?>

        <main class="main-content">
            <div class="reply-card">
                <div class="customer-info">
                    <h2 style="font-family:'Playfair Display'; margin-bottom: 5px;">Response to Review</h2>
                    <p style="color:var(--rose); font-size: 0.85rem; font-weight: 600;">Customer: <?php echo $data['user_email']; ?></p>
                </div>

                <label>Customer's Comment</label>
                <div class="review-text">
                    <?php echo $data['comment']; ?>
                </div>

                <form method="POST">
                    <label>Your Response</label>
                    <textarea name="reply_msg" placeholder="Type your professional reply here..." required><?php echo $data['admin_reply']; ?></textarea>
                    
                    <div class="btn-group">
                        <button type="submit" name="btn_reply">Send Reply</button>
                        <a href="review.php" class="cancel-link">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <?php include("footer.php"); ?>

</body>
</html>