<?php
// ::::: 1. CONFIGURATION :::::
if(file_exists("config/db.php")) {
    include_once("config/db.php");
} else {
    if(file_exists("db.php")) { include_once("db.php"); }
}

// ::::: 2. INCLUDE HEADER :::::
include("includes/header.php");
?>

<style>
    /* HERO SECTION */
    .terms-header {
        background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
        padding: 60px 5%;
        text-align: center;
        border-bottom: 1px solid #e2e8f0;
    }
    .terms-header h1 { font-size: 2.5rem; color: var(--dark); margin-bottom: 10px; font-weight: 700; }
    .terms-header p { color: var(--gray); font-size: 1.1rem; }

    /* CONTENT CONTAINER */
    .terms-container {
        max-width: 1000px;
        margin: 50px auto;
        padding: 0 20px;
        background: var(--white);
    }

    .terms-content {
        line-height: 1.8;
        color: var(--gray);
        font-size: 1rem;
    }

    .terms-content h2 {
        color: var(--dark);
        font-size: 1.5rem;
        margin-top: 40px;
        margin-bottom: 15px;
        border-left: 4px solid var(--primary);
        padding-left: 15px;
    }

    .terms-content p { margin-bottom: 20px; }
    
    .terms-content ul {
        list-style-type: disc;
        margin-left: 20px;
        margin-bottom: 20px;
    }
    
    .terms-content li { margin-bottom: 10px; }

    .last-updated {
        margin-top: 50px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
        font-size: 0.9rem;
        font-style: italic;
        color: #94a3b8;
    }
</style>

<div class="terms-header">
    <h1>Terms & Conditions</h1>
    <p>Please read these terms carefully before using our services.</p>
</div>

<div class="terms-container">
    <div class="terms-content">
        
        <p>Welcome to <strong>All In One Bazaar.com</strong>. By accessing or using our website, you agree to be bound by these Terms and Conditions. If you disagree with any part of these terms, please do not use our website.</p>

        <h2>1. General Use</h2>
        <p>All In One Bazaar.com provides an online platform for purchasing goods. By using this site, you confirm that:</p>
        <ul>
            <li>You are at least 18 years old or accessing the site under the supervision of a parent or guardian.</li>
            <li>You will not use the website for any illegal or unauthorized purpose.</li>
            <li>You will not attempt to hack, disrupt, or interfere with the security of the website.</li>
        </ul>

        <h2>2. Products and Pricing</h2>
        <p>We strive to ensure that all product descriptions, images, and prices are accurate. However, errors may occur.</p>
        <ul>
            <li><strong>Pricing:</strong> Prices are subject to change without notice. In the event of a pricing error, we reserve the right to cancel orders.</li>
            <li><strong>Availability:</strong> Product availability is subject to stock. We may limit the quantity of items purchased per person.</li>
            <li><strong>Images:</strong> Product images are for illustrative purposes only. Actual products may vary slightly in color or design.</li>
        </ul>

        <h2>3. Orders and Payments</h2>
        <p>When you place an order, you agree to provide current, complete, and accurate purchase and account information.</p>
        <ul>
            <li>We reserve the right to refuse or cancel any order for reasons including but not limited to: product unavailability, errors in pricing, or suspected fraud.</li>
            <li>Payment must be made via the secure methods provided at checkout (Credit Card, Debit Card, UPI, etc.).</li>
        </ul>

        <h2>4. Returns and Refunds</h2>
        <p>Our goal is customer satisfaction. If you are not happy with your purchase, you may return eligible items within 7 days of delivery.</p>
        <p>Items must be unused, in original packaging, and accompanied by proof of purchase. Refunds will be processed to the original payment method within 5-7 business days after inspection.</p>

        <h2>5. User Accounts</h2>
        <p>You are responsible for maintaining the confidentiality of your account and password. You agree to accept responsibility for all activities that occur under your account.</p>

        <h2>6. Intellectual Property</h2>
        <p>All content included on this site, such as text, graphics, logos, images, and software, is the property of All In One Bazaar.com or its content suppliers and is protected by copyright laws.</p>

        <h2>7. Limitation of Liability</h2>
        <p>All In One Bazaar.com shall not be liable for any direct, indirect, incidental, or consequential damages resulting from the use or inability to use our services or products.</p>

        <h2>8. Contact Information</h2>
        <p>If you have any questions about these Terms & Conditions, please contact us at:</p>
        <p>
            <strong>Email:</strong> legal@allinonebazaar.com<br>
            <strong>Phone:</strong> +91 88499 19418
        </p>

        <div class="last-updated">
            Last Updated: <?php echo date("F d, Y"); ?>
        </div>

    </div>
</div>

<?php
include("includes/footer.php");
?>