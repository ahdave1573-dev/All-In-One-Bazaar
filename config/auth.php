<?php
// SECURITY CHECK

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user logged in
if (!isset($_SESSION['user_id'])) {

    if (defined('SITEURL')) {
        $login_url = SITEURL . 'login.php';
    } else {
        // fallback
        $login_url = '../login.php';
    }

    header("Location: " . $login_url);
    exit();
}
