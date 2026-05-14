<?php 
include("function.php");
session_start();

// Admin protection
if(!isset($_SESSION["admin"])) { 
    header("location:adminlogin.php"); 
    exit(); 
} 

$cn = make_connection();

// ID fetch karna URL se
if(!isset($_GET["id"])) { 
    header("location:user-records.php");
    exit(); 
}

$sno = mysqli_real_escape_string($cn, $_GET["id"]);

// 1. User ki basic details fetch karna
$res = mysqli_query($cn, "SELECT * FROM userdetail WHERE sno='$sno'");
$user = mysqli_fetch_array($res);

if(!$user) { 
    echo "<h2 style='text-align:center; margin-top:50px;'>Customer record not found!</h2>"; 
    exit(); 
}

// 2. User Update History (Audit Log)
$history_res = mysqli_query($cn, "SELECT * FROM user_update_history WHERE user_sno='$sno' ORDER BY update_time DESC");

// 3. User ki Order History
$u_gmail = $user['gmail'];
$order_res = mysqli_query($cn, "SELECT * FROM orders WHERE user_email='$u_gmail' ORDER BY order_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Profile | <?php echo $user['name']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="user-detail.css">

</head>
<body>

    <?php include("header.php"); ?>

    <div class="admin-wrapper">
        <?php include("sidebar.php"); ?>

        <main class="main-content">
            <a href="user-record.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to User Records</a>

            <div class="grid-layout">
                <div class="card" style="text-align: center;">
                    <?php 
                        $photo = $user['userphoto'];
                        $img = (!empty($photo)) ? "uploads/".$photo : "https://cdn-icons-png.flaticon.com/512/149/149071.png";
                    ?>
                    <img src="<?php echo $img; ?>" class="user-big-photo" onerror="this.src='https://cdn-icons-png.flaticon.com/512/149/149071.png'">
                    <h2 style="font-family:'Playfair Display';"><?php echo $user['name']; ?></h2>
                    <p style="color:var(--rose); font-weight: 600; font-size: 0.8rem; margin-bottom: 25px;">Customer Profile</p>
                    
                    <div class="info-row"><span class="info-label">Email</span><span class="info-value"><?php echo $user['gmail']; ?></span></div>
                    <div class="info-row"><span class="info-label">Password</span><span class="info-value"><?php echo $user['password']; ?></span></div>
                    
                    <div class="info-row"><span class="info-label">Mobile</span><span class="info-value">+91 <?php echo $user['mobilenumber']; ?></span></div>
                    <div class="info-row"><span class="info-label">Full Address</span><span class="info-value"><?php echo (!empty($user['address'])) ? $user['address'] : 'N/A'; ?></span></div>
                    <div class="info-row"><span class="info-label">City</span><span class="info-value"><?php echo (!empty($user['city'])) ? $user['city'] : 'N/A'; ?></span></div>
                    <div class="info-row"><span class="info-label">State</span><span class="info-value"><?php echo (!empty($user['state'])) ? $user['state'] : 'N/A'; ?></span></div>
                    <div class="info-row"><span class="info-label">Pincode</span><span class="info-value"><?php echo (!empty($user['pincode'])) ? $user['pincode'] : 'N/A'; ?></span></div>
                    <div class="info-row"><span class="info-label">Age</span><span class="info-value"><?php echo (!empty($user['age'])) ? $user['age'] : 'N/A'; ?></span></div>
                    <div class="info-row"><span class="info-label">Verification Status</span>
                        <span class="info-value">
                            <?php echo ($user['is_verified'] == 1) ? '<i class="fas fa-check-circle" style="color:#388e3c;"></i> Verified' : '<i class="fas fa-times-circle" style="color:#d32f2f;"></i> Not Verified'; ?>
                        </span>
                    </div>
                </div>

                <div class="card">
                    <h3><i class="fas fa-shopping-bag" style="color:var(--rose); margin-right:10px;"></i> Recent Orders</h3>
                    <table>
                        <thead>
                            <tr><th>Order ID</th><th>Date</th><th>Price</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($order_res) > 0) {
                                while($ord = mysqli_fetch_array($order_res)) { ?>
                                <tr>
                                    <td><b>#ORD-<?php echo $ord['order_id']; ?></b></td>
                                    <td><?php echo date("d M, Y", strtotime($ord['order_date'])); ?></td>
                                    <td style="font-weight:600;">₹<?php echo number_format($ord['productprice']); ?></td>
                                    <td><span style="color:var(--rose); font-weight:600;"><?php echo $ord['status']; ?></span></td>
                                </tr>
                            <?php } } else { ?>
                                <tr><td colspan="4" align="center" style="padding:40px; color:#AAA;">No orders found.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <h3 style="color:var(--rose);"><i class="fas fa-history" style="margin-right:10px;"></i> Profile Activity Log (Audit)</h3>
                <p style="font-size: 0.8rem; color: #999; margin-bottom: 20px;">Tracking all manual changes made by the user.</p>
                <table>
                    <thead style="background:#FAF7F4;">
                        <tr>
                            <th>Updated Field</th>
                            <th>Previous Data</th>
                            <th>New Data</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($history_res) > 0) {
                            while($log = mysqli_fetch_array($history_res)) { 
                                $is_photo = ($log['field_name'] == 'userphoto');
                        ?>
                        <tr>
                            <td><b style="color:#555;"><?php echo ucwords(str_replace('_', ' ', $log['field_name'])); ?></b></td>
                            
                            <td>
                                <?php if($is_photo && !empty($log['old_value'])): ?>
                                    <img src="uploads/<?php echo $log['old_value']; ?>" class="history-thumb" onerror="this.src='https://cdn-icons-png.flaticon.com/512/149/149071.png'">
                                    <small style="color:#d32f2f;">Old Photo</small>
                                <?php else: ?>
                                    <span class="diff-old"><?php echo $log['old_value']; ?></span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if($is_photo && !empty($log['new_value'])): ?>
                                    <img src="uploads/<?php echo $log['new_value']; ?>" class="history-thumb" onerror="this.src='https://cdn-icons-png.flaticon.com/512/149/149071.png'">
                                    <small style="color:#388e3c;">New Photo</small>
                                <?php else: ?>
                                    <span class="diff-new"><?php echo $log['new_value']; ?></span>
                                <?php endif; ?>
                            </td>

                            <td style="color:#999; font-size:0.8rem;">
                                <?php echo date("d M Y | h:i A", strtotime($log['update_time'])); ?>
                            </td>
                        </tr>
                        <?php } } else { ?>
                            <tr><td colspan="4" align="center" style="padding:40px; color:#AAA;">No activity recorded yet.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>