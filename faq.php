<?php
// ::::: 1. CONFIGURATION :::::
// Connect to database (optional, but good practice to keep session active)
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
    .faq-header {
        background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
        padding: 60px 5%;
        text-align: center;
        border-bottom: 1px solid #e2e8f0;
    }
    .faq-header h1 { font-size: 2.5rem; color: var(--dark); margin-bottom: 10px; font-weight: 700; }
    .faq-header span { color: var(--primary); }
    .faq-header p { color: var(--gray); font-size: 1.1rem; max-width: 600px; margin: 0 auto; }

    /* FAQ CONTAINER */
    .faq-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 0 20px;
        min-height: 50vh;
    }

    /* ACCORDION ITEM */
    .faq-item {
        background: var(--white);
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 15px;
        overflow: hidden;
        transition: var(--transition);
    }
    
    .faq-item:hover {
        border-color: var(--primary);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    /* QUESTION (CLICKABLE) */
    .faq-question {
        padding: 20px 25px;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        user-select: none; /* Prevents text highlighting on click */
    }

    .faq-question i {
        color: var(--primary);
        transition: transform 0.3s ease;
    }

    /* ANSWER (HIDDEN BY DEFAULT) */
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        background: #f8fafc;
    }

    .faq-answer p {
        padding: 20px 25px;
        color: var(--gray);
        line-height: 1.6;
        font-size: 0.95rem;
        border-top: 1px solid #f1f5f9;
        margin: 0;
    }

    /* ACTIVE STATE (OPEN) */
    .faq-item.active .faq-question {
        color: var(--primary);
    }
    .faq-item.active .faq-question i {
        transform: rotate(180deg);
    }
    .faq-item.active .faq-answer {
        max-height: 200px; /* Adjust based on content length */
    }

    /* CONTACT BANNER */
    .faq-contact {
        text-align: center;
        margin-top: 60px;
        padding: 40px;
        background: #eff6ff;
        border-radius: 15px;
    }
    .faq-contact h3 { color: var(--dark); margin-bottom: 10px; }
    .faq-contact a { 
        display: inline-block; margin-top: 15px; 
        background: var(--primary); color: white; 
        padding: 12px 30px; border-radius: 50px; 
        font-weight: 600; transition: 0.3s; 
    }
    .faq-contact a:hover { background: var(--primary-dark); }
</style>

<div class="faq-header">
    <h1>Frequently Asked <span>Questions</span></h1>
    <p>Find answers to common questions about orders, shipping, returns, and account management.</p>
</div>

<div class="faq-container">

    <div class="faq-item">
        <div class="faq-question">
            How do I place an order?
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            <p>Simply browse our products, add items to your cart, and proceed to checkout. You'll need to create an account or login to finalize your purchase securely.</p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            What payment methods do you accept?
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            <p>We accept all major credit/debit cards, net banking, UPI, and Cash on Delivery (COD) for select locations.</p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            How can I track my order?
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            <p>Once your order is shipped, you can track it from the "My Orders" section in your dashboard. We also send tracking updates via email.</p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            What is your return policy?
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            <p>We offer a 7-day easy return policy for defective or damaged products. Please ensure the item is unused and in its original packaging.</p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            Do you offer international shipping?
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            <p>Currently, All In One Bazaar.com only ships within India. We plan to expand to international markets in the future.</p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            I forgot my password, what should I do?
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            <p>Go to the login page and click on "Forgot Password". You currently need to contact admin support to reset it manually in this version.</p>
        </div>
    </div>

    <div class="faq-contact">
        <h3>Still have questions?</h3>
        <p>Can't find the answer you're looking for? Please chat to our friendly team.</p>
        <a href="contact.php">Contact Support</a>
    </div>

</div>

<script>
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', () => {
            // Optional: Close other items when one opens
            // faqItems.forEach(otherItem => {
            //     if(otherItem !== item) otherItem.classList.remove('active');
            // });

            // Toggle current item
            item.classList.toggle('active');
        });
    });
</script>

<?php
include("includes/footer.php");
?>