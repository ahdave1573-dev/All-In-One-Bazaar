<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Clear session variables
$_SESSION = array();

// 2. Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// 3. Destroy the session
session_unset();
session_destroy();

// 4. Prevent Caching of the Logout Process
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

header("Location: admin_login.php");
exit();
?>
