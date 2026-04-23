<?php
// 1. Constants
include('config/constants.php');

// 2. Functions
include('includes/functions.php');

// 3. Header
include('includes/header.php');

// DB Connection
if(file_exists("config/db.php")) {
    include_once("config/db.php");
} else {
    if(file_exists("db.php")) { include_once("db.php"); }
}

// Fetch categories for display
$home_categories = [];
if(isset($conn)){
    $hc = mysqli_query($conn, "SELECT c.id, c.name, c.image, COUNT(p.id) as product_count 
                               FROM categories c 
                               LEFT JOIN products p ON c.id = p.category_id AND p.status=0 
                               WHERE c.status=0 
                               GROUP BY c.id 
                               ORDER BY product_count DESC 
                               LIMIT 8");
    while($r = mysqli_fetch_assoc($hc)){
        $home_categories[] = $r;
    }
}

// Category icon mapping (reuse from header)
$cat_icons = [
    'electronics' => 'fa-laptop', 'fashion' => 'fa-shirt',
    'home & kitchen' => 'fa-house', 'books' => 'fa-book',
    'sports & outdoors' => 'fa-futbol', 'beauty & personal care' => 'fa-spa',
    'toys & games' => 'fa-gamepad', 'grocery & gourmet' => 'fa-cart-shopping',
    'health & wellness' => 'fa-heart-pulse', 'automotive' => 'fa-car',
    'baby products' => 'fa-baby', 'pet supplies' => 'fa-paw',
    'office supplies' => 'fa-briefcase', 'garden & outdoors' => 'fa-leaf',
    'jewelry & watches' => 'fa-gem', 'shoes & handbags' => 'fa-shoe-prints',
    'music & instruments' => 'fa-music', 'mobiles' => 'fa-mobile-screen',
    'laptops' => 'fa-laptop', 'cameras' => 'fa-camera',
    'audio' => 'fa-headphones', 'accessories' => 'fa-plug',
    'smartphones' => 'fa-mobile-screen', 'computers' => 'fa-desktop',
    'headphones' => 'fa-headphones', 'smartwatches' => 'fa-clock',
    'tablets' => 'fa-tablet-screen-button', 'gaming' => 'fa-gamepad',
    'wearables' => 'fa-clock',
];
function getIcon($name, $icons) {
    $lower = strtolower(trim($name));
    return isset($icons[$lower]) ? $icons[$lower] : 'fa-tag';
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* --- Global & Fonts --- */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary-color: #2563eb;
        --primary-dark: #1e40af;
        --text-dark: #1e293b;
        --text-gray: #64748b;
        --bg-light: #f1f5f9;
        --white: #ffffff;
        --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        --radius: 12px;
        --amazon-orange: #febd69;
        --success: #16a34a;
        --danger: #ef4444;
    }

    body {
        font-family: 'Poppins', sans-serif;
        margin: 0; padding: 0;
        box-sizing: border-box;
        background-color: var(--bg-light);
        color: var(--text-dark);
        overflow-x: hidden;
    }

    a { text-decoration: none; color: inherit; transition: 0.3s ease; }
    img { max-width: 100%; display: block; }

    /* ===== HERO SLIDER ===== */
    .slider-container {
        position: relative;
        width: 100%;
        min-height: 420px;
        overflow: hidden;
    }
    .slider-wrapper {
        display: flex;
        width: 100%;
        height: 100%;
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .slide {
        width: 100%;
        flex: 0 0 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 50px 8%;
        box-sizing: border-box;
        position: relative;
    }
    .slide:nth-child(1) { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .slide:nth-child(2) { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .slide:nth-child(3) { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .slide:nth-child(4) { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }

    .hero-text { flex: 1; max-width: 550px; z-index: 2; padding-right: 30px; color: #fff; }
    .hero-tag {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        color: #fff;
        padding: 8px 18px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 15px;
    }
    .hero-text h1 {
        font-size: 2.8rem;
        line-height: 1.15;
        color: #fff;
        margin-bottom: 15px;
        font-weight: 800;
    }
    .hero-text p {
        color: rgba(255,255,255,0.9);
        font-size: 1rem;
        margin-bottom: 25px;
        line-height: 1.6;
    }
    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background-color: #fff;
        color: var(--text-dark);
        padding: 14px 32px;
        border-radius: 50px;
        font-weight: 600;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        transition: 0.3s;
    }
    .hero-btn:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(0,0,0,0.2); }

    .hero-image {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1;
    }
    .hero-image img {
        max-height: 350px;
        max-width: 100%;
        filter: drop-shadow(0 30px 60px rgba(0,0,0,0.3));
        animation: float 4s ease-in-out infinite;
        object-fit: contain;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }

    /* Slider Navigation */
    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 45px; height: 45px;
        background: rgba(255,255,255,0.9);
        border: none;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: var(--text-dark);
        transition: 0.3s;
    }
    .slider-btn:hover { background: #fff; transform: translateY(-50%) scale(1.1); }
    .prev-btn { left: 20px; }
    .next-btn { right: 20px; }

    /* Slider Dots */
    .slider-dots {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 10;
    }
    .slider-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: rgba(255,255,255,0.4);
        cursor: pointer;
        transition: 0.3s;
    }
    .slider-dot.active { background: #fff; width: 28px; border-radius: 5px; }

    @media (max-width: 991px) {
        .slide {
            flex-direction: column-reverse;
            text-align: center;
            padding: 30px 5%;
            justify-content: center;
            gap: 15px;
        }
        .hero-text { margin: 0 auto; width: 100%; padding-right: 0; }
        .hero-text h1 { font-size: 2rem; }
        .hero-image img { max-height: 200px; width: auto; }
        .slider-btn { width: 36px; height: 36px; font-size: 0.9rem; }
        .prev-btn { left: 8px; }
        .next-btn { right: 8px; }
    }

    /* ===== FEATURES BAR ===== */
    .features-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        padding: 0 5%;
        margin-top: -30px;
        position: relative;
        z-index: 10;
        margin-bottom: 40px;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }
    .feature-item {
        background: var(--white); padding: 20px; border-radius: var(--radius);
        box-shadow: 0 8px 25px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 12px;
        transition: transform 0.3s ease; border-bottom: 3px solid transparent;
    }
    .feature-item:hover { transform: translateY(-3px); border-bottom-color: var(--primary-color); }
    .feature-icon {
        font-size: 22px; color: var(--primary-color); background: #eff6ff; width: 48px; height: 48px;
        display: flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0;
    }
    .feature-text h4 { font-size: 0.9rem; font-weight: 600; margin: 0 0 3px 0; }
    .feature-text p { font-size: 0.78rem; color: var(--text-gray); margin: 0; }

    /* ===== SECTION CONTAINERS ===== */
    .section-container {
        padding: 15px 5%;
        margin: 30px auto;
        max-width: 1300px;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 25px;
    }
    .section-title h2 { font-size: 1.6rem; font-weight: 700; margin: 0; }
    .section-title span { color: var(--primary-color); }
    .section-title p { font-size: 0.85rem; color: var(--text-gray); margin-top: 3px; }
    .view-all-btn {
        color: var(--primary-color); font-weight: 600;
        display: flex; align-items: center; gap: 8px;
        font-size: 0.9rem;
    }
    .view-all-btn:hover { gap: 12px; }

    /* ===== CATEGORY GRID (AMAZON STYLE) ===== */
    .cat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 20px;
    }
    .cat-card {
        background: var(--white);
        border: 1px solid #e8e8e8;
        padding: 25px 15px;
        border-radius: 16px;
        text-align: center;
        transition: 0.3s;
        display: block;
    }
    .cat-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 10px 25px rgba(37,99,235,0.1);
        transform: translateY(-5px);
    }
    .cat-icon-box {
        width: 60px; height: 60px;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 12px;
        font-size: 1.4rem;
        color: var(--primary-color);
        transition: 0.3s;
    }
    .cat-card:hover .cat-icon-box {
        background: var(--primary-color);
        color: #fff;
        transform: scale(1.1);
    }
    .cat-card h3 { font-size: 0.85rem; font-weight: 600; margin: 0; color: var(--text-dark); }
    .cat-card .cat-count { font-size: 0.75rem; color: var(--text-gray); margin-top: 3px; }

    /* ===== PROMO BANNERS ===== */
    .promo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin: 40px auto;
        max-width: 1300px;
        padding: 0 5%;
    }
    .promo-card {
        border-radius: 16px;
        padding: 35px 30px;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: 0.3s;
    }
    .promo-card:hover { transform: translateY(-5px); }
    .promo-card h3 { font-size: 1.3rem; font-weight: 700; margin-bottom: 8px; }
    .promo-card p { font-size: 0.9rem; opacity: 0.9; margin-bottom: 18px; }
    .promo-btn {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);
        padding: 10px 22px; border-radius: 25px;
        font-weight: 600; font-size: 0.85rem;
        transition: 0.3s; color: #fff;
    }
    .promo-btn:hover { background: rgba(255,255,255,0.3); }
    .promo-1 { background: linear-gradient(135deg, #ff6b35, #f7931e); }
    .promo-2 { background: linear-gradient(135deg, #667eea, #764ba2); }
    .promo-3 { background: linear-gradient(135deg, #11998e, #38ef7d); }

    /* ===== DEALS TIMER ===== */
    .deal-timer {
        display: flex; gap: 10px; align-items: center;
        margin-bottom: 25px;
    }
    .timer-box {
        background: var(--primary-color); color: #fff;
        padding: 8px 12px; border-radius: 8px;
        text-align: center; min-width: 50px;
    }
    .timer-box .num { font-size: 1.3rem; font-weight: 700; display: block; }
    .timer-box .label { font-size: 0.65rem; text-transform: uppercase; opacity: 0.8; }
    .timer-sep { font-size: 1.3rem; font-weight: 700; color: var(--primary-color); }

    /* ===== PRODUCT GRID & CARDS ===== */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 25px;
    }
    .product-card {
        background: var(--white);
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        transition: 0.3s;
        border: 1px solid #f0f0f0;
        display: flex;
        flex-direction: column;
    }
    .product-card:hover {
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        transform: translateY(-4px);
    }
    .pro-img {
        position: relative;
        background: #f8fafc;
        padding: 20px;
        height: 210px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pro-img img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        mix-blend-mode: multiply;
        pointer-events: none;
    }
    .pro-info { padding: 18px; flex: 1; display: flex; flex-direction: column; }
    .pro-cat {
        font-size: 0.75rem;
        color: var(--primary-color);
        margin-bottom: 4px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .pro-title {
        font-size: 0.95rem; font-weight: 600; margin: 0 0 8px 0;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        color: var(--text-dark);
    }
    .pro-price-box { display: flex; align-items: baseline; gap: 8px; flex-wrap: wrap; }
    .pro-price { color: var(--text-dark); font-weight: 700; font-size: 1.15rem; }
    .pro-price-old { color: #94a3b8; font-size: 0.85rem; text-decoration: line-through; }
    .pro-discount {
        background: #dcfce7; color: var(--success);
        font-size: 0.7rem; font-weight: 700;
        padding: 2px 8px; border-radius: 4px;
    }

    /* ❤️ LIKE BUTTON */
    .like-btn {
        position: absolute; top: 12px; right: 12px;
        width: 36px; height: 36px; border-radius: 50%;
        background: #fff; border: 1px solid #e2e8f0;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 20; transition: 0.3s;
    }
    .like-btn i { color: #94a3b8; font-size: 1rem; }
    .like-btn.liked { background: #fee2e2; border-color: #fca5a5; }
    .like-btn.liked i { color: #ef4444; }

    /* 👁 VIEW BUTTON */
    .view-btn {
        position: absolute; bottom: 12px; right: 12px;
        width: 40px; height: 40px;
        background: var(--primary-color);
        color: var(--white); border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        transform: scale(0); transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        font-size: 1rem; z-index: 10; border: 2px solid #fff;
    }
    .product-card:hover .view-btn { transform: scale(1); }
    .view-btn:hover { background: var(--primary-dark); transform: scale(1.1); }

    /* ===== DEAL BADGE ===== */
    .deal-badge {
        position: absolute; top: 12px; left: 12px;
        background: var(--danger); color: #fff;
        padding: 4px 10px; border-radius: 6px;
        font-size: 0.7rem; font-weight: 700;
        z-index: 5;
    }

    /* ===== WHY SHOP SECTION ===== */
    .why-shop-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
    }
    .why-card {
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        text-align: center;
        border: 1px solid #f1f5f9;
        transition: 0.3s;
    }
    .why-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
    .why-icon {
        width: 65px; height: 65px;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; color: var(--primary-color);
        margin: 0 auto 15px;
    }
    .why-card h4 { font-weight: 600; margin-bottom: 8px; }
    .why-card p { font-size: 0.85rem; color: var(--text-gray); line-height: 1.5; }

    
    /* ::::: 3D OVERRIDES - DEEP ORANGE BUTTONS ::::: */
    .slider-container { background: transparent !important; }
    
    .product-grid { perspective: 1000px; }
    .cat-card, .product-card, .why-card, .feature-item {
        background: #ffffff !important;
        border: 1px solid #e8eaed !important;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05) !important;
        transform-style: preserve-3d;
        transition: all 0.3s ease !important;
        border-radius: 12px !important;
    }
    .cat-card:hover, .product-card:hover, .why-card:hover, .feature-item:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        border-color: var(--primary) !important;
    }
    
    .section-container { background: transparent !important; border: none !important; backdrop-filter: none !important; }
    h2 span { color: var(--primary) !important; }
    
    /* 3D Tactile "Lock" Buttons (DEEP ORANGE) */
    .hero-btn, .promo-btn, .save-btn, .view-all-btn {
        background: var(--accent) !important;
        color: white !important;
        border: none !important;
        box-shadow: 0 4px 0 var(--accent-dark), 0 8px 15px rgba(255,107,0,0.3) !important;
        border-radius: 8px !important;
        padding: 10px 24px !important;
        transition: 0.15s !important;
        font-weight: 700 !important;
        transform: translateY(0) !important;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .hero-btn:active, .promo-btn:active, .save-btn:active, .view-all-btn:active {
        transform: translateY(4px) !important;
        box-shadow: 0 0 0 var(--accent-dark), 0 4px 8px rgba(255,107,0,0.3) !important;
    }
    
    .view-btn { background: var(--accent) !important; border-color: var(--white) !important; }
    .view-btn:hover { background: var(--accent-dark) !important; }
</style>

<!-- ===== HERO SLIDER ===== -->
<section class="slider-container">
    <div class="slider-wrapper">
        
        <div class="slide">
            <div class="hero-text">
                <span class="hero-tag">🔥 Mega Sale 2026</span>
                <h1>Shop Everything <br> You Love on All In One Bazaar.com</h1>
                <p>From fashion to electronics, home essentials to books — find millions of products at unbeatable prices.</p>
                <a href="products.php" class="hero-btn">Start Shopping <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1607082349566-187342175e2f?q=80&w=600&auto=format&fit=crop" 
                     alt="Shopping" 
                     onerror="this.src='https://placehold.co/500x400?text=Shop+Now'">
            </div>
        </div>

        <div class="slide">
            <div class="hero-text">
                <span class="hero-tag">👗 Fashion Week</span>
                <h1>Trending Fashion <br> Up To 70% Off</h1>
                <p>Discover the latest styles in clothing, footwear, and accessories for men, women & kids.</p>
                <a href="products.php" class="hero-btn">Shop Fashion <i class="fas fa-shirt"></i></a>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=600&auto=format&fit=crop" 
                     alt="Fashion"
                     onerror="this.src='https://placehold.co/500x400?text=Fashion'">
            </div>
        </div>

        <div class="slide">
            <div class="hero-text">
                <span class="hero-tag">💻 Tech Deals</span>
                <h1>Best Electronics <br> Great Prices</h1>
                <p>Latest smartphones, laptops, earbuds, smartwatches & more at incredible discounts.</p>
                <a href="products.php" class="hero-btn">Shop Electronics <i class="fas fa-laptop"></i></a>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=600&auto=format&fit=crop" 
                     alt="Electronics"
                     onerror="this.src='https://placehold.co/500x400?text=Electronics'">
            </div>
        </div>

        <div class="slide">
            <div class="hero-text">
                <span class="hero-tag">🏠 Home Essentials</span>
                <h1>Home & Kitchen <br> Starting ₹199</h1>
                <p>Upgrade your home with premium furniture, décor, appliances, and daily essentials.</p>
                <a href="products.php" class="hero-btn">Shop Home <i class="fas fa-house"></i></a>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?q=80&w=600&auto=format&fit=crop" 
                     alt="Home & Kitchen"
                     onerror="this.src='https://placehold.co/500x400?text=Home'">
            </div>
        </div>

    </div>

    <button class="slider-btn prev-btn" id="prevBtn"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-btn next-btn" id="nextBtn"><i class="fas fa-chevron-right"></i></button>
    
    <div class="slider-dots">
        <span class="slider-dot active" onclick="goToSlide(0)"></span>
        <span class="slider-dot" onclick="goToSlide(1)"></span>
        <span class="slider-dot" onclick="goToSlide(2)"></span>
        <span class="slider-dot" onclick="goToSlide(3)"></span>
    </div>
</section>

<!-- ===== FEATURES BAR ===== -->
<div class="features-bar">
    <div class="feature-item">
        <div class="feature-icon"><i class="fas fa-truck-fast"></i></div>
        <div class="feature-text"><h4>Free Shipping</h4><p>On orders above ₹499</p></div>
    </div>
    <div class="feature-item">
        <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
        <div class="feature-text"><h4>Secure Payment</h4><p>100% Protected</p></div>
    </div>
    <div class="feature-item">
        <div class="feature-icon"><i class="fas fa-undo"></i></div>
        <div class="feature-text"><h4>Easy Returns</h4><p>30 Day Policy</p></div>
    </div>
    <div class="feature-item">
        <div class="feature-icon"><i class="fas fa-headset"></i></div>
        <div class="feature-text"><h4>24/7 Support</h4><p>Ready to help</p></div>
    </div>
</div>

<!-- ===== SHOP BY CATEGORY ===== -->
<div class="section-container">
    <div class="section-header">
        <div class="section-title">
            <h2>Shop by <span>Category</span></h2>
            <p>Browse our wide range of product categories</p>
        </div>
        <a href="categories.php" class="view-all-btn">View All <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="cat-grid">
        <?php
        if (!empty($home_categories)) {
            foreach($home_categories as $row) {
                $icon = getIcon($row['name'], $cat_icons);
        ?>
            <a href="products.php?cat=<?= $row['id'] ?>" class="cat-card">
                <div class="cat-icon-box"><i class="fas <?= $icon ?>"></i></div>
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <div class="cat-count"><?= $row['product_count'] ?> Products</div>
            </a>
        <?php
            }
        } else { echo '<p>No categories found.</p>'; }
        ?>
    </div>
</div>

<!-- ===== PROMO BANNERS ===== -->
<div class="promo-grid">
    <div class="promo-card promo-1">
        <h3>🎉 Fashion Festival</h3>
        <p>Up to 70% off on trending styles. Limited time offer!</p>
        <a href="products.php" class="promo-btn">Shop Now <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="promo-card promo-2">
        <h3>💻 Tech Carnival</h3>
        <p>Best deals on smartphones, laptops & gadgets.</p>
        <a href="products.php" class="promo-btn">Explore <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="promo-card promo-3">
        <h3>🏠 Home Makeover</h3>
        <p>Transform your space with amazing deals on home essentials.</p>
        <a href="products.php" class="promo-btn">Discover <i class="fas fa-arrow-right"></i></a>
    </div>
</div>

<!-- ===== DEALS OF THE DAY ===== -->
<div class="section-container" style="background:var(--white);border-radius:24px;padding:35px;margin-bottom:40px;box-shadow:0 4px 20px rgba(0,0,0,0.04);">
    <div class="section-header">
        <div class="section-title">
            <h2>⚡ Deals of the <span>Day</span></h2>
        </div>
        <a href="products.php" class="view-all-btn">See All Deals <i class="fas fa-arrow-right"></i></a>
    </div>
    
    <!-- Timer -->
    <div class="deal-timer">
        <div class="timer-box"><span class="num" id="deal-hours">08</span><span class="label">Hours</span></div>
        <span class="timer-sep">:</span>
        <div class="timer-box"><span class="num" id="deal-mins">45</span><span class="label">Mins</span></div>
        <span class="timer-sep">:</span>
        <div class="timer-box"><span class="num" id="deal-secs">30</span><span class="label">Secs</span></div>
    </div>

    <div class="product-grid">
        <?php
        if(isset($conn)){
            $sql_deals = "SELECT p.id, p.name, p.image, p.selling_price, p.original_price, c.name as cat_name
                          FROM products p 
                          LEFT JOIN categories c ON p.category_id = c.id
                          WHERE p.status=0 AND p.original_price > p.selling_price
                          ORDER BY (p.original_price - p.selling_price) DESC 
                          LIMIT 4";
            $result_deals = mysqli_query($conn, $sql_deals);
            
            // If no discounted products, show latest ones
            if(!$result_deals || mysqli_num_rows($result_deals) == 0){
                $sql_deals = "SELECT p.id, p.name, p.image, p.selling_price, p.original_price, c.name as cat_name
                              FROM products p 
                              LEFT JOIN categories c ON p.category_id = c.id
                              WHERE p.status=0 
                              ORDER BY id DESC 
                              LIMIT 4";
                $result_deals = mysqli_query($conn, $sql_deals);
            }

            while ($row = mysqli_fetch_assoc($result_deals)) {
                $img_path = (!empty($row['image']) && file_exists('uploads/products/'.$row['image']))
                            ? 'uploads/products/'.$row['image']
                            : ((!empty($row['image']) && file_exists('assets/images/'.$row['image']))
                                ? 'assets/images/'.$row['image']
                                : 'https://placehold.co/300x300?text=Product');

                $discount = 0;
                if(!empty($row['original_price']) && $row['original_price'] > $row['selling_price']){
                    $discount = round((($row['original_price'] - $row['selling_price']) / $row['original_price']) * 100);
                }

                $liked = false;
                if(isset($_SESSION['user_id'])){
                    $uid = $_SESSION['user_id'];
                    $pid = $row['id'];
                    $chk = mysqli_query($conn, "SELECT id FROM wishlist WHERE user_id='$uid' AND product_id='$pid'");
                    if(mysqli_num_rows($chk) > 0) $liked = true;
                }
        ?>
            <div class="product-card">
                <div class="pro-img">
                    <?php if($discount > 0): ?>
                        <span class="deal-badge"><?= $discount ?>% OFF</span>
                    <?php endif; ?>

                    <button type="button" class="like-btn <?= $liked?'liked':'' ?>"
                            onclick="toggleLike(this,<?= $row['id'] ?>)">
                        <i class="<?= $liked?'fa-solid':'fa-regular' ?> fa-heart"></i>
                    </button>

                    <a href="product-details.php?id=<?= $row['id'] ?>" class="view-btn">
                        <i class="fas fa-eye"></i>
                    </a>

                    <img src="<?= $img_path ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                </div>
                <div class="pro-info">
                    <div class="pro-cat"><?= htmlspecialchars($row['cat_name'] ?? 'General') ?></div>
                    <h3 class="pro-title"><?= htmlspecialchars($row['name']) ?></h3>
                    <div class="pro-price-box">
                        <span class="pro-price">₹<?= number_format($row['selling_price']) ?></span>
                        <?php if($discount > 0): ?>
                            <span class="pro-price-old">₹<?= number_format($row['original_price']) ?></span>
                            <span class="pro-discount"><?= $discount ?>% off</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php } } ?>
    </div>
</div>

<!-- ===== TRENDING / NEW ARRIVALS ===== -->
<div class="section-container">
    <div class="section-header">
        <div class="section-title">
            <h2>🔥 Trending <span>Now</span></h2>
            <p>Most popular products this week</p>
        </div>
        <a href="products.php" class="view-all-btn">Shop All <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="product-grid">
        <?php
        $sql_trending = "SELECT p.id, p.name, p.image, p.selling_price, p.original_price, c.name as cat_name
                         FROM products p 
                         LEFT JOIN categories c ON p.category_id = c.id
                         WHERE p.status=0 
                         ORDER BY p.trending DESC, p.id DESC 
                         LIMIT 8";
        $result_trending = mysqli_query($conn, $sql_trending);

        while ($row = mysqli_fetch_assoc($result_trending)) {
            $img_path = (!empty($row['image']) && file_exists('uploads/products/'.$row['image']))
                        ? 'uploads/products/'.$row['image']
                        : ((!empty($row['image']) && file_exists('assets/images/'.$row['image']))
                            ? 'assets/images/'.$row['image']
                            : 'https://placehold.co/300x300?text=Product');

            $discount = 0;
            if(!empty($row['original_price']) && $row['original_price'] > $row['selling_price']){
                $discount = round((($row['original_price'] - $row['selling_price']) / $row['original_price']) * 100);
            }

            $liked = false;
            if(isset($_SESSION['user_id'])){
                $uid = $_SESSION['user_id'];
                $pid = $row['id'];
                $chk = mysqli_query($conn, "SELECT id FROM wishlist WHERE user_id='$uid' AND product_id='$pid'");
                if(mysqli_num_rows($chk) > 0) $liked = true;
            }
        ?>
        <div class="product-card">
            <div class="pro-img">
                <?php if($discount > 0): ?>
                    <span class="deal-badge"><?= $discount ?>% OFF</span>
                <?php endif; ?>

                <button type="button" class="like-btn <?= $liked?'liked':'' ?>"
                        onclick="toggleLike(this,<?= $row['id'] ?>)">
                    <i class="<?= $liked?'fa-solid':'fa-regular' ?> fa-heart"></i>
                </button>

                <a href="product-details.php?id=<?= $row['id'] ?>" class="view-btn">
                    <i class="fas fa-eye"></i>
                </a>

                <img src="<?= $img_path ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            </div>
            <div class="pro-info">
                <div class="pro-cat"><?= htmlspecialchars($row['cat_name'] ?? 'General') ?></div>
                <h3 class="pro-title"><?= htmlspecialchars($row['name']) ?></h3>
                <div class="pro-price-box">
                    <span class="pro-price">₹<?= number_format($row['selling_price']) ?></span>
                    <?php if($discount > 0): ?>
                        <span class="pro-price-old">₹<?= number_format($row['original_price']) ?></span>
                        <span class="pro-discount"><?= $discount ?>% off</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<!-- ===== WHY SHOP WITH US ===== -->
<div class="section-container">
    <div class="section-header">
        <div class="section-title"><h2>Why Shop on <span>All In One Bazaar</span>?</h2></div>
    </div>
    <div class="why-shop-grid">
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-infinity"></i></div>
            <h4>Millions of Products</h4>
            <p>From fashion to electronics, groceries to books — find everything in one place.</p>
        </div>
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-tags"></i></div>
            <h4>Best Prices Guaranteed</h4>
            <p>We offer competitive prices and daily deals you won't find anywhere else.</p>
        </div>
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-lock"></i></div>
            <h4>Safe & Secure</h4>
            <p>Shop with confidence. Your data and payments are always protected.</p>
        </div>
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-rotate-left"></i></div>
            <h4>Hassle-Free Returns</h4>
            <p>Not satisfied? Return products easily within 30 days for a full refund.</p>
        </div>
    </div>
</div>


<?php include("includes/footer.php"); ?>

<script>
// ===== SLIDER =====
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const sliderWrapper = document.querySelector('.slider-wrapper');
const dots = document.querySelectorAll('.slider-dot');
const totalSlides = slides.length;

function goToSlide(n) {
    currentSlide = n;
    sliderWrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
    dots.forEach((d,i) => d.classList.toggle('active', i === currentSlide));
}

document.getElementById('nextBtn').addEventListener('click', () => {
    goToSlide((currentSlide + 1) % totalSlides);
});
document.getElementById('prevBtn').addEventListener('click', () => {
    goToSlide((currentSlide - 1 + totalSlides) % totalSlides);
});

// Auto-slide
setInterval(() => goToSlide((currentSlide + 1) % totalSlides), 5000);

// ===== DEAL TIMER =====
function updateDealTimer() {
    let now = new Date();
    let end = new Date();
    end.setHours(23, 59, 59, 0);
    let diff = Math.max(0, end - now);
    
    let h = Math.floor(diff / 3600000);
    let m = Math.floor((diff % 3600000) / 60000);
    let s = Math.floor((diff % 60000) / 1000);
    
    document.getElementById('deal-hours').textContent = String(h).padStart(2, '0');
    document.getElementById('deal-mins').textContent = String(m).padStart(2, '0');
    document.getElementById('deal-secs').textContent = String(s).padStart(2, '0');
}
updateDealTimer();
setInterval(updateDealTimer, 1000);

// ===== WISHLIST TOGGLE =====
function toggleLike(btn, productId){
    fetch("wishlist_action.php",{
        method:"POST",
        headers:{ "Content-Type":"application/x-www-form-urlencoded" },
        body:"product_id="+productId
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === "login"){
            alert("Please login first");
            return;
        }
        let icon = btn.querySelector("i");
        if(data.status === "added"){
            btn.classList.add("liked");
            icon.classList.replace("fa-regular","fa-solid");
        }
        if(data.status === "removed"){
            btn.classList.remove("liked");
            icon.classList.replace("fa-solid","fa-regular");
        }
    });
}
</script>
