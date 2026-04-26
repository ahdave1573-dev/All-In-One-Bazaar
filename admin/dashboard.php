<?php
require_once('includes/auth_check.php');
include('../config/db.php');

/* ================= STATS ================= */

// Total Orders
$orderResult = mysqli_query($conn, "SELECT id FROM orders");
$total_orders = $orderResult ? mysqli_num_rows($orderResult) : 0;

// ✅ Pending Orders (string based)
$pendingResult = mysqli_query(
    $conn,
    "SELECT id FROM orders WHERE status='Pending'"
);
$pending_orders = $pendingResult ? mysqli_num_rows($pendingResult) : 0;

// Total Products
$productResult = mysqli_query($conn, "SELECT id FROM products");
$total_products = $productResult ? mysqli_num_rows($productResult) : 0;

//users
$userResult = mysqli_query($conn, "SELECT id FROM users");
$total_users = $userResult ? mysqli_num_rows($userResult) : 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | All In One Bazaar</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#f1f5f9;min-height:100vh}

.main-content{padding:30px}
.welcome-header{margin-bottom:30px}
.welcome-header h2{color:#0f172a;font-weight:600}
.welcome-header p{color:#64748b}

.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:40px}
.stat-card{background:#fff;padding:20px;border-radius:12px;display:flex;justify-content:space-between;align-items:center;border-left:4px solid #2563eb}
.stat-info h3{font-size:1.8rem;color:#1e293b}
.stat-info p{color:#64748b}
.stat-icon{font-size:2.3rem;opacity:.15;color:#2563eb}

.actions-title{font-size:1.2rem;font-weight:600;margin-bottom:20px}
.actions-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px}
.action-card{background:#fff;padding:25px;border-radius:12px;text-align:center;text-decoration:none;color:inherit;box-shadow:0 4px 15px rgba(0,0,0,.05);transition:.3s;border:1px solid #e2e8f0}
.action-card:hover{transform:translateY(-5px);border-color:#2563eb}
.action-icon{width:60px;height:60px;border-radius:50%;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto 15px}
.action-card h4{color:#1e293b;margin-bottom:5px}
.action-card p{font-size:.85rem;color:#64748b}
</style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

<div class="welcome-header">
    <h2>Welcome, <?= $_SESSION['admin_name']; ?> 👋</h2>
    <p>Here is your admin overview & management panel.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <h3><?= $total_orders ?></h3>
            <p>Total Orders</p>
        </div>
        <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
    </div>

    <div class="stat-card" style="border-left-color:#f59e0b">
        <div class="stat-info">
            <h3><?= $pending_orders ?></h3>
            <p>Pending Orders</p>
        </div>
        <div class="stat-icon" style="color:#f59e0b"><i class="fas fa-clock"></i></div>
    </div>

    <div class="stat-card" style="border-left-color:#10b981">
        <div class="stat-info">
            <h3><?= $total_products ?></h3>
            <p>Products</p>
        </div>
        <div class="stat-icon" style="color:#10b981"><i class="fas fa-box"></i></div>
    </div>

    <div class="stat-card" style="border-left-color:#8b5cf6">
        <div class="stat-info">
            <h3><?= $total_users ?></h3>
            <p>Customers</p>
        </div>
        <div class="stat-icon" style="color:#8b5cf6"><i class="fas fa-users"></i></div>
    </div>
</div>

<h3 class="actions-title">Management Modules</h3>
<div class="actions-grid">

    <a href="manage-users.php" class="action-card">
        <div class="action-icon"><i class="fas fa-user-friends"></i></div>
        <h4>Manage Users</h4>
        <p>View & delete customers</p>
    </a>

    <a href="manage-categories.php" class="action-card">
        <div class="action-icon"><i class="fas fa-layer-group"></i></div>
        <h4>Categories</h4>
        <p>Add & manage categories</p>
    </a>

    <a href="manage-products.php" class="action-card">
        <div class="action-icon"><i class="fas fa-box"></i></div>
        <h4>Products</h4>
        <p>Add, edit & delete products</p>
    </a>

    <a href="manage-orders.php" class="action-card">
        <div class="action-icon"><i class="fas fa-receipt"></i></div>
        <h4>Orders</h4>
        <p>Process customer orders</p>
    </a>

    <!-- ✅ FIXED ICON -->
    <a href="contact.php" class="action-card">
        <div class="action-icon"><i class="fas fa-envelope"></i></div>
        <h4>Contact</h4>
        <p>Contact details</p>
    </a>

</div>

</div>
</body>
</html>
