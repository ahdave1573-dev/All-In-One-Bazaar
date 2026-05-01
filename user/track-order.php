<?php
// ::::: 1. SECURITY & CONFIGURATION :::::
session_start();
include('../config/constants.php');
include('../config/db.php');
include('../config/auth.php'); // Login Check

$user_id = $_SESSION['user_id'];
$found_order = false;
$order_data = [];

// ::::: 2. CHECK LOGIC :::::
// Check if Tracking No is provided via URL (GET) or Form (POST)
if(isset($_REQUEST['tracking_no']) || isset($_GET['t'])) {
    
    $track_no = "";
    if(isset($_POST['track_btn'])) {
        $track_no = mysqli_real_escape_string($conn, $_POST['tracking_no']);
    } else if(isset($_GET['t'])) {
        $track_no = mysqli_real_escape_string($conn, $_GET['t']);
    }

    if($track_no != "") {
        // Query: Tracking number match + User ID match (Security)
        $query = "SELECT * FROM orders WHERE tracking_no='$track_no' AND user_id='$user_id' LIMIT 1";
        $query_run = mysqli_query($conn, $query);

        if(mysqli_num_rows($query_run) > 0) {
            $found_order = true;
            $order_data = mysqli_fetch_array($query_run);
        } else {
            $_SESSION['message'] = "Invalid Tracking Number or Order not found.";
        }
    }
}

include('../includes/header.php');
?>

