<?php
require_once __DIR__ . "/config.php";
// Fetch featured products for display (no login required)
$featured_res = mysqli_query($cn, "SELECT * FROM shop WHERE is_featured=1 ORDER BY pid DESC LIMIT 8");
$categories_res = mysqli_query($cn, "SELECT DISTINCT category FROM shop WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
$total_res = mysqli_query($cn, "SELECT COUNT(*) as c FROM shop");
$total_row = mysqli_fetch_assoc($total_res);
$total_products = $total_row['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shivi's Stylevana | Your Style, Your Story ✨</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,600&family=Josefin+Sans:wght@300;400;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --rose:    #D9A299;
      --rose2:   #c48b81;
      --blush:   #f5ddd9;
      --dark:    #1a1a2e;
      --dark2:   #16213e;
      --cream:   #faf6f3;
      --gold:    #e8c99a;
      --muted:   #8a8fa8;
      --white:   #ffffff;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }

    body {
      font-family: 'Josefin Sans', sans-serif;
      background: var(--cream);
      color: var(--dark);
      overflow-x: hidden;
      cursor: none;
    }

    /* ── CUSTOM CURSOR ── */
    .cursor-dot {
      position: fixed; width: 8px; height: 8px;
      background: var(--rose); border-radius: 50%;
      pointer-events: none; z-index: 99999;
      transform: translate(-50%, -50%);
      transition: transform 0.1s;
    }
    .cursor-ring {
      position: fixed; width: 36px; height: 36px;
      border: 1.5px solid var(--rose);
      border-radius: 50%; pointer-events: none; z-index: 99998;
      transform: translate(-50%, -50%);
      transition: all 0.12s ease;
      opacity: 0.6;
    }
    body:hover .cursor-dot, body:hover .cursor-ring { opacity: 1; }

    /* ── NAVBAR ── */
    .navbar {
      position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
      padding: 20px 60px;
      display: flex; align-items: center; justify-content: space-between;
      transition: all 0.4s ease;
    }
    .navbar.scrolled {
      background: rgba(26,26,46,0.96);
      backdrop-filter: blur(20px);
      padding: 14px 60px;
      box-shadow: 0 4px 30px rgba(0,0,0,0.3);
    }
    .nav-logo {
      font-family: 'Dancing Script', cursive;
      font-size: 28px; color: white;
      text-decoration: none; letter-spacing: 1px;
    }
    .nav-logo span { color: var(--rose); }
    .nav-links { display: flex; gap: 36px; align-items: center; }
    .nav-links a {
      color: rgba(255,255,255,0.8); text-decoration: none;
      font-size: 11px; font-weight: 600; letter-spacing: 2.5px;
      text-transform: uppercase; transition: color 0.3s;
      position: relative;
    }
    .nav-links a::after {
      content: ''; position: absolute; bottom: -4px; left: 0;
      width: 0; height: 1px; background: var(--rose);
      transition: width 0.3s ease;
    }
    .nav-links a:hover { color: var(--rose); }
    .nav-links a:hover::after { width: 100%; }
    .nav-cta {
      background: var(--rose); color: white !important;
      padding: 10px 24px; border-radius: 2px;
      font-size: 11px !important; font-weight: 700 !important;
      letter-spacing: 2px !important;
      transition: background 0.3s, transform 0.2s !important;
    }
    .nav-cta::after { display: none !important; }
    .nav-cta:hover { background: var(--rose2) !important; transform: translateY(-1px); }

    /* ── HERO ── */
    .hero {
      height: 100vh; min-height: 680px;
      position: relative; overflow: hidden;
      display: flex; align-items: center; justify-content: center;
    }
    .hero-bg {
      position: absolute; inset: 0;
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 40%, #2d1b2e 70%, #1a1a2e 100%);
    }
    /* Noise grain overlay */
    .hero-bg::before {
      content: '';
      position: absolute; inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
      opacity: 0.5; pointer-events: none;
    }
    /* Floating orbs */
    .orb {
      position: absolute; border-radius: 50%;
      filter: blur(80px); pointer-events: none;
      animation: float-orb 8s ease-in-out infinite;
    }
    .orb1 { width: 500px; height: 500px; background: rgba(217,162,153,0.12); top: -100px; right: -100px; animation-delay: 0s; }
    .orb2 { width: 350px; height: 350px; background: rgba(232,201,154,0.08); bottom: -80px; left: -60px; animation-delay: -3s; }
    .orb3 { width: 250px; height: 250px; background: rgba(217,162,153,0.1); top: 40%; left: 30%; animation-delay: -6s; }
    @keyframes float-orb {
      0%, 100% { transform: translate(0,0) scale(1); }
      50% { transform: translate(30px,-30px) scale(1.05); }
    }
    /* Grid lines */
    .hero-grid {
      position: absolute; inset: 0;
      background-image: 
        linear-gradient(rgba(217,162,153,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(217,162,153,0.04) 1px, transparent 1px);
      background-size: 60px 60px;
    }

    .hero-content {
      position: relative; z-index: 2;
      text-align: center; padding: 0 20px;
    }
    .hero-eyebrow {
      font-size: 11px; letter-spacing: 5px;
      color: var(--rose); text-transform: uppercase;
      margin-bottom: 24px; font-weight: 600;
      opacity: 0; animation: fade-up 0.8s ease 0.3s forwards;
      display: inline-flex; align-items: center; gap: 12px;
    }
    .hero-eyebrow::before, .hero-eyebrow::after {
      content: ''; display: inline-block;
      width: 30px; height: 1px; background: var(--rose);
    }
    .hero-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(52px, 8vw, 110px);
      line-height: 1; color: white;
      font-weight: 300; letter-spacing: -1px;
      margin-bottom: 12px;
      opacity: 0; animation: fade-up 0.9s ease 0.5s forwards;
    }
    .hero-title em {
      font-style: italic; color: var(--rose);
      font-weight: 300;
    }
    .hero-title .outline-text {
      -webkit-text-stroke: 1px rgba(255,255,255,0.3);
      color: transparent;
    }
    .hero-subtitle {
      font-size: 12px; letter-spacing: 3px;
      color: rgba(255,255,255,0.45); text-transform: uppercase;
      margin-bottom: 48px; font-weight: 400;
      opacity: 0; animation: fade-up 0.9s ease 0.7s forwards;
    }
    .hero-buttons {
      display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;
      opacity: 0; animation: fade-up 0.9s ease 0.9s forwards;
    }
    .btn-primary {
      display: inline-flex; align-items: center; gap: 10px;
      background: var(--rose); color: white;
      text-decoration: none; padding: 16px 40px;
      font-size: 11px; font-weight: 700; letter-spacing: 3px;
      text-transform: uppercase; border-radius: 2px;
      transition: all 0.3s; position: relative; overflow: hidden;
    }
    .btn-primary::before {
      content: ''; position: absolute; top: 0; left: -100%;
      width: 100%; height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }
    .btn-primary:hover::before { left: 100%; }
    .btn-primary:hover { background: var(--rose2); transform: translateY(-2px); box-shadow: 0 12px 30px rgba(217,162,153,0.4); }
    .btn-outline {
      display: inline-flex; align-items: center; gap: 10px;
      border: 1px solid rgba(255,255,255,0.25); color: rgba(255,255,255,0.75);
      text-decoration: none; padding: 16px 40px;
      font-size: 11px; font-weight: 600; letter-spacing: 3px;
      text-transform: uppercase; border-radius: 2px;
      transition: all 0.3s;
    }
    .btn-outline:hover { border-color: var(--rose); color: var(--rose); transform: translateY(-2px); }

    /* Scroll indicator */
    .scroll-indicator {
      position: absolute; bottom: 36px; left: 50%;
      transform: translateX(-50%); z-index: 2;
      display: flex; flex-direction: column; align-items: center; gap: 8px;
      color: rgba(255,255,255,0.3); font-size: 9px; letter-spacing: 3px;
      text-transform: uppercase; animation: pulse-down 2s ease-in-out infinite;
    }
    .scroll-line {
      width: 1px; height: 50px;
      background: linear-gradient(to bottom, rgba(217,162,153,0.6), transparent);
    }
    @keyframes pulse-down {
      0%, 100% { transform: translateX(-50%) translateY(0); opacity: 0.6; }
      50% { transform: translateX(-50%) translateY(8px); opacity: 1; }
    }

    @keyframes fade-up {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── SHIMMER MARQUEE ── */
    .marquee-strip {
      background: var(--dark);
      padding: 14px 0; overflow: hidden;
      border-top: 1px solid rgba(217,162,153,0.15);
      border-bottom: 1px solid rgba(217,162,153,0.15);
    }
    .marquee-track {
      display: flex; gap: 0;
      animation: marquee 25s linear infinite;
      width: max-content;
    }
    .marquee-item {
      display: inline-flex; align-items: center; gap: 20px;
      padding: 0 40px;
      font-size: 10px; letter-spacing: 3px; text-transform: uppercase;
      color: rgba(255,255,255,0.5); font-weight: 600; white-space: nowrap;
    }
    .marquee-item .dot { width: 4px; height: 4px; background: var(--rose); border-radius: 50%; flex-shrink: 0; }
    @keyframes marquee {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); }
    }

    /* ── STATS ROW ── */
    .stats-section {
      background: white; padding: 48px 60px;
      display: grid; grid-template-columns: repeat(4, 1fr);
      border-bottom: 1px solid #f0ebe8;
    }
    .stat-item { text-align: center; padding: 20px; border-right: 1px solid #f0ebe8; }
    .stat-item:last-child { border-right: none; }
    .stat-num {
      font-family: 'Cormorant Garamond', serif;
      font-size: 44px; font-weight: 600; color: var(--dark);
      line-height: 1;
    }
    .stat-num span { color: var(--rose); }
    .stat-label { font-size: 10px; letter-spacing: 2px; color: var(--muted); text-transform: uppercase; margin-top: 8px; font-weight: 600; }

    /* ── CATEGORIES SECTION ── */
    .section { padding: 80px 60px; }
    .section-head { text-align: center; margin-bottom: 56px; }
    .section-eyebrow {
      font-size: 10px; letter-spacing: 4px; color: var(--rose);
      text-transform: uppercase; font-weight: 700; margin-bottom: 14px;
      display: flex; align-items: center; justify-content: center; gap: 12px;
    }
    .section-eyebrow::before, .section-eyebrow::after {
      content: ''; display: inline-block; width: 40px; height: 1px; background: var(--blush);
    }
    .section-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(32px, 4vw, 52px); font-weight: 300;
      color: var(--dark); line-height: 1.15;
    }
    .section-title em { font-style: italic; color: var(--rose); }

    .cat-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
    }
    .cat-card {
      position: relative; overflow: hidden;
      border-radius: 4px; cursor: pointer;
      height: 320px;
      /* Shimmer on load */
      background: linear-gradient(90deg, #f0ebe8 25%, #faf6f3 50%, #f0ebe8 75%);
      background-size: 200% 100%;
      animation: shimmer 1.5s ease-in-out infinite;
    }
    .cat-card.loaded { animation: none; background: none; }
    @keyframes shimmer {
      from { background-position: 200% 0; }
      to   { background-position: -200% 0; }
    }
    .cat-card img {
      width: 100%; height: 100%; object-fit: cover;
      transition: transform 0.7s ease;
    }
    .cat-card:hover img { transform: scale(1.08); }
    .cat-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(to top, rgba(26,26,46,0.85) 0%, rgba(26,26,46,0.1) 60%, transparent 100%);
      display: flex; flex-direction: column;
      align-items: flex-start; justify-content: flex-end;
      padding: 28px;
    }
    .cat-icon { font-size: 28px; margin-bottom: 8px; }
    .cat-name {
      font-family: 'Cormorant Garamond', serif;
      font-size: 26px; color: white; font-weight: 600; line-height: 1.1;
    }
    .cat-tagline { font-size: 10px; color: rgba(255,255,255,0.55); letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
    .cat-btn {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--rose); color: white;
      text-decoration: none; padding: 8px 18px;
      font-size: 9px; letter-spacing: 2px; text-transform: uppercase;
      font-weight: 700; border-radius: 2px; margin-top: 16px;
      transform: translateY(10px); opacity: 0;
      transition: all 0.3s ease;
    }
    .cat-card:hover .cat-btn { transform: translateY(0); opacity: 1; }

    /* ── FEATURED PRODUCTS ── */
    .products-section { padding: 80px 60px; background: var(--cream); }
    .prod-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 24px;
    }

    /* Shimmer card placeholder */
    .shimmer-card {
      border-radius: 6px; overflow: hidden;
      background: white;
    }
    .shimmer-img {
      height: 280px;
      background: linear-gradient(90deg, #f0ebe8 25%, #faf6f3 50%, #f0ebe8 75%);
      background-size: 200% 100%;
      animation: shimmer 1.4s ease-in-out infinite;
    }
    .shimmer-line {
      height: 12px; border-radius: 4px; margin: 14px 16px 8px;
      background: linear-gradient(90deg, #f0ebe8 25%, #faf6f3 50%, #f0ebe8 75%);
      background-size: 200% 100%;
      animation: shimmer 1.4s ease-in-out infinite;
    }
    .shimmer-line.short { width: 60%; margin-top: 0; }

    .prod-card {
      background: white; border-radius: 6px; overflow: hidden;
      position: relative;
      box-shadow: 0 2px 16px rgba(0,0,0,0.04);
      transition: transform 0.35s ease, box-shadow 0.35s ease;
    }
    .prod-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    .prod-img-wrap { position: relative; height: 280px; overflow: hidden; background: #f9f6f4; }
    .prod-img-wrap img {
      width: 100%; height: 100%; object-fit: cover;
      transition: transform 0.5s ease;
    }
    .prod-card:hover .prod-img-wrap img { transform: scale(1.06); }
    .prod-overlay {
      position: absolute; inset: 0;
      background: rgba(26,26,46,0.5);
      display: flex; align-items: center; justify-content: center;
      opacity: 0; transition: opacity 0.3s;
    }
    .prod-card:hover .prod-overlay { opacity: 1; }
    .prod-overlay a {
      background: white; color: var(--dark);
      text-decoration: none; padding: 12px 28px;
      font-size: 10px; letter-spacing: 2.5px; text-transform: uppercase;
      font-weight: 700; border-radius: 2px;
      transform: translateY(10px); transition: transform 0.3s;
    }
    .prod-card:hover .prod-overlay a { transform: translateY(0); }
    .prod-badge {
      position: absolute; top: 12px; left: 12px;
      background: var(--dark); color: var(--rose);
      font-size: 9px; letter-spacing: 2px; text-transform: uppercase;
      font-weight: 700; padding: 4px 10px; border-radius: 2px;
    }
    .prod-info { padding: 16px; }
    .prod-brand { font-size: 9px; letter-spacing: 2px; color: var(--rose); text-transform: uppercase; font-weight: 700; margin-bottom: 4px; }
    .prod-name {
      font-family: 'Cormorant Garamond', serif;
      font-size: 18px; color: var(--dark); font-weight: 600;
      line-height: 1.3; margin-bottom: 10px;
    }
    .prod-price {
      font-size: 16px; font-weight: 700; color: var(--dark);
    }
    .prod-price-login {
      font-size: 11px; color: var(--muted); letter-spacing: 1px;
      margin-top: 2px;
    }
    .prod-lock-btn {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--dark); color: white;
      text-decoration: none; padding: 10px 20px;
      font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
      font-weight: 700; border-radius: 2px; margin-top: 12px;
      width: 100%; justify-content: center;
      transition: background 0.3s;
    }
    .prod-lock-btn:hover { background: var(--rose); }

    /* ── FULLWIDTH BANNER ── */
    .banner-section {
      position: relative; height: 420px; overflow: hidden;
      display: flex; align-items: center;
    }
    .banner-bg {
      position: absolute; inset: 0;
      background: linear-gradient(135deg, var(--dark) 0%, #2d1b2e 50%, #1a1a2e 100%);
    }
    .banner-deco {
      position: absolute; right: 0; top: 0; bottom: 0; width: 50%;
      background: url('https://images.unsplash.com/photo-1512436991641-6745cdb1723f?w=800&q=80') center/cover;
      opacity: 0.25;
    }
    .banner-content {
      position: relative; z-index: 2; padding: 0 80px;
      max-width: 600px;
    }
    .banner-tag {
      font-size: 10px; letter-spacing: 4px; color: var(--gold);
      text-transform: uppercase; font-weight: 700; margin-bottom: 16px;
    }
    .banner-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(36px, 5vw, 60px); color: white;
      font-weight: 300; line-height: 1.1; margin-bottom: 20px;
    }
    .banner-title em { font-style: italic; color: var(--rose); }
    .banner-sub { font-size: 13px; color: rgba(255,255,255,0.5); line-height: 1.8; margin-bottom: 32px; letter-spacing: 0.5px; }
    .banner-offer {
      display: inline-block; background: var(--rose);
      color: white; padding: 4px 16px; border-radius: 2px;
      font-size: 12px; font-weight: 700; letter-spacing: 2px;
      margin-bottom: 24px; animation: glow 2s ease-in-out infinite;
    }
    @keyframes glow {
      0%, 100% { box-shadow: 0 0 0 rgba(217,162,153,0); }
      50% { box-shadow: 0 0 20px rgba(217,162,153,0.5); }
    }

    /* ── TESTIMONIALS ── */
    .testimonials { padding: 80px 60px; background: white; }
    .test-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-top: 48px; }
    .test-card {
      padding: 32px; border: 1px solid #f0ebe8; border-radius: 4px;
      position: relative; transition: all 0.3s;
    }
    .test-card:hover { border-color: var(--rose); transform: translateY(-4px); }
    .test-quote {
      font-family: 'Cormorant Garamond', serif;
      font-size: 15px; color: #555; line-height: 1.8;
      font-style: italic; margin-bottom: 20px;
    }
    .test-stars { color: var(--rose); font-size: 12px; margin-bottom: 14px; letter-spacing: 2px; }
    .test-author { font-size: 11px; font-weight: 700; color: var(--dark); letter-spacing: 2px; text-transform: uppercase; }
    .test-loc { font-size: 10px; color: var(--muted); letter-spacing: 1px; margin-top: 3px; }
    .test-card::before {
      content: '\201C';
      font-family: 'Cormorant Garamond', serif;
      font-size: 80px; color: var(--blush);
      position: absolute; top: 10px; right: 20px;
      line-height: 1; font-weight: 700;
    }

    /* ── NEWSLETTER ── */
    .newsletter {
      padding: 80px 60px;
      background: var(--dark);
      text-align: center;
    }
    .newsletter h2 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 44px; color: white; font-weight: 300; margin-bottom: 12px;
    }
    .newsletter h2 em { font-style: italic; color: var(--rose); }
    .newsletter p { font-size: 12px; color: rgba(255,255,255,0.45); letter-spacing: 1.5px; margin-bottom: 36px; }
    .newsletter-form {
      display: flex; gap: 0; max-width: 440px; margin: 0 auto;
      border: 1px solid rgba(255,255,255,0.1); border-radius: 2px; overflow: hidden;
    }
    .newsletter-form input {
      flex: 1; padding: 16px 20px;
      background: rgba(255,255,255,0.05); border: none; outline: none;
      color: white; font-family: 'Josefin Sans', sans-serif;
      font-size: 12px; letter-spacing: 1px;
    }
    .newsletter-form input::placeholder { color: rgba(255,255,255,0.3); }
    .newsletter-form button {
      background: var(--rose); color: white; border: none;
      padding: 16px 28px; font-family: 'Josefin Sans', sans-serif;
      font-size: 10px; font-weight: 700; letter-spacing: 2.5px;
      text-transform: uppercase; cursor: pointer; transition: background 0.3s;
    }
    .newsletter-form button:hover { background: var(--rose2); }

    /* ── FOOTER ── */
    .footer {
      background: #111827; padding: 60px 60px 32px;
      color: rgba(255,255,255,0.5);
    }
    .footer-grid {
      display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 48px; margin-bottom: 48px;
    }
    .footer-brand .logo-text {
      font-family: 'Dancing Script', cursive;
      font-size: 30px; color: white; margin-bottom: 14px;
    }
    .footer-brand .logo-text span { color: var(--rose); }
    .footer-brand p { font-size: 12px; line-height: 1.9; max-width: 260px; }
    .footer-socials { display: flex; gap: 12px; margin-top: 20px; }
    .footer-social {
      width: 38px; height: 38px; border: 1px solid rgba(255,255,255,0.1);
      border-radius: 2px; display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,0.5); text-decoration: none;
      font-size: 13px; transition: all 0.3s;
    }
    .footer-social:hover { border-color: var(--rose); color: var(--rose); transform: translateY(-2px); }
    .footer-col h4 { font-size: 10px; letter-spacing: 3px; color: white; text-transform: uppercase; font-weight: 700; margin-bottom: 20px; }
    .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
    .footer-col ul a { text-decoration: none; color: rgba(255,255,255,0.45); font-size: 12px; transition: color 0.3s; letter-spacing: 0.5px; }
    .footer-col ul a:hover { color: var(--rose); }
    .footer-bottom { border-top: 1px solid rgba(255,255,255,0.06); padding-top: 28px; text-align: center; font-size: 11px; letter-spacing: 1px; }

    /* ── REVEAL ANIMATIONS ── */
    .reveal { opacity: 0; transform: translateY(40px); transition: all 0.8s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
      .navbar { padding: 16px 24px; }
      .navbar.scrolled { padding: 12px 24px; }
      .nav-links { display: none; }
      .stats-section { grid-template-columns: repeat(2,1fr); }
      .section, .products-section, .testimonials, .newsletter { padding: 60px 24px; }
      .banner-content { padding: 0 32px; }
      .footer-grid { grid-template-columns: 1fr 1fr; }
      .footer { padding: 48px 24px 24px; }
    }
    @media (max-width: 600px) {
      .hero-title { font-size: 46px; }
      .cat-grid { grid-template-columns: 1fr 1fr; }
      .cat-card { height: 200px; }
      .footer-grid { grid-template-columns: 1fr; }
      .stats-section { grid-template-columns: repeat(2,1fr); padding: 32px 20px; }
    }
  </style>
