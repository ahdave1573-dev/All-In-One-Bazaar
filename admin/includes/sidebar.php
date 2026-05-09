<?php 
  $page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); 
?>
<style>
/* Sidebar Universal Styles */
.sidebar {
    width: 260px;
    background: #0f172a;
    color: #fff;
    display: flex;
    flex-direction: column;
    position: fixed;
    height: 100vh;
    left: 0;
    top: 0;
    z-index: 1000;
}
.sidebar-header {
    padding: 25px;
    font-size: 1.3rem;
    font-weight: 600;
    text-align: center;
    border-bottom: 1px solid #1e293b;
    margin: 0;
}
.sidebar-header span { color: #2563eb; }
.sidebar-menu { 
    flex: 1; 
    padding: 15px 0; 
    display: flex; 
    flex-direction: column; 
    margin: 0; 
}
.sidebar-menu a {
    display: flex;
    align-items: center;
    padding: 14px 25px;
    color: #94a3b8;
    text-decoration: none;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    transition: 0.3s;
}
.sidebar-menu a:hover, .sidebar-menu a.active {
    background: #1e293b;
    color: #fff;
    border-left: 4px solid #2563eb;
}
.sidebar-menu i {
    width: 30px;
    font-size: 16px;
    text-align: center;
    margin-right: 10px;
}
/* Ensure main content is not overlapped by fixed sidebar */
body {
    margin: 0;
}
.main-content {
    margin-left: 260px !important;
    width: calc(100% - 260px) !important;
}
</style>
<div class="sidebar">
    <div class="sidebar-header">All In One <span>Bazaar</span></div>
    <div class="sidebar-menu">
        <a href="dashboard.php" class="<?= $page == 'dashboard.php' ? 'active' : '' ?>"><i class="fas fa-home"></i>Dashboard</a>
        <a href="manage-orders.php" class="<?= in_array($page, ['manage-orders.php', 'order-details.php']) ? 'active' : '' ?>"><i class="fas fa-shopping-bag"></i>Orders</a>
        <a href="manage-products.php" class="<?= in_array($page, ['manage-products.php', 'add-product.php', 'edit-product.php']) ? 'active' : '' ?>"><i class="fas fa-box-open"></i>Products</a>
        <a href="manage-categories.php" class="<?= in_array($page, ['manage-categories.php', 'add-category.php', 'edit-category.php', 'categories.php']) ? 'active' : '' ?>"><i class="fas fa-list"></i>Categories</a>
        <a href="manage-users.php" class="<?= $page == 'manage-users.php' ? 'active' : '' ?>"><i class="fas fa-users"></i>Users</a>
        <a href="contact.php" class="<?= $page == 'contact.php' ? 'active' : '' ?>"><i class="fas fa-envelope"></i>Contact</a>
        <a href="reports.php" class="<?= $page == 'reports.php' ? 'active' : '' ?>"><i class="fas fa-chart-bar"></i>Reports</a>
        <a href="settings.php" class="<?= $page == 'settings.php' ? 'active' : '' ?>"><i class="fas fa-cog"></i>Settings</a>
        <a href="logout.php" style="color:#ef4444;margin-top:auto;"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
</div>
