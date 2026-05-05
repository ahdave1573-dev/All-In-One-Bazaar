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
    .privacy-header {
        background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
        padding: 60px 5%;
        text-align: center;
        border-bottom: 1px solid #e2e8f0;
    }
    .privacy-header h1 { font-size: 2.5rem; color: var(--dark); margin-bottom: 10px; font-weight: 700; }
    .privacy-header p { color: var(--gray); font-size: 1.1rem; }

    /* CONTENT CONTAINER */
    .privacy-container {
        max-width: 1000px;
        margin: 50px auto;
        padding: 0 20px;
        background: var(--white);
    }

    .privacy-content {
        line-height: 1.8;
        color: var(--gray);
        font-size: 1rem;
    }

    .privacy-content h2 {
        color: var(--dark);
        font-size: 1.5rem;
        margin-top: 40px;
        margin-bottom: 15px;
        border-left: 4px solid var(--primary);
        padding-left: 15px;
    }

    .privacy-content p { margin-bottom: 20px; }
    
    .privacy-content ul {
        list-style-type: disc;
        margin-left: 20px;
        margin-bottom: 20px;
    }
    
    .privacy-content li { margin-bottom: 10px; }

    .last-updated {
        margin-top: 50px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
        font-size: 0.9rem;
        font-style: italic;
        color: #94a3b8;
    }
</style>

<div class="privacy-header">
    <h1>Privacy Policy</h1>
    <p>We care about your privacy and the security of your data.</p>
</div>

<div class="privacy-container">
    <div class="privacy-content">
        
        <p>At <strong>All In One Bazaar.com</strong>, accessible from our website, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by All In One Bazaar.com and how we use it.</p>

        <h2>1. Information We Collect</h2>
        <p>We collect several different types of information for various purposes to provide and improve our service to you.</p>
        <ul>
            <li><strong>Personal Data:</strong> When you register or make a purchase, we may ask for personal information such as your Name, Email address, Phone number, and Shipping address.</li>
            <li><strong>Usage Data:</strong> We may also collect information on how the Service is accessed and used (e.g., page views, time spent on pages).</li>
        </ul>

        <h2>2. How We Use Your Information</h2>
        <p>We use the collected data for various purposes:</p>
        <ul>
            <li>To provide and maintain our Service.</li>
            <li>To notify you about changes to our Service.</li>
            <li>To provide customer support.</li>
            <li>To process your orders and manage your account.</li>
            <li>To detect, prevent, and address technical issues.</li>
        </ul>

        <h2>3. Security of Data</h2>
        <p>The security of your data is important to us. We use standard security protocols (like Password Hashing) to protect your personal information. However, please remember that no method of transmission over the Internet is 100% secure.</p>

        <h2>4. Cookies</h2>
        <p>We use cookies to store information including visitors' preferences and the pages on the website that the visitor accessed or visited. The information is used to optimize the users' experience by customizing our web page content.</p>

        <h2>5. Third-Party Disclosure</h2>
        <p>We do not sell, trade, or otherwise transfer to outside parties your Personally Identifiable Information. This does not include website hosting partners and other parties who assist us in operating our website, conducting our business, or serving our users.</p>

        <h2>6. Consent</h2>
        <p>By using our website, you hereby consent to our Privacy Policy and agree to its Terms and Conditions.</p>

        <h2>7. Contact Us</h2>
        <p>If you have any questions about this Privacy Policy, please contact us:</p>
        <p>
            <strong>Email:</strong> 
        </p>

        <div class="last-updated">
            Last Updated: <?php echo date("F d, Y"); ?>
        </div>

    </div>
</div>

<?php
include("includes/footer.php");
?>