</head>
<body>

<!-- Custom cursor -->
<div class="cursor-dot" id="cursorDot"></div>
<div class="cursor-ring" id="cursorRing"></div>

<!-- ── NAVBAR ── -->
<nav class="navbar" id="navbar">
  <a href="index.php" class="nav-logo">Shivi's <span>Stylevana</span></a>
  <div class="nav-links">
    <a href="#categories">Collections</a>
    <a href="#featured">Featured</a>
    <a href="about.php">About</a>
    <a href="login.php" class="nav-cta">Login / Sign Up</a>
  </div>
</nav>

<!-- ── HERO ── -->
<section class="hero">
  <div class="hero-bg">
    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="orb orb3"></div>
    <div class="hero-grid"></div>
  </div>
  <div class="hero-content">
    <div class="hero-eyebrow">New Season 2026</div>
    <h1 class="hero-title">
      Your Style,<br>
      <em>Your</em> <span class="outline-text">Story</span>
    </h1>
    <p class="hero-subtitle">Fashion · Skincare · Makeup · Jewellery</p>
    <div class="hero-buttons">
      <a href="login.php" class="btn-primary">
        <i class="fas fa-shopping-bag"></i> Shop Now
      </a>
      <a href="#featured" class="btn-outline">
        <i class="fas fa-arrow-down"></i> Explore
      </a>
    </div>
  </div>
  <div class="scroll-indicator">
    <div class="scroll-line"></div>
    <span>Scroll</span>
  </div>
