<?php 
require_once __DIR__ . "/config.php"; 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Shivi's Stylevana</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            --primary-pink: #D9A299;
            --soft-bg: #fdfaf9;
            --text-dark: #333;
            --glass: rgba(255, 255, 255, 0.7);
        }

        body {
            margin: 0; font-family: 'Poppins', sans-serif;
            background-color: var(--soft-bg); color: var(--text-dark);
            overflow-x: hidden;
            position: relative;
        }

        /* --- STATIC HEADER (Header fixed nahi rahega, scroll hoga) --- */
        header {
            position: static; 
            background: #fff;
            width: 100%;
            z-index: 10;
        }

        /* --- BUBBLE EFFECT --- */
        .bubble-bg {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1; overflow: hidden; pointer-events: none;
        }
        .bubble {
            position: absolute; background: var(--primary-pink);
            border-radius: 50%; opacity: 0.1;
            animation: moveUp 15s infinite ease-in-out;
        }
        @keyframes moveUp {
            0% { transform: translateY(110vh) scale(1); opacity: 0.1; }
            50% { opacity: 0.3; }
            100% { transform: translateY(-20vh) scale(1.5); opacity: 0; }
        }

        /* --- NEW HERO SECTION --- */
        .hero-banner {
            height: 70vh; 
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), 
            url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1500&q=80');
            background-size: cover; 
            background-position: center;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            text-align: center;
            color: #fff;
        }
        .hero-content {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            padding: 50px;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            max-width: 700px;
            animation: fadeIn 1.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hero-content h1 { 
            font-family: 'Playfair Display', serif; 
            font-size: 4rem; 
            margin-bottom: 10px; 
            font-weight: 900;
        }
        .hero-content p { 
            font-size: 1.2rem; 
            letter-spacing: 2px; 
            text-transform: uppercase;
        }

        /* --- CONTENT SECTION --- */
        .content-section { max-width: 1100px; margin: 80px auto; padding: 0 20px; }
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
        .about-text h2 { font-family: 'Playfair Display'; font-size: 2.5rem; color: var(--primary-pink); }
        .about-text p { font-size: 1.05rem; line-height: 1.8; color: #666; }
        .main-img { width: 100%; border-radius: 20px; box-shadow: 20px 20px 0 var(--primary-pink); }

        /* --- SHINY BUTTON --- */
        .btn-shiny {
            position: relative; display: inline-block; margin-top: 20px;
            padding: 12px 35px; background: var(--primary-pink);
            color: #fff; text-decoration: none; border-radius: 50px;
            font-weight: 600; overflow: hidden; border: none;
            box-shadow: 0 4px 15px rgba(217, 162, 153, 0.4);
            transition: 0.3s;
        }
        .btn-shiny::before {
            content: ''; position: absolute; top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.6), transparent);
            transition: 0.5s;
        }
        .btn-shiny:hover::before { left: 100%; }
        .btn-shiny:hover { transform: scale(1.05); }

        /* --- FOUNDER ROW --- */
        .founder-row {
            display: flex; align-items: center; gap: 50px; margin-bottom: 80px;
            opacity: 0; transform: translateY(30px); transition: 1s ease-out;
        }
        .founder-row.visible { opacity: 1; transform: translateY(0); }
        .row-left { flex-direction: row; }
        .row-right { flex-direction: row-reverse; }

        .founder-img-box { flex: 1; }
        .founder-img-box img {
            width: 100%; border-radius: 30px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            filter: grayscale(20%); transition: 0.5s;
        }
        .founder-img-box:hover img { filter: grayscale(0%); transform: scale(1.02); }

        .founder-info { flex: 1.5; }
        .founder-info h2 { font-family: 'Playfair Display'; font-size: 2.5rem; color: var(--primary-pink); margin: 10px 0; }
        .founder-info h4 { text-transform: uppercase; letter-spacing: 2px; color: #888; }
        .founder-info p { line-height: 1.8; color: #666; }

        /* --- TRUST BADGES --- */
        .trust-badges { display: flex; justify-content: space-around; padding: 60px 0; background: var(--primary-pink); color: #fff; text-align: center; }
        .badge i { font-size: 2.5rem; margin-bottom: 10px; }

        /* --- MEDIA GRID --- */
        .media-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 40px; }
        .media-item { border-radius: 15px; overflow: hidden; height: 350px; background: #eee; }
        .media-item img, .media-item video { width: 100%; height: 100%; object-fit: cover; }

        .section-head { text-align: center; margin-bottom: 60px; }

        @media (max-width: 850px) {
            .hero-content h1 { font-size: 2.5rem; }
            .about-grid, .founder-row, .row-right { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

    <div class="bubble-bg">
        <div class="bubble" style="width:80px; height:80px; left:10%;"></div>
        <div class="bubble" style="width:120px; height:120px; left:40%; animation-delay:5s;"></div>
        <div class="bubble" style="width:60px; height:60px; left:70%; animation-delay:2s;"></div>
    </div>

    <?php include("header.php"); ?>

    <section class="hero-banner">
        <div class="hero-content">
            <p>Welcome to Stylevana</p>
            <h1>Our Story</h1>
            <div style="width: 50px; height: 2px; background: #fff; margin: 20px auto;"></div>
            <p style="font-size: 0.9rem; letter-spacing: 4px;">Since 2024</p>
        </div>
    </section>

    <div class="content-section">
        <div class="about-grid">
            <div class="about-text">
                <h2>Defining Your Style</h2>
                <p>Welcome to <b>Shivi's Stylevana</b>. We believe every essential should be elegant. Our journey is about bringing utility and beauty together.</p>
                <a href="terms.php" class="btn-shiny">Terms & Policies</a>
                <a href="privacy.php" class="btn-shiny" style="margin-left:10px;">Privacy Policy</a>
            </div>
            <div class="image-stack">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQTdcOwO5LgJTQ3u4xd-s0pzLQBuaw7Puz6Sg&s" alt="Boutique" class="main-img">
            </div>
        </div>
    </div>

    <div class="content-section">
        <div class="section-head">
            <h2 style="font-family: 'Playfair Display'; font-size: 2.8rem;">Meet the Visionaries</h2>
        </div>

        <div class="founder-row row-left scroll-reveal">
            <div class="founder-img-box">
                <img src="https://mooddp.com/wp-content/uploads/2025/12/cultural-vibe-in-indian-girl-dp.jpg" alt="Shivani Mishra">
            </div>
            <div class="founder-info">
                <h4>Founder & CEO</h4>
                <h2>Shivani Mishra</h2>
                <p>Shivani Mishra started Stylevana with a vision to redefine how we perceive daily essentials. Every detail of the brand reflects elegance and premium quality.</p>
                <a href="terms.php" class="btn-shiny">Terms of Service</a>
            </div>
        </div>

        <div class="founder-row row-right scroll-reveal">
            <div class="founder-img-box">
                <img src="https://images.squarespace-cdn.com/content/v1/64af2e05035dfb736a00fa2a/95ef0923-5fef-402f-9b66-2236e0e75fee/BBBeauty_Boutique_Team.jpg" alt="Creative Crew">
            </div>
            <div class="founder-info">
                <h4>Creative Director</h4>
                <h2>The Creative Crew</h2>
                <p>Behind every great design is a team of visionaries. Our crew ensures that your Stylevana experience is seamless and stylish.</p>
                <a href="privacy.php" class="btn-shiny">Our Privacy Policies</a>
            </div>
        </div>
    </div>

    <section class="trust-badges">
        <div class="badge"><i class="fas fa-check-circle"></i><br>100% Original</div>
        <div class="badge"><i class="fas fa-truck"></i><br>Fast Shipping</div>
        <div class="badge"><i class="fas fa-heart"></i><br>5000+ Happy Users</div>
    </section>

    <div class="content-section">
        <div class="section-head">
            <h2>Stylevana Diaries</h2>
            <p>Moments captured by our community</p>
        </div>
        <div class="media-grid">
            <div class="media-item"><img src="https://www.shutterstock.com/image-photo/happy-woman-curly-hair-glasses-600nw-2693353677.jpg" alt="User"></div>
            <div class="media-item">
                <video src="brandvedio.mp4" autoplay muted loop></video>
            </div>
            <div class="media-item"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQz_IGsgGtMq8FX0uMRQyFRP2PNsWkIfgGUSw&s" alt="User"></div>
        </div>
        <br>
        <div class="media-item">
                <video src="vedio2.mp4" autoplay muted loop></video>
        </div>
    </div>

    <?php include("footer.php"); ?>

    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));
    </script>

</body>
</html>
