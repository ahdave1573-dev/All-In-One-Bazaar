<?php
// ::::: 1. CONFIGURATION PATH FIX :::::
$path_to_root = (file_exists("config/constants.php")) ? "" : "../";

// Include Constants & DB
if(file_exists($path_to_root . "config/constants.php")){ include_once($path_to_root . "config/constants.php"); }
if(file_exists($path_to_root . "config/db.php")){ include_once($path_to_root . "config/db.php"); }

// ::::: 2. SESSION START :::::
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Current Page & URL setup
$current_page = basename($_SERVER['PHP_SELF']);
$url = defined('SITEURL') ? SITEURL : ''; 

// ::::: 3. FETCH ALL CATEGORIES FOR MEGA BAR :::::
$all_categories = [];
if(isset($conn)){
    $cat_query = mysqli_query($conn, "SELECT id, name, image FROM categories WHERE status=0 ORDER BY name ASC");
    if($cat_query){
        while($cat_row = mysqli_fetch_assoc($cat_query)){
            $all_categories[] = $cat_row;
        }
    }
}

// Category icon mapping
$cat_icons = [
    'electronics' => 'fa-laptop',
    'fashion' => 'fa-shirt',
    'home & kitchen' => 'fa-house',
    'books' => 'fa-book',
    'sports & outdoors' => 'fa-futbol',
    'beauty & personal care' => 'fa-spa',
    'toys & games' => 'fa-gamepad',
    'grocery & gourmet' => 'fa-cart-shopping',
    'health & wellness' => 'fa-heart-pulse',
    'automotive' => 'fa-car',
    'baby products' => 'fa-baby',
    'pet supplies' => 'fa-paw',
    'office supplies' => 'fa-briefcase',
    'garden & outdoors' => 'fa-leaf',
    'jewelry & watches' => 'fa-gem',
    'shoes & handbags' => 'fa-shoe-prints',
    'music & instruments' => 'fa-music',
    'mobiles' => 'fa-mobile-screen',
    'laptops' => 'fa-laptop',
    'cameras' => 'fa-camera',
    'audio' => 'fa-headphones',
    'accessories' => 'fa-plug',
    'smartphones' => 'fa-mobile-screen',
    'computers' => 'fa-desktop',
    'headphones' => 'fa-headphones',
    'smartwatches' => 'fa-clock',
    'tablets' => 'fa-tablet-screen-button',
    'gaming' => 'fa-gamepad',
    'wearables' => 'fa-clock',
];

function getCategoryIcon($name, $icons) {
    $lower = strtolower(trim($name));
    return isset($icons[$lower]) ? $icons[$lower] : 'fa-tag';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All In One Bazaar | Shop Everything Online</title>
    <meta name="description" content="All In One Bazaar - Your one-stop online marketplace. Shop fashion, electronics, home essentials, books, sports, beauty and more at the best prices.">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">

    <style>

/* ::::: CUSTOM COLOR PALETTE VARIABLES ::::: */
:root {
    --primary: #2563EB; 
    --secondary: #1E40AF; 
    --accent: #F59E0B; 
    --bg-color: #F9FAFB;
    --text-dark: #111827; 
    --text-light: #ffffff;
    --gray-light: #f1f3f4;
    --gray-hover: #e5e7eb;
    
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 25px rgba(0,0,0,0.15);
}

* { margin: 0; padding: 0; box-sizing: border-box; font-family: "Poppins", sans-serif; }
body { background: var(--bg-color); color: var(--text-dark); display: flex; flex-direction: column; min-height: 100vh; overflow-x: hidden; }

a { text-decoration: none; color: inherit; } 
ul { list-style: none; }

/* ::::: TOP BAR ::::: */
.top-bar { 
    background: var(--text-dark); 
    color: var(--text-light); 
    font-size: 0.85rem; 
    padding: 8px 0; 
}
.top-bar-inner { 
    max-width: 1200px; 
    margin: 0 auto; 
    padding: 0 20px; 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
}
.top-bar a { 
    transition: all 0.3s ease; 
    padding: 4px 10px; 
    border-radius: 4px;
}
.top-bar a:hover { 
    background: rgba(255,255,255,0.1); 
    color: var(--accent); 
}
.top-bar-links { display: flex; gap: 10px; }

/* ::::: MAIN NAVBAR ::::: */
.navbar { 
    background: var(--primary); 
    box-shadow: var(--shadow-md); 
    position: sticky; 
    top: 0; 
    z-index: 1000; 
    width: 100%; 
}
.nav-container { 
    max-width: 1250px; 
    margin: 0 auto; 
    padding: 8px 15px; 
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center; 
    gap: 20px; 
}

/* Logo */
.logo { 
    font-size: 24px; 
    font-weight: 800; 
    color: var(--text-light); 
    letter-spacing: -0.5px; 
    flex-shrink: 0;
}
.logo span { color: var(--accent); }

/* ::::: SEARCH BAR ::::: */
.nav-search { 
    width: 100%;
    max-width: 550px; 
    height: 40px; 
    display: flex; 
    background: #fff; 
    border-radius: 4px; 
    overflow: hidden; 
    border: 1px solid #ddd;
    margin: 0 auto; /* Center within the grid column */
}
.nav-search:focus-within {
    box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.3);
    border-color: var(--accent);
}
.nav-search select { 
    border: none; 
    background: #f3f4f6; 
    padding: 0 15px; 
    font-size: 0.85rem; 
    color: #4b5563; 
    outline: none; 
    border-right: 1px solid #e5e7eb; 
    cursor: pointer; 
    font-weight: 500;
    height: 100%;
    flex-shrink: 0;
}
.nav-search input { 
    flex: 1; 
    border: none; 
    padding: 0 15px; 
    font-size: 0.95rem; 
    outline: none; 
    color: var(--text-dark); 
    height: 100%;
}
.nav-search button { 
    background: var(--accent); 
    color: #fff; 
    border: none; 
    height: 100%;
    width: 42px !important;
    min-width: 42px !important;
    font-size: 0.95rem; 
    cursor: pointer; 
    display: flex !important; 
    align-items: center; 
    justify-content: center;
    flex-shrink: 0;
}
.nav-search button:hover { 
    background: #d97706; 
}