</section>

<!-- ── MARQUEE ── -->
<div class="marquee-strip">
  <div class="marquee-track">
    <?php for($i=0;$i<4;$i++): ?>
    <span class="marquee-item"><span class="dot"></span> Free Shipping Above ₹499</span>
    <span class="marquee-item"><span class="dot"></span> 100% Authentic Products</span>
    <span class="marquee-item"><span class="dot"></span> Easy 7-Day Returns</span>
    <span class="marquee-item"><span class="dot"></span> New Arrivals Every Week</span>
    <span class="marquee-item"><span class="dot"></span> Exclusive Member Offers</span>
    <?php endfor; ?>
  </div>
</div>

<!-- ── STATS ── -->
<div class="stats-section reveal">
  <div class="stat-item">
    <div class="stat-num" data-target="<?= $total_products ?>">0<span>+</span></div>
    <div class="stat-label">Products</div>
  </div>
  <div class="stat-item">
    <?php $cat_count = mysqli_num_rows($categories_res); ?>
    <div class="stat-num" data-target="<?= $cat_count ?>">0<span>+</span></div>
    <div class="stat-label">Categories</div>
  </div>
  <div class="stat-item">
    <div class="stat-num" data-target="5000">0<span>+</span></div>
    <div class="stat-label">Happy Customers</div>
  </div>
  <div class="stat-item">
    <div class="stat-num" data-target="4">0<span>.9 ★</span></div>
    <div class="stat-label">Average Rating</div>
  </div>
