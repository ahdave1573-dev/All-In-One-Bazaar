<?php 
// ::::: 1. GET VARIABLES :::::
// Ensure we have the site URL and current page name for active states
$url = defined('SITEURL') ? SITEURL : '';
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar">
    
    <a href="<?php echo $url; ?>index.php" class="logo">All In One <span>Bazaar</span>.</a>
    
    <ul class="nav-links">
        <li>
            <a href="<?php echo $url; ?>index.php" class="<?php if($current_page == 'index.php'){echo 'active';} ?>">Home</a>
        </li>
        
        <li>
            <a href="<?php echo $url; ?>products.php" class="<?php if($current_page == 'products.php'){echo 'active';} ?>">Shop</a>
        </li>

        <li>
            <a href="<?php echo $url; ?>categories.php" class="<?php if($current_page == 'categories.php'){echo 'active';} ?>">Categories</a>
        </li>

        <li>
            <a href="<?php echo $url; ?>about.php" class="<?php if($current_page == 'about.php'){echo 'active';} ?>">About Us</a>
        </li>

        <li>
            <a href="<?php echo $url; ?>contact.php" class="<?php if($current_page == 'contact.php'){echo 'active';} ?>">Contact</a>
        </li>
    </ul>

    <div class="nav-icons">
        
        <a href="<?php echo $url; ?>search.php" title="Search"><i class="fas fa-search"></i></a>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="user-dropdown">
                <a href="<?php echo $url; ?>user/dashboard.php" title="My Account">
                    <i class="fas fa-user-circle"></i>
                </a>
                <div class="dropdown-content">
                    <a href="<?php echo $url; ?>user/dashboard.php">Dashboard</a>
                    <a href="<?php echo $url; ?>user/orders.php">My Orders</a>
                    <a href="<?php echo $url; ?>user/profile.php">Edit Profile</a>
                    <a href="<?php echo $url; ?>user/logout.php" style="color: red;">Logout</a>
                </div>
            </div>
        <?php else: ?>
            <a href="<?php echo $url; ?>login.php" title="Login / Register"><i class="fas fa-user"></i></a>
        <?php endif; ?>
        
        <div class="cart-icon">
            <a href="<?php echo $url; ?>user/cart.php" title="View Cart">
                <i class="fas fa-shopping-bag"></i>
                <span class="cart-badge">
                    <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                </span>
            </a>
        </div>
    </div>
</nav>