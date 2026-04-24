<?php
// ================== SECURITY & CONFIG ==================
session_start();

include('../config/constants.php');
include('../config/db.php');
include('../config/auth.php'); // login check

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id   = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

// ================== METRICS ==================

// Total Orders
$q1 = mysqli_query($conn, "SELECT id FROM orders WHERE user_id='$user_id'");
$total_orders = $q1 ? mysqli_num_rows($q1) : 0;

// Pending Orders (Fix: Check for 0 or pending)
$q2 = mysqli_query($conn, "SELECT id FROM orders 
                           WHERE user_id='$user_id' 
                           AND (status='0' OR LOWER(status)='pending')");
$pending_orders = $q2 ? mysqli_num_rows($q2) : 0;

// Cart Items
$cart_items = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// ================== HEADER ==================
include('../includes/header.php');
?>

<style>
.dashboard-wrapper{
    display:flex;
    min-height:80vh;
    background:#f8fafc;
}
.sidebar-area{
    width:280px;
    background:#fff;
}
.main-content{
    flex:1;
    padding:30px;
}

/* Welcome Banner */
.welcome-banner{
    background:linear-gradient(135deg,#2563eb,#1e40af);
    color:#fff;
    padding:30px;
    border-radius:15px;
    margin-bottom:30px;
}
.welcome-banner h1{margin-bottom:5px}

/* Stats */
.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:25px;
    margin-bottom:35px;
}
.stat-card{
    background:#fff;
    padding:22px;
    border-radius:12px;
    display:flex;
    gap:18px;
    border:1px solid #e5e7eb;
}
.stat-icon{
    width:55px;height:55px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
}
.blue{background:#eff6ff;color:#2563eb}
.orange{background:#fff7ed;color:#ea580c}
.green{background:#f0fdf4;color:#16a34a}

.stat-info h3{font-size:26px;margin:0}
.stat-info p{margin:0;color:#64748b;font-size:14px}

/* Orders Table - FIXED CSS */
.table-box{
    background:#fff;
    padding:25px;
    border-radius:15px;
    border:1px solid #e5e7eb;
}
.table-header{
    display:flex;
    justify-content:space-between;
    margin-bottom:15px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

/* અહીં ફેરફાર કર્યો છે: text-align left જેથી બધું લાઈનમાં આવે */
th, td {
    padding: 15px 10px;
    border-bottom: 1px solid #f1f5f9;
    text-align: left; 
    vertical-align: middle;
}

th {
    color: #64748b;
    font-size: 14px;
    font-weight: 600;
}

td {
    color: #333;
    font-size: 14px;
}

/* Status Badges */
.badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.pending { background:#fff7ed; color:#c2410c; }
.delivered { background:#f0fdf4; color:#15803d; }
.cancelled { background:#fee2e2; color:#b91c1c; }

@media(max-width:900px){
    .dashboard-wrapper{flex-direction:column}
    .sidebar-area{width:100%}
    .table-box { overflow-x: auto; } /* Mobile scroll support */
}
</style>

<div class="dashboard-wrapper">

    <div class="sidebar-area">
        <?php include('../includes/sidebar.php'); ?>
    </div>

    <div class="main-content">

        <div class="welcome-banner">
            <h1>Welcome back, <?php echo htmlspecialchars($user_name); ?>!</h1>
            <p>Manage your orders, profile and shopping activity.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-shopping-bag"></i></div>
                <div class="stat-info">
                    <h3><?php echo $total_orders; ?></h3>
                    <p>Total Orders</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <h3><?php echo $pending_orders; ?></h3>
                    <p>Pending Orders</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-shopping-cart"></i></div>
                <div class="stat-info">
                    <h3><?php echo $cart_items; ?></h3>
                    <p>Cart Items</p>
                </div>
            </div>
        </div>

        <div class="table-box">
            <div class="table-header">
                <h3>Recent Orders</h3>
                <a href="orders.php" style="color:#2563eb;font-weight:600;text-decoration:none;">View All</a>
            </div>

            <?php
            $recent = mysqli_query($conn,
                "SELECT * FROM orders 
                 WHERE user_id='$user_id' 
                 ORDER BY created_at DESC LIMIT 5"
            );

            if ($recent && mysqli_num_rows($recent) > 0) {
                echo "<table>
                        <thead>
                            <tr>
                                <th width='15%'>Order ID</th>
                                <th width='20%'>Date</th>
                                <th width='20%'>Total</th>
                                <th width='20%'>Status</th>
                                <th width='15%'>Action</th>
                            </tr>
                        </thead><tbody>";

                while ($o = mysqli_fetch_assoc($recent)) {

                    // DATE CHECK (Fallback if created_at is missing)
                    $date_val = isset($o['created_at']) ? $o['created_at'] : $o['order_date'];
                    
                    // STATUS LOGIC (Fixing 0/1/2 display)
                    $st_code = $o['status'];
                    $st_cls = 'pending';
                    $st_txt = 'Pending';

                    if($st_code == '0' || strtolower($st_code) == 'pending'){
                        $st_cls = 'pending'; $st_txt = 'Pending';
                    } elseif($st_code == '1' || strtolower($st_code) == 'completed' || strtolower($st_code) == 'delivered'){
                        $st_cls = 'delivered'; $st_txt = 'Delivered';
                    } elseif($st_code == '2' || strtolower($st_code) == 'cancelled'){
                        $st_cls = 'cancelled'; $st_txt = 'Cancelled';
                    } else {
                        $st_txt = ucfirst($st_code);
                    }

                    echo "<tr>
                            <td>ORD-" . str_pad($o['id'], 4, '0', STR_PAD_LEFT) . "</td>
                            <td>" . date('d M Y', strtotime($date_val)) . "</td>
                            <td>₹" . number_format($o['total_price']) . "</td>
                            <td><span class='badge $st_cls'>$st_txt</span></td>
                            <td><a href='order-details.php?id={$o['id']}' style='color:#2563eb;font-weight:600;text-decoration:none;'>View</a></td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p style='text-align:center;color:#94a3b8;padding:20px;'>
                        No orders yet. <a href='../products.php' style='color:#2563eb'>Start Shopping</a>
                      </p>";
            }
            ?>
        </div>

    </div>
</div>

<?php include('../includes/footer.php'); ?>