</div>

<!-- ── CATEGORIES ── -->
<section class="section reveal" id="categories">
  <div class="section-head">
    <div class="section-eyebrow">Collections</div>
    <h2 class="section-title">Shop by <em>Category</em></h2>
  </div>
  <div class="cat-grid">
    <?php
    $cat_meta = [
      'jewellery' => ['icon'=>'💎','name'=>'Jewellery','tag'=>'Timeless Elegance','img'=>'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&q=80'],
      'makeup'    => ['icon'=>'💄','name'=>'Makeup','tag'=>'Define Your Beauty','img'=>'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=600&q=80'],
      'skincare'  => ['icon'=>'✨','name'=>'Skincare','tag'=>'Glow From Within','img'=>'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=600&q=80'],
      'clothes'   => ['icon'=>'👗','name'=>'Fashion','tag'=>'Express Yourself','img'=>'https://images.unsplash.com/photo-1445205170230-053b83016050?w=600&q=80'],
      'clothing'  => ['icon'=>'👗','name'=>'Fashion','tag'=>'Express Yourself','img'=>'https://images.unsplash.com/photo-1445205170230-053b83016050?w=600&q=80'],
    ];
    mysqli_data_seek($categories_res, 0);
    $shown_cats = [];
    while($cr = mysqli_fetch_assoc($categories_res)):
      $cat = $cr['category'];
      $key = strtolower(trim($cat));
      if(in_array($key, $shown_cats)) continue;
      $shown_cats[] = $key;
      $m = $cat_meta[$key] ?? ['icon'=>'🛍️','name'=>ucfirst($cat),'tag'=>'Explore Now','img'=>'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&q=80'];
    ?>
    <div class="cat-card" onclick="window.location='login.php'">
      <img src="<?= $m['img'] ?>" alt="<?= htmlspecialchars($m['name']) ?>"
           onload="this.closest('.cat-card').classList.add('loaded')"
           onerror="this.closest('.cat-card').classList.add('loaded')">
      <div class="cat-overlay">
        <div class="cat-icon"><?= $m['icon'] ?></div>
        <div class="cat-name"><?= $m['name'] ?></div>
        <div class="cat-tagline"><?= $m['tag'] ?></div>
        <a href="login.php" class="cat-btn">Explore <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</section>

