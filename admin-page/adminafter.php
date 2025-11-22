
<?php 
session_start();
if (!isset($_SESSION["admin"])){
    header("location:adminlogin.php");
    exit();
}
?>
<?php
    if (isset($_POST["logout-btn"])){
        session_destroy();
        header("location:adminlogin.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Shivi's Stylevana</title>
    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Color variables */
        :root {
            --primary-bg: #F0E4D3;
            --primary-text: #333;
            --accent-color: #D9A299;
            --secondary-accent: #DCC5B2;
            --dark-text: #222;
        }

        /* Basic styles */
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            color: var(--primary-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Header styles */
        header {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        
        .flex-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
            height: 5rem;
            width: auto;
        }

        .header-title {
            color: var(--dark-text);
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-btn {
            background-color: var(--accent-color);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        
        .logout-btn:hover {
            opacity: 0.8;
        }

        /* Main content styles */
        main {
            padding: 2rem 0;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        

        /* Sidebar navigation */
        .sidebar {
            background-color: #fff;
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            height: fit-content;
        }

        .sidebar h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-text);
            margin-bottom: 1rem;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .sidebar-nav a {
            display: block;
            width: 100%;
            color: var(--primary-text);
            text-align: left;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }
        
        .sidebar-nav a:hover {
            background-color: var(--secondary-accent);
        }
        
        .sidebar-nav .active a {
            background-color: var(--accent-color);
            color: #fff;
            font-weight: 600;
        }
        
        
        
        /* Footer styles */
        footer {
            background-color: var(--secondary-accent);
            color: var(--dark-text);
            padding: 2rem 0;
            text-align: center;
            margin-top: 2rem;
        }

        footer .social-icons a {
            color: var(--dark-text);
            font-size: 1.25rem;
            transition: color 0.3s;
            margin: 0 0.5rem;
        }
        
        footer .social-icons a:hover {
            color: var(--accent-color);
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 767px) {
            .admin-panel-container {
                flex-direction: column;
            }
            .sidebar {
                min-width: auto;
            }
        }
.logoo{
    display: flex;
    justify-content: center;
    align-items: center;
}
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container flex-container">
            <!-- Logo -->
            <div class="logoo">
               
                <div class="logo"> <img src="logo.png" alt="Shivi's Stylevana Logo"></div>
                <div> <p>welcome,<?php echo $_SESSION["admin"]; ?></p></div>
             
            </div>
            <div class="header-actions">
                <span class="header-title">Admin Panel</span>
                <form method="post">
                    <input type="submit" class="logout-btn" name="logout-btn" value="Logout">
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div class="admin-panel-container">
            <!-- Sidebar Navigation -->
            <aside class="sidebar">
                <h2>Dashboard</h2>
                <ul class="sidebar-nav">
                    <li class="active"><a href="user-record.php">User Records</a></li>
                    <li class="active"><a href="product.php">Product Records</a></li>
                    <li class="active"><a href="order.php">Order Records</a></li>
                </ul>
            </aside>
            
          
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Shivi's Stylevana. All Rights Reserved.</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-pinterest-p"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>
