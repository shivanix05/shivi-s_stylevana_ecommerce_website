<?php 
require_once __DIR__ . "/config.php"; 
session_start();

// Check if user is logged in
if (!isset($_SESSION["user"])) {
    header("location:login.php");
    exit();
}

$u_mail = $_SESSION['user'];

// SQL query: corrected table name and matching 'gmail'
// Note: Make sure $cn matches your config.php variable ($cn or $conn)
$query = mysqli_query($cn, "SELECT * FROM userdetail WHERE gmail = '$u_mail'");
$data = mysqli_fetch_assoc($query);

// Data variables (Dynamic)
$user_name = $data['name'] ?? 'Stylevana User';
$profile_pic = $data['userphoto'] ?? ''; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Shivi's Stylevana</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="my-profile.css">

    <style>
       
    </style>
</head>
<body>

    <?php include("header.php"); ?>

    <div class="profile-container">
        
        <div class="profile-main-card">
            <div class="img-holder">
                <?php if(!empty($profile_pic) && file_exists("uploads/" . $profile_pic)): ?>
                    <img src="uploads/<?php echo $profile_pic; ?>" alt="User Profile">
                <?php else: 
                    $name_parts = explode(" ", trim($user_name));
                    $f_initial = strtoupper(substr($name_parts[0], 0, 1));
                    $l_initial = (count($name_parts) > 1) ? strtoupper(substr(end($name_parts), 0, 1)) : "";
                ?>
                    <div class="initials-avatar"><?php echo $f_initial . $l_initial; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="user-details">
                <h2><?php echo $user_name; ?></h2>
                <p><i class="fas fa-envelope"></i> <?php echo $u_mail; ?></p>
                <p><i class="fas fa-check-circle" style="color: #4cd137;"></i> Verified Stylevana User</p>
            </div>
        </div>

        <div class="nav-links-container">
            <p class="section-label">Shopping Activity</p>
            <a href="wishlist.php" class="nav-row">
                <i class="fas fa-heart"></i>
                <span>My Wishlist</span>
                <i class="fas fa-chevron-right"></i>
            </a>

            <a href="addcart.php" class="nav-row">
                <i class="fas fa-shopping-cart"></i>
                <span>My Cart</span>
                <i class="fas fa-chevron-right"></i>
            </a>

            <a href="myorder.php" class="nav-row">
                <i class="fas fa-shopping-bag"></i>
                <span>My Orders</span>
                <i class="fas fa-chevron-right"></i>
            </a>

            <p class="section-label">Account & Info</p>
            <a href="user_edit.php" class="nav-row settings-btn">
                <i class="fas fa-user-cog"></i>
                <span>Account Settings</span>
                <i class="fas fa-chevron-right"></i>
            </a>

            <a href="about.php" class="nav-row about-btn">
                <i class="fas fa-info-circle"></i>
                <span>About Shivi's Stylevana</span>
                <i class="fas fa-chevron-right"></i>
            </a>

            <a href="contact.php" class="nav-row">
                <i class="fas fa-headset"></i>
                <span>Help & Support</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Sign Out</a>
    </div>
<?php include("footer.php"); ?>
</body>
</html>
