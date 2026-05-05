<?php
include('config/constants.php');
include('config/db.php');
include('includes/functions.php');
include('includes/header.php');
?>

<style>
/* ::::: GLOBAL VARIABLES ::::: */
:root {
    --primary: #2563eb;
    --primary-dark: #1e40af;
    --dark: #0f172a;
    --gray: #64748b;
    --light-bg: #f8fafc;
}

/* HERO SECTION */
.about-hero{
    background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
    padding: 80px 5%;
    text-align: center;
    border-bottom: 1px solid #e2e8f0;
}
.about-hero h1{
    font-size: 3rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 20px;
}
.about-hero span{ color: var(--primary); }
.about-hero p{
    max-width: 800px;
    margin: 0 auto;
    color: var(--gray);
    font-size: 1.1rem;
    line-height: 1.8;
}

/* CONTAINER */
.about-container{
    max-width: 1300px; /* Increased width for side-by-side layout */
    margin: auto;
    padding: 70px 20px;
}

/* SECTION TITLE */
.section-title{
    text-align: center;
    margin-bottom: 50px;
}
.section-title h2{
    font-size: 2.2rem;
    color: var(--dark);
    display: inline-block;
    position: relative;
    font-weight: 600;
}
.section-title h2::after{
    content: '';
    width: 80px;
    height: 4px;
    background: var(--primary);
    display: block;
    margin: 12px auto 0;
    border-radius: 4px;
}

/* GRID LAYOUT */
.about-grid{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 35px;
    margin-bottom: 80px;
}

/* INFO CARD DESIGN */
.info-card{
    background: #fff;
    padding: 40px 30px;
    border-radius: 20px;
    text-align: center;
    border: 1px solid #f1f5f9;
    box-shadow: 0 10px 25px rgba(0,0,0,0.03);
    transition: all 0.3s ease;
}
.info-card:hover{
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(37,99,235,0.1);
    border-color: rgba(37,99,235,0.3);
}

.card-icon{
    width: 80px;
    height: 80px;
    background: #eff6ff;
    color: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 25px;
    transition: 0.3s;
}
.info-card:hover .card-icon{
    background: var(--primary);
    color: #fff;
    transform: rotateY(180deg);
}

.info-card h3{
    font-size: 1.4rem;
    margin-bottom: 15px;
    color: var(--dark);
    font-weight: 600;
}
.info-card p{
    color: var(--gray);
    font-size: 0.95rem;
    line-height: 1.6;
}

/* ::::: SIDE-BY-SIDE DEVELOPER SECTION CSS ::::: */
.dev-wrapper {
    display: flex;
    justify-content: center;
    flex-wrap: wrap; /* Allows wrapping on small screens */
    gap: 30px; /* Space between the two cards */
    margin-top: 30px;
}

.dev-card {
    background: #ffffff;
    flex: 1 1 500px; /* Grow factor, Shrink factor, Basis width */
    max-width: 600px; /* Maximum width per card */
    border-radius: 24px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    padding: 40px;
    gap: 30px;
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
}

/* Background decoration circle */
.dev-card::before {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 150px;
    height: 150px;
    background: rgba(37, 99, 235, 0.05);
    border-radius: 50%;
    z-index: 0;
}

