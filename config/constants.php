<?php
// START SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// SITE URL (IMPORTANT)
define('SITEURL', 'http://localhost/All%20In%20One%20Bazaar/');

// DATABASE SETTINGS
define('LOCALHOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'all_in_one_bazaar');