<style>
    /* Layout */
    .dashboard-wrapper { display: flex; min-height: 80vh; background: #f8fafc; }
    .sidebar-area { width: 280px; flex-shrink: 0; background: #fff; border-right: 1px solid #e2e8f0; }
    .main-content { flex: 1; padding: 30px; }

    /* Search Box */
    .track-search-card {
        background: white; padding: 30px; border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #e2e8f0;
        margin-bottom: 30px; text-align: center;
    }
    .track-input {
        max-width: 400px; margin: 0 auto;
        padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1;
    }
    .track-btn {
        background: var(--dark); color: white; border: none;
        padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: 600;
        margin-top: 10px; transition: 0.3s;
    }
    .track-btn:hover { background: var(--primary); }

    /* Order Result Card */
    .result-card { background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; }
    .result-header { background: #f1f5f9; padding: 15px 20px; font-weight: 600; display: flex; justify-content: space-between; }

    /* ::::: PROGRESS BAR STYLING ::::: */
    .track-progress {
        display: flex; justify-content: space-between; position: relative;
        margin: 50px 20px;
    }
    /* The Line */
    .track-progress::before {
        content: ''; position: absolute; top: 15px; left: 0; right: 0;
        height: 4px; background: #e2e8f0; z-index: 1;
    }
    
    .step { position: relative; z-index: 2; text-align: center; width: 33.33%; }
    
    .step-icon {
        width: 35px; height: 35px; background: #e2e8f0; color: #64748b;
        border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
        font-weight: bold; margin-bottom: 10px; border: 4px solid white;
    }
    .step-label { font-size: 0.9rem; font-weight: 600; color: var(--gray); }

    /* Active State (Green) */
    .step.active .step-icon { background: #22c55e; color: white; }
    .step.active .step-label { color: #166534; }
    
    /* Cancelled State (Red) */
    .step.cancelled .step-icon { background: #ef4444; color: white; }
    .step.cancelled .step-label { color: #b91c1c; }

    /* Line Color Logic */
    .track-progress.status-0::before { background: linear-gradient(to right, #22c55e 33%, #e2e8f0 33%); }
    .track-progress.status-1::before { background: #22c55e; } /* Full Green */
    .track-progress.status-2::before { background: #ef4444; } /* Full Red */

    @media (max-width: 900px) {
        .dashboard-wrapper { flex-direction: column; }
        .sidebar-area { width: 100%; }
        .step-label { font-size: 0.75rem; }
    }
</style>

<div class="dashboard-wrapper">
    
    <div class="sidebar-area">
        <div class="sidebar-menu" style="padding: 20px;">
            <h4 style="margin-bottom:20px; color:var(--dark);">My Account</h4>
            <a href="dashboard.php" style="display:block; padding:10px; color:var(--gray); text-decoration:none;"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="orders.php" style="display:block; padding:10px; color:var(--gray); text-decoration:none;"><i class="fas fa-box me-2"></i> My Orders</a>
            <a href="track-order.php" style="display:block; padding:10px; color:var(--primary); font-weight:bold; background:#f1f5f9; border-radius:5px;"><i class="fas fa-map-marker-alt me-2"></i> Track Order</a>
            <a href="logout.php" style="display:block; padding:10px; color:#ef4444; text-decoration:none;"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        
        <h2 style="margin-bottom: 20px;">Track Your Order</h2>

        <div class="track-search-card">
            <h5>Enter your Tracking Number</h5>
            <p class="text-muted" style="font-size: 0.9rem;">You can find this in your 'My Orders' section.</p>
            
            <form action="track-order.php" method="POST">
                <input type="text" name="tracking_no" class="track-input w-100" placeholder="e.g. ORD778990" value="<?= isset($_GET['t']) ? $_GET['t'] : '' ?>" required>
                <br>
                <button type="submit" name="track_btn" class="track-btn">Track Now</button>
            </form>
            
            <?php if(isset($_SESSION['message'])): ?>
                <div style="margin-top: 15px; color: #ef4444; font-weight: 500;">
                    <?= $_SESSION['message']; ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
        </div>

        <?php if($found_order): ?>
            
            <div class="result-card">
                <div class="result-header">
                    <span>Order #<?= $order_data['id']; ?></span>
                    <span>Date: <?= date('d M Y', strtotime($order_data['created_at'])); ?></span>
                </div>
                
                <div style="padding: 30px;">
                    
                    <?php
                        // Logic to set classes based on Status
                        $raw_status = $order_data['status'];
                        if($raw_status === '2' || strtolower($raw_status) === 'cancelled') $status = 2;
                        elseif($raw_status === '1' || strtolower($raw_status) === 'completed' || strtolower($raw_status) === 'delivered') $status = 1;
                        else $status = 0;
                        // 0 = Pending, 1 = Completed/Delivered, 2 = Cancelled
                    ?>

                    <?php if($status == 2): ?>
                        <div class="track-progress status-2">
                            <div class="step cancelled">
                                <div class="step-icon"><i class="fas fa-times"></i></div>
                                <div class="step-label">Order Placed</div>
                            </div>
                            <div class="step cancelled">
                                <div class="step-icon"><i class="fas fa-ban"></i></div>
                                <div class="step-label">Cancelled</div>
                            </div>
                            <div class="step cancelled">
                                <div class="step-icon"><i class="fas fa-times-circle"></i></div>
                                <div class="step-label">Refund Processed</div>
                            </div>
                        </div>
                        <div style="text-align: center; color: #ef4444; font-weight: bold; margin-top: 20px;">
                            This order has been cancelled.
                        </div>

                    <?php else: ?>
                        <div class="track-progress status-<?= $status; ?>">
                            
                            <div class="step active">
                                <div class="step-icon"><i class="fas fa-clipboard-check"></i></div>
                                <div class="step-label">Order Placed</div>
                            </div>

                            <div class="step <?= ($status >= 0) ? 'active' : ''; ?>">
                                <div class="step-icon"><i class="fas fa-cog fa-spin" style="animation-duration: 3s;"></i></div>
                                <div class="step-label">Under Process</div>
                            </div>

                            <div class="step <?= ($status == 1) ? 'active' : ''; ?>">
                                <div class="step-icon"><i class="fas fa-truck"></i></div>
                                <div class="step-label">Delivered</div>
                            </div>

                        </div>

                        <?php if($status == 1): ?>
                             <div style="text-align: center; color: #16a34a; font-weight: bold; margin-top: 20px;">
                                <i class="fas fa-check-circle"></i> Successfully Delivered
                             </div>
                        <?php endif; ?>

                    <?php endif; ?>

                    <div style="text-align: center; margin-top: 30px;">
                        <a href="order-details.php?id=<?= $order_data['id']; ?>" style="color: var(--primary); font-weight: 600;">View Order Details</a>
                    </div>

                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php include('../includes/footer.php'); ?>