/* ::::: NAV LINKS ::::: */
/* Wrapper for right side links & icons */
.nav-right {
    display: flex;
    align-items: center;
    gap: 20px;
}
.nav-links { display: flex; gap: 8px; }
.nav-links li a { 
    font-size: 14px; 
    font-weight: 600; 
    color: var(--text-light); 
    padding: 6px 10px; 
    border-radius: 4px; 
    transition: all 0.3s; 
}
.nav-links li a:hover, .nav-links li a.active { 
    background: var(--secondary); 
    color: var(--text-light); 
}

/* ::::: NAV ICONS ::::: */
.nav-icons { display: flex; align-items: center; gap: 12px; }
.nav-icons > a { 
    color: var(--primary); 
    background: var(--text-light); 
    font-size: 1.2rem; 
    width: 42px; 
    height: 42px; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    border-radius: 50%; 
    transition: 0.3s; 
    position: relative;
}
.nav-icons > a:hover { 
    background: var(--accent); 
    color: var(--text-light); 
    transform: translateY(-2px); 
}

/* Cart Badge */
.cart-badge { 
    position: absolute; 
    top: -5px; 
    right: -5px; 
    background: var(--accent); 
    color: var(--text-light); 
    font-size: 11px; 
    font-weight: 700; 
    height: 22px; 
    width: 22px; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    border-radius: 50%; 
    box-shadow: var(--shadow-sm); 
}

/* User Dropdown Button */
.user-dropdown { 
    display: flex; 
    align-items: center; 
    background: var(--text-light); 
    padding: 6px 14px 6px 8px; 
    border-radius: 25px; 
    cursor: pointer; 
    transition: 0.3s; 
    position: relative;
}
.user-dropdown:hover { 
    background: var(--gray-light); 
}
.user-name-span { 
    font-size: 0.9rem; 
    font-weight: 600; 
    color: var(--text-dark);
}

/* Dropdown Menu */
.dropdown-content { 
    visibility: hidden; 
    opacity: 0; 
    position: absolute; 
    right: 0; 
    top: calc(100% + 10px); 
    background: var(--text-light); 
    min-width: 220px; 
    box-shadow: var(--shadow-lg); 
    border-radius: 8px; 
    border: 1px solid var(--gray-hover);
    overflow: hidden; 
    transition: all 0.3s ease; 
    z-index: 1001; 
}
.dropdown-content.show { 
    visibility: visible; 
    opacity: 1; 
    transform: translateY(0); 
}
.dropdown-content a { 
    color: var(--text-dark); 
    padding: 12px 20px; 
    display: flex; 
    align-items: center; 
    gap: 12px; 
    font-size: 14px; 
    border-bottom: 1px solid var(--gray-light); 
    transition: 0.2s; 
}
.dropdown-content a:hover { 
    background: var(--gray-light); 
    color: var(--primary);
    padding-left: 25px; 
}

