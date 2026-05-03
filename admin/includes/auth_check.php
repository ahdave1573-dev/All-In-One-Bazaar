<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* 🛑 NO-CACHE HEADERS (Prevents Back Button Access after Logout) */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

/* 🔐 ADMIN AUTH CHECK */
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    // Clear any potential stale session data
    $_SESSION = array();
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    
    // Redirect with cache-clearing headers
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Location: admin_login.php");
    exit();
}
?>
