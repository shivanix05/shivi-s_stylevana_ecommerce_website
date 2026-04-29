<?php 
require_once __DIR__ . "/config.php"; 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions - Shivi's Stylevana</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            --primary-pink: #D9A299;
            --soft-bg: #F0E4D3; /* Matching your theme */
            --text-dark: #444;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--soft-bg);
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Keeps footer at bottom */
        }

        /* --- MAIN CONTENT AREA --- */
        .legal-container {
            flex: 1; /* Occupies available space */
            padding: 80px 20px;
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
        }

        .policy-card {
            background: var(--white);
            padding: 60px;
            border-radius: 40px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            border: 1px solid rgba(217, 162, 153, 0.1);
        }

        .policy-card h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            color: var(--primary-pink);
            margin-bottom: 10px;
            text-align: center;
        }

        .last-updated {
            text-align: center;
            font-size: 0.85rem;
            color: #AAA;
            margin-bottom: 50px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
        }

        .policy-content h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--text-dark);
            margin-top: 35px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        /* Accent Line for Headings */
        .policy-content h3::before {
            content: "";
            width: 30px;
            height: 2px;
            background: var(--primary-pink);
            margin-right: 15px;
            display: inline-block;
        }

        .policy-content p {
            line-height: 1.9;
            color: #666;
            margin-bottom: 20px;
            font-size: 0.95rem;
            text-align: justify;
        }

        .policy-content b {
            color: var(--primary-pink);
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--soft-bg); }
        ::-webkit-scrollbar-thumb { background: var(--primary-pink); border-radius: 10px; }

        @media (max-width: 768px) {
            .policy-card { padding: 30px; border-radius: 20px; }
            .policy-card h1 { font-size: 2.2rem; }
            .legal-container { padding: 40px 15px; }
        }
    </style>
</head>
<body>

    <?php include("header.php"); ?>

    <main class="legal-container">
        <div class="policy-card">
            <h1>Terms & Conditions</h1>
            <p class="last-updated">Last Updated: March 2026</p>

            <div class="policy-content">
                <p>Welcome to <b>Shivi's Stylevana</b>. By accessing and shopping on this website, you agree to the terms and conditions outlined below. We are committed to providing you with the best beauty and lifestyle essentials while ensuring a transparent shopping experience.</p>

                <h3>1. User Agreement</h3>
                <p>By using our services, you confirm that you are at least 18 years old or are using the site under the supervision of a parent or guardian. All account information provided during checkout must be accurate and up-to-date to ensure successful delivery.</p>

                <h3>2. Intellectual Property</h3>
                <p>The designs, logos, product images, and content featured on Shivi's Stylevana are our intellectual property. Any reproduction, distribution, or unauthorized use without our written consent is strictly prohibited.</p>

                <h3>3. Product Accuracy</h3>
                <p>We strive to showcase the true colors and details of our jewelry, skincare, and clothing. However, due to screen variations, actual products may have slight differences. We recommend reading descriptions carefully before purchase.</p>

                <h3>4. Limitations of Liability</h3>
                <p>Shivi's Stylevana shall not be liable for any incidental or consequential damages resulting from the use of products or website downtime. Our goal is 100% uptime, but maintenance is necessary for a smooth experience.</p>

                <h3>5. Contact Us</h3>
                <p>We value your feedback. If you have any questions or require clarification regarding these terms, please reach out to our team at <b>support@stylevana.com</b>.</p>
            </div>
        </div>
    </main>

    <?php include("footer.php"); ?>

</body>
</html>