<!-- ── FEATURED PRODUCTS ── -->
<section class="products-section reveal" id="featured">
  <div class="section-head">
    <div class="section-eyebrow">⭐ Handpicked For You</div>
    <h2 class="section-title"><em>Featured</em> Picks</h2>
  </div>
  <div class="prod-grid" id="prodGrid">
    <!-- Shimmer placeholders -->
    <?php for($i=0;$i<8;$i++): ?>
    <div class="shimmer-card" id="shimmer-<?=$i?>">
      <div class="shimmer-img"></div>
      <div class="shimmer-line"></div>
      <div class="shimmer-line short"></div>
    </div>
    <?php endfor; ?>
  </div>
</section>

<!-- ── BANNER ── -->
<section class="banner-section reveal">
  <div class="banner-bg"></div>
  <div class="banner-deco"></div>
  <div class="banner-content">
    <div class="banner-tag">✦ Limited Time Offer</div>
    <div class="banner-offer">UP TO 30% OFF</div>
    <h2 class="banner-title">Jewellery that tells your <em>story</em></h2>
    <p class="banner-sub">Handcrafted pieces, timeless designs. Discover our exclusive jewellery collection.</p>
    <a href="login.php" class="btn-primary"><i class="fas fa-gem"></i> Shop Jewellery</a>
  </div>
