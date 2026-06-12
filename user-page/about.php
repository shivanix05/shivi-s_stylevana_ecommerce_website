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
        <link rel="stylesheet" href="about.css">

    


</head>
<body>

    <div class="bubble-bg">
        <div class="bubble" style="width:80px; height:80px; left:10%;"></div>
        <div class="bubble" style="width:120px; height:120px; left:40%; animation-delay:5s;"></div>
        <div class="bubble" style="width:60px; height:60px; left:70%; animation-delay:2s;"></div>
    </div>

    <?php include("header.php"); ?>

   <section class="hero-banner" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('image6.png'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 60vh; display: flex; align-items: center; justify-content: center; text-align: center;">
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