/* Left Side: Image/Icon */
.dev-img-box {
    flex-shrink: 0;
    width: 140px; /* Slightly smaller to fit side-by-side */
    height: 140px;
    background: linear-gradient(135deg, #e0e7ff, #eff6ff);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid #fff;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);
    z-index: 1;
}
.dev-img-box i {
    font-size: 4rem;
    color: var(--primary);
}

/* Right Side: Content */
.dev-content {
    flex: 1;
    z-index: 1;
    text-align: left;
}

.dev-badge {
    background: #dbeafe;
    color: var(--primary);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
    margin-bottom: 10px;
}

.dev-content h2 {
    font-size: 1.8rem;
    color: var(--dark);
    margin-bottom: 5px;
    font-weight: 700;
}

.dev-role {
    font-size: 1rem;
    color: var(--gray);
    font-weight: 500;
    margin-bottom: 15px;
    display: block;
}

.dev-desc {
    color: var(--gray);
    line-height: 1.6;
    margin-bottom: 20px;
    font-size: 0.9rem;
}

/* Social Icons */
.dev-socials {
    display: flex;
    gap: 12px;
}
.dev-socials a {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #f1f5f9;
    color: var(--dark);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 0.9rem;
}
.dev-socials a:hover {
    background: var(--primary);
    color: #fff;
    transform: translateY(-3px);
}

/* Responsive for Dev Card */
@media(max-width: 992px){
    .dev-card {
        flex-direction: column;
        text-align: center;
        padding: 30px 20px;
        max-width: 100%; /* Full width on tablet/mobile */
    }
    .dev-content { text-align: center; }
    .dev-socials { justify-content: center; }
}
</style>

<section class="about-hero">
    <h1>About <span>All In One Bazaar.com</span></h1>
    <p>
        <strong>All In One Bazaar.com</strong> is your one-stop online marketplace — just like shopping on Amazon.
        From fashion to electronics, home essentials to books, we bring millions of products
        to your doorstep at unbeatable prices.
    </p>
</section>

<div class="about-container">

    <div class="section-title">
        <h2>Who We Are</h2>
    </div>

    <div class="about-grid">
        <div class="info-card">
            <div class="card-icon"><i class="fas fa-eye"></i></div>
            <h3>Our Vision</h3>
            <p>To become India's most trusted online marketplace by delivering quality, affordability, and convenience to every doorstep.</p>
        </div>

        <div class="info-card">
            <div class="card-icon"><i class="fas fa-bullseye"></i></div>
            <h3>Our Mission</h3>
            <p>To offer millions of genuine products across every category at competitive prices with a seamless shopping experience.</p>
        </div>

        <div class="info-card">
            <div class="card-icon"><i class="fas fa-store"></i></div>
            <h3>What We Sell</h3>
            <p>Everything! Fashion, Electronics, Home & Kitchen, Books, Sports, Beauty, Toys, Grocery, and much more.</p>
        </div>
    </div>

    <div class="section-title">
        <h2>Why Choose Us?</h2>
    </div>

    <div class="about-grid">
        <div class="info-card">
            <div class="card-icon"><i class="fas fa-shield-alt"></i></div>
            <h3>Secure Payments</h3>
            <p>Your transactions are safe with our encrypted payment gateways and multiple payment options.</p>
        </div>

        <div class="info-card">
            <div class="card-icon"><i class="fas fa-headset"></i></div>
            <h3>24/7 Support</h3>
            <p>Dedicated support team to assist you with product queries, orders, and after-sales service.</p>
        </div>

        <div class="info-card">
            <div class="card-icon"><i class="fas fa-shipping-fast"></i></div>
            <h3>Fast Delivery</h3>
            <p>Optimized logistics network to ensure your orders reach your doorstep safely and on time.</p>
        </div>
    </div>

    <div class="section-title">
        <h2>Meet The Developers</h2>
    </div>

    <div class="dev-wrapper">
        
        <div class="dev-card">
            <div class="dev-img-box">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="dev-content">
                <span class="dev-badge">Designed & Developed By</span>
                <h2>Dave Anshul</h2>
                <span class="dev-role">Final Year Student | Bachelor Of Computer Application </span>
                <p class="dev-desc">
                    Passionate regarding web technologies and software development. 
                    Created <strong>All In One Bazaar.com</strong> as a Major Project to demonstrate 
                    Full Stack Development skills using PHP and MySQL.
                </p>
                <div class="dev-socials">
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://github.com/ahdave1573-dev" target="_blank" title="GitHub"><i class="fab fa-github"></i></a>
                    <a href="mailto:anshul@example.com" title="Email"><i class="fas fa-envelope"></i></a>
                    <a href="#" title="Portfolio"><i class="fas fa-globe"></i></a>
                </div>
            </div>
        </div>
    </div>
    </div>

<?php include('includes/footer.php'); ?>