/* ::::: CATEGORY BAR ::::: */
.category-bar { 
    background: var(--text-dark); 
    width: 100%; 
}
.category-bar-inner { 
    max-width: 1200px; 
    margin: 0 auto; 
    display: flex; 
    align-items: center; 
    padding: 0 10px; 
    overflow-x: auto; 
    scrollbar-width: none; 
}
.category-bar-inner::-webkit-scrollbar { display: none; }
.cat-bar-item { 
    color: var(--text-light); 
    font-size: 0.9rem; 
    font-weight: 500; 
    padding: 12px 18px; 
    white-space: nowrap; 
    display: flex; 
    align-items: center; 
    gap: 8px; 
    transition: 0.3s; 
}
.cat-bar-item:hover { 
    background: rgba(255,255,255,0.1); 
    color: var(--accent); 
}

/* Responsive & Mobile Menus */
.hamburger { display: none; background: none; border: none; font-size: 1.5rem; color: var(--text-light); cursor: pointer; }
.mobile-menu-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9998; }
.mobile-menu-overlay.show { display: block; }
.mobile-menu { position: fixed; top: 0; left: -320px; width: 300px; height: 100vh; background: var(--bg-color); z-index: 9999; transition: 0.3s; overflow-y: auto; }
.mobile-menu.show { left: 0; }
.mobile-menu-header { background: var(--secondary); color: var(--text-light); padding: 20px; display: flex; justify-content: space-between; align-items: center; }
.mobile-menu-header h3 { font-size: 1.2rem; display: flex; align-items: center; gap: 10px; color: var(--text-light); }
.mobile-menu-close { background: rgba(255,255,255,0.2); border: none; color: var(--text-light); width: 35px; height: 35px; border-radius: 50%; cursor: pointer; }
.mobile-nav-links a { display: flex; gap: 15px; padding: 15px 25px; color: var(--text-dark); font-weight: 500; border-bottom: 1px solid var(--gray-hover); }
.mobile-nav-links a i { width: 25px; text-align: center; color: var(--primary); }
.mobile-nav-links a:hover { background: var(--gray-light); color: var(--secondary); padding-left: 30px; }
.mobile-cat-title { padding: 20px 25px 10px; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; color: #6b7280; }

@media(max-width:991px){ 
    .nav-links, .nav-search { display: none !important; } 
    .hamburger { display: block; } 
    .category-bar { display: none; } 
    .user-name-span { display: none; }
}
</style>
</head>
<body>

    <!-- TOP INFO BAR -->
    <div class="top-bar">
        <div class="top-bar-inner">
            <span><i class="fas fa-truck-fast"></i> Free Delivery on orders above ₹499</span>
            <div class="top-bar-links">
                <a href="<?php echo $url; ?>faq.php">Help</a>
                <a href="<?php echo $url; ?>user/orders.php"><i class="fas fa-box"></i> Track Order</a>
            </div>
        </div>
    </div>

    <!-- MAIN NAVBAR -->
    <nav class="navbar">
        <div class="nav-container">
            
            <!-- Hamburger -->
            <button class="hamburger" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>

            <a href="<?php echo $url; ?>index.php" class="logo">All In One <span>Bazaar.com</span>.</a>
            
            <!-- AMAZON-STYLE SEARCH BAR -->
            <form action="<?php echo $url; ?>search.php" method="GET" class="nav-search">
                <select name="cat" id="nav-search-cat">
                    <option value="">All Categories</option>
                    <?php foreach($all_categories as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?> </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="search" placeholder="Search..." autocomplete="off">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>

            <div class="nav-right">
                <!-- NAV LINKS (Desktop) -->
                <ul class="nav-links">
                    <li><a href="<?php echo $url; ?>index.php" class="<?php if($current_page == 'index.php'){echo 'active';} ?>">Home</a></li>
                    <li><a href="<?php echo $url; ?>products.php" class="<?php if($current_page == 'products.php'){echo 'active';} ?>">Shop</a></li>
                    <li><a href="<?php echo $url; ?>categories.php" class="<?php if($current_page == 'categories.php'){echo 'active';} ?>">Categories</a></li>
                    <li><a href="<?php echo $url; ?>about.php" class="<?php if($current_page == 'about.php'){echo 'active';} ?>">About</a></li>
                </ul>

                <div class="nav-icons">
                    
                    <?php if(isset($_SESSION['user_id'])): ?>

                        <!-- Wishlist -->
                        <a href="<?php echo $url; ?>user/wishlist.php" title="Wishlist" class="wishlist-icon-wrapper">
                            <i class="fas fa-heart"></i>
                        </a>
                        
                        <div class="user-dropdown" onclick="toggleUserMenu(event)">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-user-circle" style="font-size: 1.4rem; color: var(--primary);"></i>
                                <span class="user-name-span">
                                    <?php echo isset($_SESSION['user_name']) ? explode(' ', $_SESSION['user_name'])[0] : 'User'; ?>
                                </span>
                                <i class="fas fa-chevron-down" style="font-size: 0.7rem; color: var(--gray);"></i>
                            </div>
                            
                            <div class="dropdown-content" id="userMenu">
                                <a href="<?php echo $url; ?>user/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                                <a href="<?php echo $url; ?>user/profile.php"><i class="fas fa-user-edit"></i> Edit Profile</a>
                                <a href="<?php echo $url; ?>user/orders.php"><i class="fas fa-box"></i> My Orders</a>
                                <a href="<?php echo $url; ?>user/wishlist.php"><i class="fas fa-heart"></i> My Wishlist</a>
                                <a href="<?php echo $url; ?>user/logout.php" style="color: #ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            </div>
                        </div>

                    <?php else: ?>
                        <a href="<?php echo $url; ?>login.php" title="Login">
                            <i class="fas fa-user"></i>
                        </a>
                    <?php endif; ?>

                    <a href="<?php echo $url; ?>user/cart.php" title="View Cart" class="cart-icon-wrapper">
                        <i class="fas fa-shopping-cart"></i>
                    <?php 
                        $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                        if($cart_count > 0): 
                    ?>
                        <span class="cart-badge"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </nav>

    <!-- AMAZON-STYLE CATEGORY BAR -->
    <div class="category-bar">
        <div class="category-bar-inner">
            <a href="<?php echo $url; ?>products.php" class="cat-bar-item cat-bar-all">
                <i class="fas fa-bars"></i> All
            </a>
            <?php foreach($all_categories as $c): ?>
                <a href="<?php echo $url; ?>products.php?cat=<?= $c['id'] ?>" class="cat-bar-item">
                    <i class="fas <?= getCategoryIcon($c['name'], $cat_icons) ?>"></i>
                    <?= htmlspecialchars($c['name']) ?>
                </a>
            <?php endforeach; ?>
            <a href="<?php echo $url; ?>products.php?deals=1" class="cat-bar-item" style="color: var(--amazon-orange);">
                <i class="fas fa-bolt"></i> Today's Deals
            </a>
        </div>
    </div>

    <!-- MOBILE SIDE MENU -->
    <div class="mobile-menu-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <h3><i class="fas fa-user-circle"></i> 
                <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Hello, Sign in'; ?>
            </h3>
            <button class="mobile-menu-close" onclick="toggleMobileMenu()"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="mobile-nav-links">
            <a href="<?php echo $url; ?>index.php"><i class="fas fa-home"></i> Home</a>
            <a href="<?php echo $url; ?>products.php"><i class="fas fa-store"></i> Shop All</a>
            <a href="<?php echo $url; ?>products.php?deals=1"><i class="fas fa-bolt"></i> Today's Deals</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?php echo $url; ?>user/orders.php"><i class="fas fa-box"></i> My Orders</a>
                <a href="<?php echo $url; ?>user/wishlist.php"><i class="fas fa-heart"></i> My Wishlist</a>
                <a href="<?php echo $url; ?>user/cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
            <?php else: ?>
                <a href="<?php echo $url; ?>login.php"><i class="fas fa-sign-in-alt"></i> Sign In</a>
                <a href="<?php echo $url; ?>register.php"><i class="fas fa-user-plus"></i> Register</a>
            <?php endif; ?>
        </div>

        <div class="mobile-cat-title">Shop by Category</div>
        <div class="mobile-nav-links">
            <?php foreach($all_categories as $c): ?>
                <a href="<?php echo $url; ?>products.php?cat=<?= $c['id'] ?>">
                    <i class="fas <?= getCategoryIcon($c['name'], $cat_icons) ?>"></i>
                    <?= htmlspecialchars($c['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if(isset($_SESSION['user_id'])): ?>
        <div class="mobile-cat-title">Account</div>
        <div class="mobile-nav-links">
            <a href="<?php echo $url; ?>user/profile.php"><i class="fas fa-user-edit"></i> Edit Profile</a>
            <a href="<?php echo $url; ?>user/logout.php" style="color:#ef4444"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        function toggleUserMenu(event) {
            var menu = document.getElementById("userMenu");
            menu.classList.toggle("show");
            event.stopPropagation();
        }

        window.onclick = function(event) {
            if (!event.target.closest('.user-dropdown')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    if (dropdowns[i].classList.contains('show')) {
                        dropdowns[i].classList.remove('show');
                    }
                }
            }
        }

        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('show');
            document.getElementById('mobileOverlay').classList.toggle('show');
            document.body.style.overflow = document.getElementById('mobileMenu').classList.contains('show') ? 'hidden' : '';
        }
    </script>

    <main>