<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Shivi's Stylevana</title>
    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --primary-bg: #F0E4D3;
    --primary-text: #333;
    --accent-color: #D9A299;
    --secondary-accent: #DCC5B2;
    --dark-text: #222;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--primary-bg);
    color: var(--primary-text);
    line-height: 1.6;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Header Styling */
.main-header {
    background: #e0dada;
    padding: 5px 0;
    border-bottom: 1px solid #e4c4c4;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
  
}

.logo a {
    font-family: 'Playfair Display', serif;
    font-size: 2em;
    font-weight: 700;
    color: black;
    text-decoration: none;
}

.logo span {
    font-size: 0.8em;
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    display: block;
    margin-top: -5px;
    color: #888;
}
.img-logo{
    height: 5em;
    margin: right 0%;
}
.logo{
     display: flex;
    
}
.btn-login{
    color: white;
    text-decoration: none;
}
.search-container {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

 .search-container input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 50px;
            font-family: 'Poppins', sans-serif;
        }

 .search-container button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--dark-text);
            font-size: 1.2em;
            cursor: pointer;
        }

.main-nav {
    display: flex;
    gap: 20px;
    }

.main-nav a {
    text-decoration: none;
    color: var(--dark-text);
    font-weight: 550;
    padding: 5px 10px;
    position: relative;
  
}
.options{
    border:#c7a9a9 0.5px silver;

}

.main-nav a::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: 0;
    transform: translateX(-50%) scaleX(0);
    width: 100%;
    height: 2px;
    background-color: var(--accent-color);
    transition: transform 0.3s ease;
}

.main-nav a:hover::after {
    transform: translateX(-50%) scaleX(1);
}

.header-icons {
    display: flex;
    align-items: center;
    gap: 15px;
   
}

