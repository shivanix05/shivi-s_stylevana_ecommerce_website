<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root { 
        --bg: #F8F3ED; 
        --rose: #D9A899; 
        --white: #fff; 
        --text: #444; 
        --gray: #7A7A7A; 
        --red: #e74c3c;
    }

    /* Full Width Header Styling */
    .main-header { 
        background: var(--white); 
        padding: 12px 50px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        border-bottom: 1px solid #eee; 
        position: sticky; 
        top: 0; 
        z-index: 1000;
        box-shadow: 0 2px 15px rgba(0,0,0,0.03);
    }
    
    /* Logo aur Title Section */
    .logo-container { 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        text-decoration: none;
    }
    .logo-container img { 
        width: 45px; 
        height: 45px; 
        object-fit: contain;
        border-radius: 8px;
    }
    .logo-container b { 
        font-family: 'Playfair Display', serif; 
        font-size: 1.6rem; 
        color: var(--text); 
        letter-spacing: 0.5px;
    }
    
    /* Admin Profile & Logout Section */
    .header-right { 
        display: flex; 
        align-items: center; 
        gap: 30px; 
    }
    .admin-info { 
        text-align: right; 
        line-height: 1.3; 
        border-right: 1px solid #eee;
        padding-right: 20px;
    }
    .admin-name { 
        font-size: 0.95rem; 
        color: var(--text); 
        font-weight: 600; 
        display: block; 
    }
    .admin-status { 
        font-size: 0.7rem; 
        color: var(--rose); 
        text-transform: uppercase; 
        font-weight: 700; 
        letter-spacing: 1px;
    }
    
    .logout-link { 
        text-decoration: none; 
        color: var(--red); 
        background: #fff5f5;
        font-weight: 600; 
        font-size: 0.85rem; 
        padding: 10px 20px; 
        border-radius: 12px; 
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid transparent;
    }
    .logout-link:hover { 
        background: #fee2e2; 
        border-color: #fab1a0;
        transform: translateY(-1px); 
    }
</style>

<header class="main-header">
    <a href="admindashboard.php" class="logo-container">
        <img src="logo.png" alt="Stylevana Logo">
        <b>Stylevana Admin</b>
    </a>
    
    <div class="header-right">
        <div class="admin-info">
            <span class="admin-name">Shivani Mishra</span>
            <span class="admin-status"><i class="fas fa-shield-alt"></i> Online • Administrator</span>
        </div>
        
        <a href="adminlogout.php" class="logout-link">
            <i class="fas fa-power-off"></i> Logout
        </a>
    </div>
</header>