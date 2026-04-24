<?php
// ::::: 1. CONFIGURATION ::::: 
$url = defined('SITEURL') ? SITEURL : '';
$current_page = basename($_SERVER['PHP_SELF']);

// ::::: 2. DETECT CONTEXT (Admin vs User) ::::: 
$is_admin_panel = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false);
?>

<style>
/* SIDEBAR STYLING */
.sidebar-wrapper{
    background:#ffffff;
    width:100%;
    min-height:100%;
    border-right:1px solid #e2e8f0;
    padding:20px;
    box-shadow:2px 0 10px rgba(0,0,0,0.03);
}

.user-info-box{
    text-align:center;
    padding-bottom:20px;
    border-bottom:1px solid #f1f5f9;
    margin-bottom:20px;
}

/* AVATAR */
.user-avatar{
    width:80px;
    height:80px;
    border-radius:50%;
    margin:0 auto 10px;
    border:3px solid var(--primary);
    background:#eff6ff;
    display:flex;
    align-items:center;
    justify-content:center;
}
.user-avatar i{
    font-size:2.5rem;
    color:var(--primary);
}

.user-name{
    font-weight:700;
    color:var(--dark);
    font-size:1.1rem;
}
.user-role{
    font-size:0.85rem;
    color:var(--gray);
    text-transform:uppercase;
    letter-spacing:1px;
}

/* MENU */
.sidebar-menu{list-style:none;padding:0;margin:0}
.sidebar-menu li{margin-bottom:8px}

.sidebar-menu a{
    display:flex;
    align-items:center;
    padding:12px 15px;
    color:var(--gray);
    border-radius:8px;
    transition:0.3s;
    font-weight:500;
    font-size:0.95rem;
}
.sidebar-menu a i{
    width:30px;
    font-size:1.1rem;
}

/* Hover & Active */
.sidebar-menu a:hover{
    background:#f8fafc;
    color:var(--primary);
    padding-left:20px;
}
.sidebar-menu a.active{
    background:var(--primary);
    color:#fff;
    box-shadow:0 5px 15px rgba(37,99,235,0.2);
}
.sidebar-menu a.active i{color:#fff}

.logout-btn{color:#ef4444 !important}
.logout-btn:hover{
    background:#fee2e2 !important;
    color:#991b1b !important;
}

/* Responsive */
@media(max-width:900px){
    .sidebar-wrapper{
        margin-bottom:30px;
        border-right:none;
        border-bottom:1px solid #e2e8f0;
    }
}
</style>

<div class="sidebar-wrapper">

    <!-- USER INFO -->
    <div class="user-info-box">
        <div class="user-avatar">
            <?php if($is_admin_panel): ?>
                <i class="fas fa-user-shield"></i>
            <?php else: ?>
                <i class="fas fa-user"></i>
            <?php endif; ?>
        </div>

        <div class="user-name">
            <?php
            echo isset($_SESSION['user_name'])
                ? htmlspecialchars($_SESSION['user_name'])
                : (isset($_SESSION['admin_name'])
                    ? htmlspecialchars($_SESSION['admin_name'])
                    : 'Guest');
            ?>
        </div>

        <div class="user-role">
            <?php echo $is_admin_panel ? 'Administrator' : 'Customer'; ?>
        </div>
    </div>

    <!-- MENU -->
    <ul class="sidebar-menu">

    <?php if($is_admin_panel): ?>

        <li>
            <a href="dashboard.php" class="<?= $current_page=='dashboard.php'?'active':'' ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>

        <li>
            <a href="manage-products.php" class="<?= in_array($current_page,['manage-products.php','add-product.php'])?'active':'' ?>">
                <i class="fas fa-box-open"></i> Products
            </a>
        </li>

        <li>
            <a href="manage-categories.php" class="<?= $current_page=='manage-categories.php'?'active':'' ?>">
                <i class="fas fa-layer-group"></i> Categories
            </a>
        </li>

        <li>
            <a href="manage-orders.php" class="<?= $current_page=='manage-orders.php'?'active':'' ?>">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
        </li>

        <li>
            <a href="manage-users.php" class="<?= $current_page=='manage-users.php'?'active':'' ?>">
                <i class="fas fa-users"></i> Users
            </a>
        </li>

        <li>
            <a href="settings.php" class="<?= $current_page=='settings.php'?'active':'' ?>">
                <i class="fas fa-cog"></i> Settings
            </a>
        </li>

        <li style="margin-top:20px;border-top:1px solid #f1f5f9;padding-top:10px;">
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Admin Logout
            </a>
        </li>

    <?php else: ?>

        <li>
            <a href="dashboard.php" class="<?= $current_page=='dashboard.php'?'active':'' ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </li>

        <li>
            <a href="orders.php" class="<?= $current_page=='orders.php'?'active':'' ?>">
                <i class="fas fa-box"></i> My Orders
            </a>
        </li>

        <li>
            <a href="cart.php" class="<?= $current_page=='cart.php'?'active':'' ?>">
                <i class="fas fa-shopping-bag"></i> My Cart
            </a>
        </li>

        <li>
            <a href="wishlist.php" class="<?= $current_page=='wishlist.php'?'active':'' ?>">
                <i class="fas fa-heart"></i> Wishlist
            </a>
        </li>

        <li>
            <a href="track-order.php" class="<?= $current_page=='track-order.php'?'active':'' ?>">
                <i class="fas fa-map-marker-alt"></i> Track Order
            </a>
        </li>

        <li>
            <a href="profile.php" class="<?= $current_page=='profile.php'?'active':'' ?>">
                <i class="fas fa-user-edit"></i> Edit Profile
            </a>
        </li>

        <li>
            <a href="change-password.php" class="<?= $current_page=='change-password.php'?'active':'' ?>">
                <i class="fas fa-key"></i> Change Password
            </a>
        </li>

        <li style="margin-top:20px;border-top:1px solid #f1f5f9;padding-top:10px;">
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>

    <?php endif; ?>

    </ul>
</div>