</section>

<!-- ── TESTIMONIALS ── -->
<section class="testimonials reveal">
  <div class="section-head">
    <div class="section-eyebrow">Love Notes</div>
    <h2 class="section-title">What our customers <em>say</em></h2>
  </div>
  <div class="test-grid">
    <div class="test-card">
      <div class="test-stars">★★★★★</div>
      <div class="test-quote">Absolutely love the quality! The jewellery pieces are stunning and the packaging was so beautiful. Will definitely shop again.</div>
      <div class="test-author">Priya Sharma</div>
      <div class="test-loc">Mumbai, India</div>
    </div>
    <div class="test-card">
      <div class="test-stars">★★★★★</div>
      <div class="test-quote">The skincare range is incredible. My skin has never felt better. Fast delivery and everything was exactly as described!</div>
      <div class="test-author">Ananya Singh</div>
      <div class="test-loc">Delhi, India</div>
    </div>
    <div class="test-card">
      <div class="test-stars">★★★★★</div>
      <div class="test-quote">Stylevana is my go-to for everything! Fashion, makeup, accessories — all in one place. Best online store hands down.</div>
      <div class="test-author">Riya Patel</div>
      <div class="test-loc">Bangalore, India</div>
    </div>
  </div>
</section>

<!-- ── NEWSLETTER ── -->
<section class="newsletter reveal">
  <h2>Join the <em>Stylevana</em> Family</h2>
  <p>Get exclusive offers, early access to new arrivals & style tips</p>
  <div class="newsletter-form">
    <input type="email" placeholder="Your email address...">
    <button onclick="alert('Thank you! We will keep you updated ✨')">Subscribe</button>
  </div>
