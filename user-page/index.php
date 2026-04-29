<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shivi's Stylevana | All In One Store</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* Global Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; color: #282c3f; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        /* Professional Header */
        .main-header { 
            background: #fff; 
            padding: 10px 0; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
            position: sticky; 
            top: 0; 
            z-index: 1000;
        }
        .header-content { display: flex; align-items: center; justify-content: space-between; }
        
        .logo { display: flex; align-items: center; gap: 10px; }
        .img-logo { height: 45px; }
        .logo a { text-decoration: none; font-family: 'Playfair Display', serif; font-size: 24px; color: #000; font-weight: bold; }
        .logo span { color: #D9A299; display: block; font-size: 16px; margin-top: -5px; font-family: 'Poppins', sans-serif; font-weight: 400; }

        .main-nav a { text-decoration: none; color: #282c3f; margin: 0 15px; font-weight: 600; font-size: 14px; text-transform: uppercase; transition: 0.3s; }
        .main-nav a:hover { color: #D9A299; }

        .header-icons { display: flex; align-items: center; gap: 20px; }
        .search-container { position: relative; background: #f5f5f6; border-radius: 4px; padding: 5px 10px; display: flex; align-items: center; }
        .search-container input { border: none; background: transparent; outline: none; padding: 5px; font-size: 13px; width: 150px; }
        .search-container button { border: none; background: transparent; cursor: pointer; color: #696e79; }

        .login-btn { background: #D9A299; border: none; padding: 8px 18px; border-radius: 4px; cursor: pointer; transition: 0.3s; }
        .login-btn a { text-decoration: none; color: #fff; font-weight: 600; font-size: 13px; }
        .login-btn:hover { background: #c28b82; }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
        }
        .hero-content h1 { font-family: 'Playfair Display', serif; font-size: 48px; margin-bottom: 15px; letter-spacing: 2px; }
        .hero-content p { font-size: 18px; margin-bottom: 25px; font-weight: 300; }
        .btn { display: inline-block; padding: 12px 30px; background: #fff; color: #000; text-decoration: none; font-weight: 600; border-radius: 2px; margin: 0 10px; transition: 0.3s; }
        .btn:hover { background: #D9A299; color: #fff; }

        /* Product Grid Styling */
        .section-title { text-align: center; font-family: 'Playfair Display', serif; font-size: 32px; margin: 50px 0 30px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 50px; }
        
        .product-card { background: #fff; transition: 0.3s; overflow: hidden; border-radius: 8px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .product-card img { width: 100%; height: 320px; object-fit: cover; transition: 0.5s; }
        .product-card:hover img { transform: scale(1.05); }

        .product-info { padding: 15px; text-align: center; }
        .product-info h3 { font-size: 16px; font-weight: 600; color: #282c3f; margin-bottom: 5px; }
        .product-info p { font-size: 12px; color: #7e818c; margin-bottom: 10px; }
        .price { font-size: 18px; font-weight: 700; color: #282c3f; }
        
        .order-btn { width: 100%; background: #282c3f; color: #fff; border: none; padding: 10px; margin-top: 15px; cursor: pointer; font-weight: 600; border-radius: 4px; transition: 0.3s; }
        .order-btn:hover { background: #D9A299; }

        /* Collection Banner */
        .collection-banner { 
            display: flex; 
            align-items: center; 
            background: #f9f3f1; 
            border-radius: 15px; 
            margin: 60px 0; 
            overflow: hidden; 
        }
        .banner-text { flex: 1; padding: 50px; }
        .banner-text h2 { font-family: 'Playfair Display', serif; font-size: 36px; margin-bottom: 15px; }
        .collection-banner img { flex: 1; width: 50%; height: 400px; object-fit: cover; }

        /* Footer */
        .main-footer { background: #282c3f; color: #fff; padding: 40px 0; text-align: center; margin-top: 50px; }
        .social-icons { margin-top: 20px; }
        .social-icons a { color: #fff; font-size: 20px; margin: 0 10px; transition: 0.3s; }
        .social-icons a:hover { color: #D9A299; }
    </style>
</head>
<body>

    <header class="main-header">
        <div class="container header-content">
            <div class="logo">
                <img src="logo.png" class="img-logo" alt="Logo">
                <a href="index.php">Shivi's<span>Stylevana</span></a>
            </div>

            <nav class="main-nav">
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="login.php">Contact</a>
            </nav>

            <div class="header-icons">
                <div class="search-container">
                    <input type="text" placeholder="Search products...">
                    <button type="button"><i class="fas fa-search"></i></button>
                </div>
                <button class="login-btn"><a href="login.php">Login / Sign Up</a></button>
                <a href="login.php" style="color:#282c3f;"><i class="fas fa-shopping-bag fa-lg"></i></a>
            </div>
        </div>
    </header>

    <main>
        <section class="hero-section">
            <div class="hero-content">
                <h1>ALL IN ONE STORE</h1>
                <p>Discover the latest trends and timeless classics for every woman.</p>
                <div class="hero-buttons">
                    <a href="login.php" class="btn">SHOP NOW</a>
                    <a href="login.php" class="btn">NEW ARRIVALS</a>
                </div>
            </div>
        </section>

        <section class="container">
            <h2 class="section-title">Essential Clothes</h2>
            <div class="product-grid">
                <div class="product-card">
                    <img src="https://i.pinimg.com/736x/7c/da/a3/7cdaa392ff6401cac5669c8d77364378.jpg" alt="Casual Wear">
                    <div class="product-info">
                        <h3>Casual Wear Dress</h3>
                        <p>Order ID: #CWD2345</p>
                        <span class="price">₹2,500</span>
                        <a href="login.php"><button class="order-btn">Order Now</button></a>
                    </div>
                </div>
                <div class="product-card">
                    <img src="https://i.pinimg.com/736x/f8/e2/97/f8e297ed0af424341af866f7af6e74c4.jpg" alt="Elegant Dress">
                    <div class="product-info">
                        <h3>Elegant Evening Dress</h3>
                        <p>Order ID: #EVD6789</p>
                        <span class="price">₹2,500</span>
                        <a href="login.php"><button class="order-btn">Order Now</button></a>
                    </div>
                </div>
                <div class="product-card">
                    <img src="https://i.pinimg.com/736x/7d/65/30/7d6530eddaaaaf33d2f3dde89484f02a.jpg" alt="Workwear">
                    <div class="product-info">
                        <h3>Professional Workwear</h3>
                        <p>Order ID: #PWW1011</p>
                        <span class="price">₹2,500</span>
                        <a href="login.php"><button class="order-btn">Order Now</button></a>
                    </div>
                </div>
                <div class="product-card">
                    <img src="https://i.pinimg.com/736x/9e/b6/a6/9eb6a69ef66a2d0792ef4dfcb994354d.jpg" alt="Jacket">
                    <div class="product-info">
                        <h3>Seasonal Trends Jacket</h3>
                        <p>Order ID: #STJ1213</p>
                        <span class="price">₹2,500</span>
                        <a href="login.php"><button class="order-btn">Order Now</button></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="collection-banner">
                <div class="banner-text">
                    <h2>Jewelry Collection 30% OFF</h2>
                    <p>Sparkle and shine with our new arrivals of handcrafted jewelry.</p>
                    <a href="login.php" class="btn" style="background:#282c3f; color:#fff;">SHOP NOW</a>
                </div>
                <img src="https://i.pinimg.com/videos/thumbnails/originals/2e/0f/9e/2e0f9ee31bb4f39abb1e68d4100d3a9b.0000000.jpg" alt="Jewelry">
            </div>
        </section>

        <section class="container">
            <h2 class="section-title">Makeup & Accessories</h2>
            <div class="product-grid">
                <div class="product-card">
                    <img src="https://m.media-amazon.com/images/I/61jxV0A1YlL._UF1000,1000_QL80_.jpg" alt="Lipstick">
                    <div class="product-info">
                        <h3>Signature Lipstick Set</h3>
                        <p>Order ID: #LPS4567</p>
                        <span class="price">₹2,500</span>
                        <a href="login.php"><button class="order-btn">Order Now</button></a>
                    </div>
                </div>
                <div class="product-card">
                    <img src="https://beautybaskets.in/wp-content/uploads/2022/03/Chambor-Visage-Contour-Studio-Face-Palette-Make-Up-203-Deep2-1200x1200.webp.jpg" alt="Palette">
                    <div class="product-info">
                        <h3>Glow Face Palette</h3>
                        <p>Order ID: #GFP8910</p>
                        <span class="price">₹1,000</span>
                        <a href="login.php"><button class="order-btn">Order Now</button></a>
                    </div>
                </div>
                <div class="product-card">
                    <img src="https://images.squarespace-cdn.com/content/v1/5a5fcb71010027f926e21de9/1592433648367-D349ZB69FDPZH7Q9Y3WZ/IMG_9036.JPG?format=500w" alt="Sunglasses">
                    <div class="product-info">
                        <h3>Stylish Sunglasses</h3>
                        <p>Order ID: #SSG1112</p>
                        <span class="price">₹2,500</span>
                        <a href="login.php"><button class="order-btn">Order Now</button></a>
                    </div>
                </div>
                <div class="product-card">
                    <img src="https://i.pinimg.com/736x/47/30/ae/4730ae541110f29a4626ad7e5878370e.jpg" alt="Tote Bag">
                    <div class="product-info">
                        <h3>Leather Tote Bag</h3>
                        <p>Order ID: #LTB1314</p>
                        <span class="price">₹2,500</span>
                        <a href="login.php"><button class="order-btn">Order Now</button></a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2026 Shivi's Stylevana. All Rights Reserved.</p>
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
