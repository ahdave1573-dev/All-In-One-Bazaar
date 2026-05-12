<?php
/*
 * ::::: COMMON HELPER FUNCTIONS :::::
 * Include this file in your pages to use these shortcuts.
 */

// 1. SANITIZE INPUTS (Prevents SQL Injection & XSS)
// Usage: $email = clean_input($conn, $_POST['email']);
function clean_input($conn, $data) {
    $data = trim($data);                 // Remove extra spaces
    $data = stripslashes($data);         // Remove backslashes
    $data = htmlspecialchars($data);     // Convert special chars to HTML entities (prevents script hacking)
    return mysqli_real_escape_string($conn, $data); // Escape special SQL chars
}

// 2. REDIRECT HELPER
// Usage: redirect('login.php');
function redirect($url) {
    if (!headers_sent()) {
        header("Location: " . $url);
        exit();
    } else {
        // Fallback using JS if headers are already sent
        echo "<script>window.location.href='" . $url . "';</script>";
        exit();
    }
}

// 3. FLASH MESSAGES (Set a message to show on the next page)
// Usage: set_flash('success', 'Product added!');
function set_flash($type, $message) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash_type'] = $type; // 'success' or 'error'
    $_SESSION['flash_msg'] = $message;
}

// 4. DISPLAY FLASH MESSAGE
// Usage: display_flash(); (Put this where you want the alert to appear)
function display_flash() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    
    if (isset($_SESSION['flash_msg'])) {
        $type = $_SESSION['flash_type'];
        $msg = $_SESSION['flash_msg'];
        
        // CSS Classes for styling
        $class = ($type == 'success') ? 'alert-success' : 'alert-danger';
        $icon = ($type == 'success') ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        // CSS Styles (Inline to ensure it works everywhere)
        echo "
        <style>
            .alert-box { padding: 15px; margin-bottom: 20px; border-radius: 8px; font-weight: 500; display: flex; align-items: center; gap: 10px; }
            .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
            .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        </style>
        <div class='alert-box $class'>
            <i class='fas $icon'></i> $msg
        </div>";

        // Clear message after showing
        unset($_SESSION['flash_msg']);
        unset($_SESSION['flash_type']);
    }
}

// 5. FORMAT PRICE (Indian Rupee)
// Usage: echo format_price(1500); -> ₹1,500
function format_price($price) {
    return '₹' . number_format($price);
}

// 6. SHORTEN TEXT (For Product Cards)
// Usage: echo shorten_text($desc, 50);
function shorten_text($text, $limit = 100) {
    $text = $text . " ";
    $text = substr($text, 0, $limit);
    $text = substr($text, 0, strrpos($text, ' '));
    return $text . "...";
}

// 7. CHECK ACTIVE PAGE (For Sidebar/Navbar)
// Usage: echo is_active('index.php');
function is_active($page_name) {
    $current_page = basename($_SERVER['PHP_SELF']);
    return ($current_page == $page_name) ? 'active' : '';
}
?>