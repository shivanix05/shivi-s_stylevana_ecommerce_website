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
    
    <style>
        :root {
            --primary-pink: #D9A299;
            --bg-color: #fdfaf9;
            --white: #ffffff;
            --accent-soft: #fdf0ee;
        }

        body {
            margin: 0; padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: #333;
        }

        .profile-container {
            max-width: 500px;
            margin: 40px auto 50px;
            padding: 20px;
        }

        /* Profile Top Card */
        .profile-main-card {
            background: var(--white);
            border-radius: 30px;
            padding: 40px 20px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(217, 162, 153, 0.15);
            border: 1px solid #f0f0f0;
            margin-bottom: 25px;
        }

        .img-holder {
            width: 130px; height: 130px;
            margin: 0 auto 20px;
            border-radius: 50%;
            padding: 5px;
            background: linear-gradient(45deg, var(--primary-pink), #fff);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }

        .img-holder img {
            width: 100%; height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
        }

        .initials-avatar {
            width: 100%; height: 100%;
            border-radius: 50%;
            background: #DCC5B2;
            color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 40px; font-weight: bold;
            font-family: 'Playfair Display', serif;
            border: 3px solid #fff;
        }

        .user-details h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem; margin: 10px 0 5px;
            color: #2c3e50;
        }

        .user-details p { font-size: 0.9rem; color: #888; margin: 5px 0; }

        /* Navigation Links */
        .nav-links-container { display: flex; flex-direction: column; gap: 12px; }

        .nav-row {
            display: flex; align-items: center;
            background: var(--white);
            padding: 16px 25px;
            border-radius: 20px;
            text-decoration: none;
            color: #444;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.02);
            border: 1px solid #f5f5f5;
        }

        .nav-row:hover {
            transform: translateX(8px);
            border-color: var(--primary-pink);
            background: #fffafa;
        }

        .nav-row i {
            font-size: 1.2rem;
            color: var(--primary-pink);
            margin-right: 18px;
            width: 25px;
            text-align: center;
        }

        .nav-row span { font-weight: 600; font-size: 0.95rem; flex-grow: 1; }

        .nav-row .fa-chevron-right { font-size: 0.7rem; color: #ccc; margin-right: 0; }

        /* Section Styling */
        .section-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #bbb;
            margin: 15px 0 8px 10px;
            font-weight: 700;
        }

        .settings-btn { background: var(--accent-soft); border: 1px dashed var(--primary-pink); }
        
        .about-btn { background: #f0f7ff; border: 1px solid #e0ebf5; }
        .about-btn i { color: #54a0ff; }

        .logout-btn {
            text-align: center; margin-top: 35px;
            display: block; text-decoration: none;
            color: #ff7675; font-weight: 600; font-size: 0.9rem;
        }
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
