<style>
    /* Sidebar Container - Exact same as your 1st photo */
    .sidebar { 
        width: 260px; 
        background: #fff; 
        border-radius: 25px; 
        padding: 30px 20px; 
        height: fit-content; 
        box-shadow: 0 5px 20px rgba(0,0,0,0.02); 
        position: sticky; 
        top: 100px; 
        font-family: 'Poppins', sans-serif;
    }
    
    .sidebar h3 { 
        font-size: 1.1rem; 
        color: #333; 
        margin-bottom: 25px; 
        padding-left: 15px; 
        font-weight: 600; 
    }
    
    .nav-link { 
        display: flex; 
        align-items: center; 
        gap: 12px; 
        padding: 12px 15px; 
        color: #7A7A7A; 
        text-decoration: none; 
        border-radius: 12px; 
        margin-bottom: 8px; 
        font-size: 0.9rem; 
        transition: 0.3s ease; 
    }

    /* Icon box for Notification Overlay */
    .nav-icon-box {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .nav-link i { 
        color: #D9A899; 
        width: 20px; 
        text-align: center; 
        font-size: 1.1rem;
    }

    /* Small Notification Dot on Icon */
    .notif-dot {
        background: #e74c3c;
        color: white;
        font-size: 9px;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        position: absolute;
        top: -8px;
        right: -10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        border: 2px solid #fff;
    }

    .nav-link:hover { background: #FDFBF9; color: #D9A899; }
    .nav-link.active { background: #F4EBE4; color: #444; font-weight: 600; }
</style>

<?php 
    $current_page = basename($_SERVER['PHP_SELF']); 

    // Counting pending from userfeedback table
    $pending_count = 0;
    if(isset($conn)){
        $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM userfeedback WHERE admin_reply IS NULL OR admin_reply = ''");
        $data = mysqli_fetch_assoc($res);
        $pending_count = $data['total'];
    }
?>

<aside class="sidebar">
    <h3>Management</h3>
    
    <a href="admindashboard.php" class="nav-link <?php echo ($current_page == 'admindashboard.php') ? 'active' : ''; ?>">
        <i class="fas fa-th-large"></i> Dashboard
    </a>
    
    <a href="product.php" class="nav-link <?php echo ($current_page == 'product.php') ? 'active' : ''; ?>">
        <i class="fas fa-box"></i> Inventory
    </a>
    
    <a href="order.php" class="nav-link <?php echo ($current_page == 'order.php') ? 'active' : ''; ?>">
        <i class="fas fa-shipping-fast"></i> Orders
    </a>
    
    <a href="user-record.php" class="nav-link <?php echo ($current_page == 'user-record.php') ? 'active' : ''; ?>">
        <i class="fas fa-users"></i> Customers
    </a>

    <a href="queries.php" class="nav-link <?php echo ($current_page == 'queries.php') ? 'active' : ''; ?>">
        <span class="nav-icon-box">
            <i class="fas fa-comment-dots"></i>
            <?php if($pending_count > 0): ?>
                <span class="notif-dot"><?php echo $pending_count; ?></span>
            <?php endif; ?>
        </span>
        Queries
    </a>
    
    <a href="review.php" class="nav-link <?php echo ($current_page == 'review.php') ? 'active' : ''; ?>">
        <i class="fas fa-star"></i> Reviews
    </a>

     <a href="admin_stats.php" class="nav-link <?php echo ($current_page == 'admin_stats.php') ? 'active' : ''; ?>"> <i class="fas fa-chart-bar"></i> Statistics </a>

    <a href="adminlogout.php" class="nav-link" style="margin-top: 20px; color: #e74c3c;">
        <i class="fas fa-sign-out-alt" style="color: #e74c3c;"></i> Logout
    </a>
</aside>