</section>

<!-- ── FOOTER ── -->
<footer class="footer">
  <div class="footer-grid">
    <div class="footer-brand">
      <div class="logo-text">Shivi's <span>Stylevana</span></div>
      <p>Your all-in-one destination for fashion, skincare, makeup, and jewellery. Curated with love for every woman.</p>
      <div class="footer-socials">
        <a href="#" class="footer-social"><i class="fab fa-instagram"></i></a>
        <a href="#" class="footer-social"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="footer-social"><i class="fab fa-pinterest-p"></i></a>
        <a href="#" class="footer-social"><i class="fab fa-twitter"></i></a>
      </div>
    </div>
    <div class="footer-col">
      <h4>Shop</h4>
      <ul>
        <li><a href="login.php">All Products</a></li>
        <li><a href="login.php">New Arrivals</a></li>
        <li><a href="login.php">Featured Picks</a></li>
        <li><a href="login.php">Sale</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Categories</h4>
      <ul>
        <li><a href="login.php">Fashion</a></li>
        <li><a href="login.php">Skincare</a></li>
        <li><a href="login.php">Makeup</a></li>
        <li><a href="login.php">Jewellery</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Help</h4>
      <ul>
        <li><a href="login.php">My Orders</a></li>
        <li><a href="#">Returns Policy</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="about.php">About Us</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    &copy; 2026 Shivi's Stylevana. Made with ♥ All Rights Reserved.
  </div>
