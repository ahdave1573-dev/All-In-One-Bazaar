</main> <footer class="site-footer">
    <div class="footer-container">

        <div class="footer-col">
            <h3 class="footer-logo">All In One <span>Bazaar.com</span>.</h3>
            <p>
                All In One Bazaar.com is your trusted online marketplace. 
                We provide a secure, modern, and user-friendly platform 
                to buy everything you need at the best prices.
            </p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>

        <div class="footer-col">
            <h3>Quick Links</h3>
            <ul>
                <?php 
                    // Link Logic ensure path is correct
                    $url = defined('SITEURL') ? SITEURL : ''; 
                ?>
                <li><a href="<?php echo $url; ?>index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                <li><a href="<?php echo $url; ?>products.php"><i class="fas fa-chevron-right"></i> All Products</a></li>
                <li><a href="<?php echo $url; ?>user/cart.php"><i class="fas fa-chevron-right"></i> My Cart</a></li>
                
                <li><a href="<?php echo $url; ?>user/profile.php"><i class="fas fa-chevron-right"></i> My Account</a></li>
                <li><a href="<?php echo $url; ?>contact.php"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Contact Us</h3>
            <ul class="contact-list">
                <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <span>150 Ft Ring Road, Rajkot, Gujarat, India</span>
                </li>
                <li>
                    <i class="fas fa-phone-alt"></i>
                    <span>+91 88499 19418</span>
                </li>
                <li>
                    <i class="fas fa-envelope"></i>
                    <span>support@allinonebazaar.com</span>
                </li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Stay Updated</h3>
            <p style="margin-bottom: 15px;">Subscribe for the latest deals and offers.</p>
            <form action="#" class="newsletter-form">
                <input type="email" placeholder="Enter your email" required>
                <button type="submit"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>

    </div>

    <div class="footer-bottom">
        <div class="footer-bottom-container">
            <div class="copyright">
                © <?php echo date('Y'); ?> <strong>All In One Bazaar.com</strong>
            </div>
            <div class="payment-icons">
                <i class="fab fa-cc-visa"></i>
                <i class="fab fa-cc-mastercard"></i>
                <i class="fab fa-cc-paypal"></i>
                <i class="fab fa-google-pay"></i>
            </div>
        </div>
    </div>
</footer>

<style>
    /* ::::: FOOTER CSS ::::: */
    
    /* Variables import from header logic */
    :root {
        --primary: #2563eb;
        --dark-bg: #0f172a;   /* Dark Slate */
        --darker-bg: #020617; /* Darker Black */
        --text-gray: #94a3b8;
        --white: #ffffff;
    }

    /* Footer Wrapper */
    .site-footer {
        background-color: var(--dark-bg);
        color: var(--text-gray);
        padding-top: 60px;
        margin-top: auto; /* Pushes footer to bottom */
        font-family: 'Poppins', sans-serif;
    }

    /* Main Container (Matches Header Width) */
    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        padding-bottom: 40px;
    }

    /* Columns Typography */
    .footer-col h3 {
        color: var(--white);
        font-size: 1.2rem;
        margin-bottom: 25px;
        font-weight: 600;
    }
    
    .footer-logo { font-size: 1.8rem !important; margin-bottom: 20px !important; }
    .footer-logo span { color: var(--primary); }

    .footer-col p { line-height: 1.6; font-size: 0.95rem; }

    /* Links List */
    .footer-col ul { list-style: none; padding: 0; }
    .footer-col ul li { margin-bottom: 12px; }
    
    .footer-col ul li a {
        color: var(--text-gray);
        text-decoration: none;
        transition: 0.3s;
        display: flex; align-items: center; gap: 8px;
        font-size: 0.95rem;
    }
    
    .footer-col ul li a i { font-size: 0.7rem; color: var(--primary); }
    
    .footer-col ul li a:hover {
        color: var(--white);
        transform: translateX(5px);
    }

    /* Contact List */
    .contact-list li {
        display: flex; align-items: flex-start; gap: 15px; margin-bottom: 15px;
    }
    .contact-list i { color: var(--primary); margin-top: 5px; }

    /* Social Icons */
    .social-links { margin-top: 20px; display: flex; gap: 12px; }
    .social-links a {
        width: 38px; height: 38px;
        background: rgba(255,255,255,0.08);
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        color: var(--white);
        transition: 0.3s;
    }
    .social-links a:hover { background: var(--primary); transform: translateY(-3px); }

    /* Newsletter */
    .newsletter-form {
        display: flex;
        align-items: center;
        background: rgba(255,255,255,0.05);
        padding: 6px;
        border-radius: 50px;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .newsletter-form input {
        flex: 1;
        background: transparent;
        border: none;
        padding: 12px 10px;
        color: #fff;
        outline: none;
        font-size: 0.9rem;
    }   

    .newsletter-form button {
        background: var(--primary);
        color: #fff;
        border: none;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;   /* 🔥 important fix */
    }

    .newsletter-form button i {
        font-size: 0.9rem;
    }
    .newsletter-form button:hover {
        background: #fff;
        color: var(--primary);
    }

    /* Bottom Bar */
    .footer-bottom {
        background: var(--darker-bg);
        padding: 20px 0;
        border-top: 1px solid rgba(255,255,255,0.05);
        font-size: 0.9rem;
    }
    
    .footer-bottom-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .payment-icons { font-size: 1.5rem; color: var(--text-gray); display: flex; gap: 15px; }
    
    @media (max-width: 768px) {
        .footer-bottom-container { flex-direction: column; text-align: center; }
    }
</style>

</body>
</html>