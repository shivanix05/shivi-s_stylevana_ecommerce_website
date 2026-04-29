<?php 
require_once __DIR__ . '/config.php'; 
session_start();

if (!isset($_SESSION["admin"])){
    header("location:login.php");
    exit();    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Queries - Shivi's Stylevana Admin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="quriesstyle.css">
    
</head>
<body>

    <?php include("header.php"); ?>

    <div class="admin-wrapper">
        <?php include("sidebar.php"); ?>

        <div class="main-content">
            <div class="content-body">
                <header style="display: flex; justify-content: space-between; margin-bottom: 30px; align-items: center;">
                    <h1>Customer Queries</h1>
                    <span style="color: var(--text-gray); font-size: 0.9rem;">
                        Management / <small style="color:var(--accent); font-weight: 600;">Queries</small>
                    </span>
                </header>

                <div class="query-container">
                    <table class="query-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Conversation</th>
                                <th>Latest Activity</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sql = "SELECT name, gmail, 
                                    COUNT(fid) as total_msg, 
                                    MAX(submitted_at) as last_update,
                                    SUM(CASE WHEN admin_reply IS NULL OR admin_reply = '' THEN 1 ELSE 0 END) as pending_count
                                    FROM userfeedback 
                                    GROUP BY gmail, name 
                                    ORDER BY last_update DESC";
                            
                            $res = mysqli_query($conn, $sql);
                            
                            if($res && mysqli_num_rows($res) > 0) {
                                while($row = mysqli_fetch_array($res)) {
                                    $is_pending = ($row['pending_count'] > 0);
                            ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                                    <small style="color: #999;"><?php echo htmlspecialchars($row['gmail']); ?></small>
                                </td>
                                <td>
                                    <span style="background:#F4EBE4; padding:5px 12px; border-radius:10px; font-size:12px; color: #444;">
                                        <i class="far fa-comments"></i> <?php echo $row['total_msg']; ?> Messages
                                    </span>
                                </td>
                                <td><span style="font-size: 0.9rem; color: #555;"><?php echo date('d M, Y', strtotime($row['last_update'])); ?></span></td>
                                <td>
                                    <?php if($is_pending) { ?>
                                        <span class="status-pill status-new"><?php echo $row['pending_count']; ?> Pending</span>
                                    <?php } else { ?>
                                        <span class="status-pill status-replied">All Replied</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="view-query.php?email=<?php echo urlencode($row['gmail']); ?>" class="btn-view">
                                        View Chat <i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-left: 5px;"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align:center; padding:50px; color:#ccc;'>No queries found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="footer-section">
                <?php include("footer.php"); ?>
            </div>
        </div>
    </div>

</body>
</html>