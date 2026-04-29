<?php 
include("function.php"); 
session_start();

if(!isset($_SESSION["admin"])) { 
    header("location:adminlogin.php"); 
    exit();
} 

$cn = make_connection();

// Stats Queries
$user_count = mysqli_fetch_array(mysqli_query($cn, "SELECT COUNT(*) FROM userdetail"))[0];
$prod_count = mysqli_fetch_array(mysqli_query($cn, "SELECT COUNT(*) FROM shop"))[0];
$order_count = mysqli_fetch_array(mysqli_query($cn, "SELECT COUNT(*) FROM orders"))[0];
$rev_count = mysqli_fetch_array(mysqli_query($cn, "SELECT COUNT(*) FROM reviews"))[0];
$rating_res = mysqli_fetch_array(mysqli_query($cn, "SELECT AVG(rating) FROM reviews"));
$avg_rating = ($rating_res[0] > 0) ? number_format($rating_res[0], 1) : "0.0";
$recent_orders = mysqli_query($cn, "SELECT * FROM orders ORDER BY order_id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stylevana Admin | Dashboard</title>
    <link rel="stylesheet" href="admindashboardstyle.css" />
</head>
<body>

    <?php include("header.php"); ?>

    <div class="admin-wrapper">
        <?php include("sidebar.php"); ?>

        <main class="main-content">
            <div class="welcome-header">
                <h1>Hello, <?php echo $_SESSION["admin"]; ?>!</h1>
                <p>Date: <?php echo date("d M, Y"); ?> | Welcome to your Stylevana dashboard.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-shopping-bag"></i>
                    <h2><?php echo $order_count; ?></h2>
                    <span>Total Orders</span>
                </div>
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <h2><?php echo $user_count; ?></h2>
                    <span>Total Users</span>
                </div>
                <div class="stat-card">
                    <i class="fas fa-star"></i>
                    <h2><?php echo $avg_rating; ?></h2>
                    <span>Store Rating</span>
                </div>
                <div class="stat-card">
                    <i class="fas fa-comment-dots"></i>
                    <h2><?php echo $rev_count; ?></h2>
                    <span>Reviews</span>
                </div>
            </div>

            <div class="activity-section">
                <h3>Latest Shipments</h3><br>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_array($recent_orders)) { ?>
                        <tr>
                            <td style="font-weight:600; color:var(--rose);">#<?php echo $row['order_id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td>₹<?php echo number_format($row['productprice']); ?></td>
                            <td><span class="status-pill"><?php echo $row['status']; ?></span></td>
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