.login-btn {
    background-color: var(--accent-color);
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.login-btn:hover {
    background-color: #ada39e;
}

.header-icons .icon-btn {
    background: none;
    border: none;
    cursor: pointer;
    color:black;
    font-size: 1.2em;
}

main {
            padding: 3rem 0;
            flex-grow: 1;
        }

.section {
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        h1, h2 {
            text-align: center;
            color: var(--dark-text);
        }
        
        h1 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 1rem;
        }
        
        main p {
            font-size: 1rem;
            line-height: 1.625;
            color: var(--primary-text);
            margin-bottom: 1.5rem;
        }
        
        .image-container {
            margin: 2rem auto;
            max-width: 600px;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .image-container img {
            width: 100%;
            height: auto;
            display: block;
        }

        footer {
            background-color: var(--secondary-accent);
            color: var(--dark-text);
            padding: 2rem 0;
            margin-top: auto;
            text-align: center;
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
        
        .terms-details-container {
            text-align: left;
        }

        details {
            background-color: var(--primary-bg);
            border: 1px solid var(--secondary-accent);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        summary {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-text);
            cursor: pointer;
        }
        
        details p {
            margin-top: 1rem;
            font-size: 0.9rem;
            line-height: 1.5;
            color: var(--primary-text);
        }
        
        /* Media Queries for Responsiveness */
        @media (min-width: 768px) {
            h1 { font-size: 3rem; }
            .grid-container {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        @media (max-width: 767px) {
            .search-bar, nav {
                display: none;
            }
            header .flex-container {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
     <header class="main-header">
        <div class="container header-content">
           
            <div class="logo">
                <div> <img  src="logo.png" class="img-logo"></div>
               <a href="#">Shivi's<span>Stylevana</span></a>
            </div>

            <nav class="main-nav">
                <a href="index.php">Home</a>
                <a href="#">About</a>
                <a href="#">Contact</a>
            </nav>

            <div class="header-icons">
                <button class="login-btn"> <a href="login.php" class="btn-login">Login / Sign Up </a></button>
                 <div class="header-center">
                <div class="search-container">
                    <input type="text" placeholder="Search for products...">
                    <button type="button"><i class="fas fa-search"></i></button>
                </div>
            </div>
                <button class="icon-btn"><i class="fas fa-search"></i></button>
                <button class="icon-btn"><i class="fas fa-user"></i></button>
                <button class="icon-btn"><i class="fas fa-shopping-bag"></i></button>
            </div>

        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <h1>About Us</h1>
        <p style="text-align: center;">Welcome to Shivi's Stylevana, where fashion meets passion. We believe that style is more than just clothing; it's a form of self-expression.</p>
        
        <div class="section">
            <h3>Our Story</h3>
            <p>Founded in 2025 by Shivi, our brand was born out of a desire to create a fashion line that is both elegant and accessible. Starting with a small collection of handcrafted items, Shivi's Stylevana quickly grew into a beloved online destination for unique and timeless pieces. We are committed to celebrating individuality and empowering our customers to feel confident in their own skin.</p>
            <div class="image-container">
                <img src="" alt="Our Story">
            </div>
        </div>

        <div class="section">
            <h3>Our Mission</h3>
            <p>Our mission is to provide high-quality, stylish, and comfortable fashion that inspires confidence. We are dedicated to offering a curated selection of products that reflect the latest trends while remaining true to our core values of quality, creativity, and customer satisfaction.</p>
            <div class="image-container">
                <img src="https://placehold.co/600x350/DCC5B2/fff?text=Our+Mission" alt="Our Mission">
            </div>
        </div>
        
        <div class="section">
            <h3>Our Values</h3>
            <ul style="list-style-type: disc; text-align: left; max-width: 600px; margin: 0 auto; padding-left: 1.5rem;">
                <li>**Quality:** We use only the finest materials to ensure our products are durable and luxurious.</li>
                <li>**Creativity:** Our designs are unique and crafted with a passion for artistic expression.</li>
                <li>**Customer Focus:** Your satisfaction is our top priority. We are here to help you find the perfect style.</li>
                <li>**Sustainability:** We are committed to ethical sourcing and sustainable practices to protect our planet.</li>
            </ul>
        </div>
        
        <div class="section terms-details-container">
            <h3>Terms and Conditions</h3>
            <details>
                <summary>Terms of Service Summary</summary>
                <p>By using Shivi's Stylevana, you agree to our terms. This includes respecting our intellectual property, using the site lawfully, and adhering to our return policy. We reserve the right to modify these terms at any time. Your continued use of the site signifies your acceptance of any changes.</p>
            </details>
            <details>
                <summary>Detailed Terms and Conditions</summary>
                <p>Welcome to Shivi's Stylevana. These Terms and Conditions govern your use of our website and services. By accessing or using the site, you agree to be bound by these terms. If you do not agree to all the terms and conditions, you may not access the site or use any services.</p>
                <p><strong>1. Intellectual Property:</strong> The content on this website, including text, graphics, logos, images, and software, is the property of Shivi's Stylevana and is protected by copyright and other intellectual property laws. You may not use, reproduce, or distribute any content without our express written permission.</p>
                <p><strong>2. User Conduct:</strong> You agree to use our website for lawful purposes only. You are prohibited from posting or transmitting any material that is unlawful, harmful, threatening, abusive, or otherwise objectionable.</p>
                <p><strong>3. Product Information:</strong> We strive to ensure all product descriptions, images, and prices are accurate. However, errors may occur. We reserve the right to correct any errors and to change or update information at any time without prior notice.</p>
                <p><strong>4. Limitation of Liability:</strong> Shivi's Stylevana will not be liable for any damages, including but not limited to direct, indirect, incidental, or consequential damages, arising from your use of this website or our products.</p>
                <p><strong>5. Governing Law:</strong> These terms and conditions are governed by and construed in accordance with the laws of [Your Jurisdiction], and you irrevocably submit to the exclusive jurisdiction of the courts in that State or location.</p>
            </details>
        </div>
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
