<?php
// 1. Constants (Site URL mate)
include('config/constants.php');

// 2. Database Connection
include('config/db.php');

// 3. Functions (Optional: jo tame functions use karta hovo)
include('includes/functions.php');

// 4. Header (Aa automatic Navbar pan lai lese)
include('includes/header.php');

// ::::: PHP FORM HANDLING :::::
$msg = "";
$msg_type = "";

if (isset($_POST['send_message'])) {
    // 1. Get Form Data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']); 

    // 2. Insert into Database (Optional: If you have a 'messages' table)
    $sql = "INSERT INTO messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
    mysqli_query($conn, $sql);

    // 3. Show Success Message
    $msg = "Thank you! Your message has been sent successfully.";
    $msg_type = "success";
}
?>

<style>
    /* PAGE HEADER */
    .page-header {
        background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
        padding: 50px 5%;
        text-align: center;
        border-bottom: 1px solid #e2e8f0;
    }
    .page-header h1 { font-size: 2.5rem; color: var(--dark); margin-bottom: 10px; }
    .page-header p { color: var(--gray); font-size: 1.1rem; }

    /* MAIN CONTAINER */
    .contact-container {
        max-width: 1200px;
        margin: 50px auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 50px;
    }

    /* LEFT: FORM SECTION */
    .contact-form-box {
        background: var(--white);
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
    }

    .form-group { margin-bottom: 20px; }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        color: var(--dark);
        font-weight: 500;
    }

    .form-input, .form-textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        outline: none;
        transition: 0.3s;
        background: #f8fafc;
    }

    .form-input:focus, .form-textarea:focus {
        border-color: var(--primary);
        background: var(--white);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-textarea { resize: vertical; min-height: 150px; }

    .submit-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 15px 35px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .submit-btn:hover { background: var(--primary-dark); transform: translateY(-2px); }

    /* RIGHT: INFO SECTION */
    .contact-info-box {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .info-card {
        background: var(--white);
        padding: 25px;
        border-radius: 15px;
        display: flex;
        align-items: flex-start;
        gap: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
    }

    .info-icon {
        width: 50px; height: 50px;
        background: #eff6ff;
        color: var(--primary);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .info-content h3 { font-size: 1.1rem; margin-bottom: 5px; color: var(--dark); }
    .info-content p { color: var(--gray); font-size: 0.95rem; line-height: 1.5; }

    /* MAP */
    .map-frame {
        width: 100%;
        height: 250px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }
    .map-frame iframe { width: 100%; height: 100%; border: none; }

    /* ALERTS */
    .alert {
        padding: 15px;
        background: #dcfce7;
        color: #166534;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #bbf7d0;
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {
        .contact-container { grid-template-columns: 1fr; }
    }
</style>

<div class="page-header">
    <h1>Get in Touch</h1>
    <p>Have questions about our products? We're here to help.</p>
</div>

<div class="contact-container">

    <div class="contact-form-box">
        
        <?php if($msg != ""): ?>
            <div class="alert"><i class="fas fa-check-circle"></i> <?php echo $msg; ?></div>
        <?php endif; ?>

        <form action="contact.php" method="POST">
            <div class="form-group">
                <label class="form-label">Your Name</label>
                <input type="text" name="name" class="form-input" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-input" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label class="form-label">Subject</label>
                <input type="text" name="subject" class="form-input" placeholder="What is this regarding?">
            </div>

            <div class="form-group">
                <label class="form-label">Message</label>
                <textarea name="message" class="form-textarea" placeholder="Write your message here..." required></textarea>
            </div>

            <button type="submit" name="send_message" class="submit-btn">
                Send Message <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>

    <div class="contact-info-box">
        
        <div class="info-card">
            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div class="info-content">
                <h3>Our Location</h3>
                <p>All In One Bazaar.com Tech Hub,<br>150 Ft Ring Road, Rajkot, Gujarat - 360005</p>
            </div>
        </div>

        <div class="info-card">
            <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
            <div class="info-content">
                <h3>Phone Number</h3>
                <p>+91 88499 19418<br>Mon - Sat (10 AM - 7 PM)</p>
            </div>
        </div>

        <div class="info-card">
            <div class="info-icon"><i class="fas fa-envelope"></i></div>
            <div class="info-content">
                <h3>Email Support</h3>
                <p>support@allinonebazaar.com<br>info@allinonebazaar.com</p>
            </div>
        </div>

        <div class="map-frame">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d118147.68202062602!2d70.73889449079963!3d22.273630794931835!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3959c98ac71cdf0f%3A0x76dd15cfbe93ad3b!2sRajkot%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1710000000000!5m2!1sen!2sin" allowfullscreen="" loading="lazy"></iframe>
        </div>

    </div>

</div>

<?php
// FIX: Point to the 'includes' folder
include("includes/footer.php");
?>