</footer>

<script>
// ── Custom Cursor ──
var dot = document.getElementById('cursorDot');
var ring = document.getElementById('cursorRing');
document.addEventListener('mousemove', function(e) {
  dot.style.left = e.clientX + 'px';
  dot.style.top  = e.clientY + 'px';
  ring.style.left = e.clientX + 'px';
  ring.style.top  = e.clientY + 'px';
});
document.querySelectorAll('a,button').forEach(function(el) {
  el.addEventListener('mouseenter', function() {
    ring.style.transform = 'translate(-50%,-50%) scale(1.8)';
    ring.style.borderColor = '#D9A299';
  });
  el.addEventListener('mouseleave', function() {
    ring.style.transform = 'translate(-50%,-50%) scale(1)';
    ring.style.borderColor = '#D9A299';
  });
});

// ── Navbar scroll effect ──
window.addEventListener('scroll', function() {
  var nav = document.getElementById('navbar');
  if (window.scrollY > 60) nav.classList.add('scrolled');
  else nav.classList.remove('scrolled');
});

// ── Reveal on scroll ──
var revealEls = document.querySelectorAll('.reveal');
var observer = new IntersectionObserver(function(entries) {
  entries.forEach(function(entry) {
    if (entry.isIntersecting) { entry.target.classList.add('visible'); }
  });
}, { threshold: 0.1 });
revealEls.forEach(function(el) { observer.observe(el); });

// ── Counter animation ──
function animateCounter(el, target, suffix) {
  var start = 0, duration = 1800;
  var startTime = null;
  function step(timestamp) {
    if (!startTime) startTime = timestamp;
    var progress = Math.min((timestamp - startTime) / duration, 1);
    var ease = 1 - Math.pow(1 - progress, 3);
    var current = Math.floor(ease * target);
    el.innerHTML = current.toLocaleString() + suffix;
    if (progress < 1) requestAnimationFrame(step);
  }
  requestAnimationFrame(step);
}
var counterObserver = new IntersectionObserver(function(entries) {
  entries.forEach(function(entry) {
    if (entry.isIntersecting) {
      var el = entry.target;
      var target = parseInt(el.getAttribute('data-target'));
      var suffix = el.querySelector('span') ? el.querySelector('span').outerHTML : '';
      animateCounter(el, target, suffix);
      counterObserver.unobserve(el);
    }
  });
}, { threshold: 0.5 });
document.querySelectorAll('.stat-num[data-target]').forEach(function(el) { counterObserver.observe(el); });

// ── Load featured products (replace shimmer cards) ──
(function() {
  var products = <?php
    $prods = [];
    if($featured_res && mysqli_num_rows($featured_res) > 0) {
      mysqli_data_seek($featured_res, 0);
      while($r = mysqli_fetch_assoc($featured_res)) {
        $prods[] = [
          'pid'   => $r['pid'],
          'name'  => $r['productname'],
          'brand' => $r['brand_name'] ?? 'Stylevana',
          'price' => $r['productprice'],
          'photo' => $r['productphoto'],
          'cat'   => $r['category']
        ];
      }
    }
    echo json_encode($prods);
  ?>;

  var grid = document.getElementById('prodGrid');
  if (!grid || products.length === 0) return;

  // Remove shimmer cards and render real ones
  setTimeout(function() {
    grid.innerHTML = '';
    products.forEach(function(p, i) {
      var card = document.createElement('div');
      card.className = 'prod-card';
      card.style.animationDelay = (i * 0.1) + 's';
      card.innerHTML = `
        <div class="prod-img-wrap">
          <img src="${p.photo}" alt="${p.name}"
               onerror="this.src='https://via.placeholder.com/300x300/faf6f3/D9A299?text=No+Image'">
          <div class="prod-overlay">
            <a href="login.php"><i class="fas fa-lock"></i>&nbsp; Login to Buy</a>
          </div>
          <div class="prod-badge">⭐ Featured</div>
        </div>
        <div class="prod-info">
          <div class="prod-brand">${p.brand}</div>
          <div class="prod-name">${p.name}</div>
          <div class="prod-price">₹${parseInt(p.price).toLocaleString()}</div>
          <div class="prod-price-login">Login to add to cart or wishlist</div>
          <a href="login.php" class="prod-lock-btn"><i class="fas fa-shopping-bag"></i> Shop Now</a>
        </div>
      `;
      grid.appendChild(card);
    });
  }, 800);
})();
</script>
</body>
</html>