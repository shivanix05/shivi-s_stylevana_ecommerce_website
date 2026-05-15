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
    <link rel="stylesheet" href="legal.css">
    <style>
        
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
