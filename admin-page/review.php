<?php 
include("function.php"); 
session_start();
if(!isset($_SESSION["admin"])) { header("location:adminlogin.php"); exit(); } 
$cn = make_connection();

// Tumhare table structure ke hisaab se JOIN query
$str = "SELECT r.*, s.productname FROM reviews r 
        JOIN shop s ON r.pid = s.pid 
        ORDER BY r.rid DESC";
$rs = mysqli_query($cn, $str);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stylevana | Manage Reviews</title>
       <link rel="stylesheet" href="review.css">

</head>
<body>
    <?php include("header.php"); ?> <div class="admin-wrapper">
        <?php include("sidebar.php"); ?>

        <main class="main-content">
            <h1 style="font-family:'Playfair Display'; margin-bottom:30px; font-size:2.2rem;">Customer Reviews</h1>
            
            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Customer Email</th>
                            <th>Rating</th>
                            <th>Review & Admin Reply</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_array($rs)) { ?>
                        <tr>
                            <td><b><?php echo $row['productname']; ?></b></td>
                            <td><small><?php echo $row['user_email']; ?></small></td>
                            <td class="stars">
                                <?php for($i=1; $i<=5; $i++) {
                                    echo ($i <= $row['rating']) ? "★" : "☆";
                                } ?>
                            </td>
                            <td>
                                <span><?php echo $row['comment']; ?></span>
                                <?php if(!empty($row['admin_reply'])) { ?>
                                    <span class="reply-box"><b>Reply:</b> <?php echo $row['admin_reply']; ?></span>
                                <?php } ?>
                            </td>
                            <td><small style="color:#999;"><?php echo date("d M Y", strtotime($row['review_date'])); ?></small></td>
                            <td>
                                <a href="review-reply.php?rid=<?php echo $row['rid']; ?>" class="btn-reply">Reply</a>
                                <a href="delete-review.php?rid=<?php echo $row['rid']; ?>" class="btn-delete" onclick="return confirm('Delete this review?')">Delete</a>
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