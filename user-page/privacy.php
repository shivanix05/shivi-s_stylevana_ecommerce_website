<?php 
require_once __DIR__ . "/config.php"; 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | Shivi's Stylevana</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            --primary-pink: #D9A299;
            --soft-bg: #F0E4D3; /* Your signature beige theme */
            --accent-gold: #C5A059;
            --text-dark: #2c2c2c;
            --white: #ffffff;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--soft-bg);
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* --- LAYOUT FIXES --- */
        header {
            width: 100%;
            background: var(--white);
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            z-index: 1000;
        }

        .privacy-wrapper {
            flex: 1; /* Pushes footer to bottom */
            padding: 60px 20px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .privacy-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .privacy-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .privacy-header p {
            color: var(--primary-pink);
            letter-spacing: 4px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        /* --- INFO CARDS --- */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .info-box {
            background: var(--white);
            padding: 45px 35px;
            border-radius: 35px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.03);
            transition: all 0.4s ease;
            border: 1px solid transparent;
        }

        .info-box:hover {
            transform: translateY(-10px);
            border-color: var(--primary-pink);
            box-shadow: 0 20px 40px rgba(217,162,153,0.15);
        }

        .info-box i {
            font-size: 2.2rem;
            color: var(--primary-pink);
            margin-bottom: 20px;
            background: #fff8f6;
            width: 70px;
            height: 70px;
            line-height: 70px;
            border-radius: 50%;
            display: inline-block;
        }

        .info-box h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .info-box p {
            line-height: 1.7;
            color: #666;
            font-size: 0.9rem;
        }

        /* --- DETAILED CONTENT --- */
        .detailed-content {
            background: var(--white);
            padding: 60px;
            border-radius: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }

        .detailed-content h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            margin-bottom: 25px;
            color: var(--text-dark);
            border-left: 5px solid var(--primary-pink);
            padding-left: 20px;
        }

        .detailed-content p {
            line-height: 1.8;
            color: #555;
            margin-bottom: 25px;
        }

        .data-list {
            list-style: none; padding: 0; margin-bottom: 30px;
        }

        .data-list li {
            padding: 12px 0;
            border-bottom: 1px solid #f9f9f9;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            color: #444;
        }

        .data-list li i {
            color: var(--primary-pink);
            margin-right: 15px;
            font-size: 1rem;
        }

        .help-box {
            margin-top: 40px;
            padding: 30px;
            background: #FDF9F3;
            border-radius: 20px;
            border: 1px dashed var(--primary-pink);
            text-align: center;
        }

        footer {
            background: var(--white);
            padding: 30px;
            text-align: center;
            border-top: 1px solid #eee;
        }

        @media (max-width: 768px) {
            .privacy-header h1 { font-size: 2.5rem; }
            .detailed-content { padding: 30px; }
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <?php include("header.php"); ?>

    <div class="privacy-wrapper">
        <div class="container">
            
            <div class="privacy-header">
                <p>Your Trust is Our Priority</p>
                <h1>Privacy Policy</h1>
            </div>

            <div class="info-grid">
                <div class="info-box">
                    <i class="fas fa-user-shield"></i>
                    <h3>Data Protection</h3>
                    <p>We secure your personal information with advanced encryption standards. Your details are 100% confidential and safe with us.</p>
                </div>

                <div class="info-box">
                    <i class="fas fa-cookie-bite"></i>
                    <h3>Cookie Policy</h3>
                    <p>We use cookies to provide a personalized shopping experience and to analyze our website performance for a smoother journey.</p>
                </div>

                <div class="info-box">
                    <i class="fas fa-share-alt-slash"></i>
                    <h3>No Third-Party</h3>
                    <p>Your data is never sold to third-party marketing agencies. Protecting your privacy is our core commitment at Stylevana.</p>
                </div>
            </div>

            <div class="detailed-content">
                <h2>How We Use Your Information</h2>
                <p>To provide you with the best beauty essentials and fashion updates, we collect specific data to fulfill orders and improve our service. Here is what we collect:</p>
                
                <ul class="data-list">
                    <li><i class="fas fa-check-circle"></i> Personal Identity: Name and Contact Details for secure delivery.</li>
                    <li><i class="fas fa-check-circle"></i> Payment Security: Information processed only through encrypted gateways.</li>
                    <li><i class="fas fa-check-circle"></i> Preferences: Shopping history to offer better Stylevana recommendations.</li>
                    <li><i class="fas fa-check-circle"></i> Technical Data: Device information used solely for security audits.</li>
                </ul>

                <h2>Security Standards</h2>
                <p>Shivi's Stylevana utilizes SSL (Secure Sockets Layer) technology to protect your information during transmission. We conduct regular system audits to ensure your aesthetic journey remains uninterrupted and secure.</p>
                
                <div class="help-box">
                    <p><b>Need Assistance?</b> If you have any concerns regarding your privacy or data rights, please contact our legal team at <b>privacy@stylevana.com</b></p>
                </div>
            </div>

        </div>
    </div>

    <footer>
        <p style="color: #999; font-size: 0.85rem;">&copy; 2026 Shivi's Stylevana. Crafted for Elegance. All Rights Reserved.</p>
    </footer>

</body>
</html>
