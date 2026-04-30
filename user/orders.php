<?php
session_start();

/* ================= CONFIG & DB ================= */
include("../config/constants.php");
include("../config/db.php");

/* ================= AUTH CHECK ================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ================= HEADER ================= */
include("../includes/header.php");
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

:root{
    --primary: #2563eb;
    --primary-hover: #1d4ed8;
    --dark: #0f172a;
    --gray: #64748b;
    --light-bg: #f1f5f9;
    --white: #ffffff;
    --border: #e2e8f0;
}

body {
    font-family: 'Inter', system-ui, sans-serif;
    background-color: #f8fafc;
}

.page-container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px;
    min-height: 60vh;
}

.page-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Order Card Styling */
.order-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 12px;
    margin-bottom: 25px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s, box-shadow 0.2s;
}

.order-card:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    border-color: #cbd5e1;
}

.order-header {
    background: #f8fafc;
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
    gap: 20px;
}

.header-info {
    display: flex;
    gap: 40px;
    flex-wrap: wrap;
}

.info-group {
    display: flex;
    flex-direction: column;
}

.label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray);
    font-weight: 600;
    margin-bottom: 4px;
}

.value {
    font-size: 0.95rem;
    color: var(--dark);
    font-weight: 600;
}

/* Status Badge Styling */
.badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
/* Colors based on Status */
.badge.Pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
.badge.Completed { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
.badge.Delivered { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
.badge.Cancelled { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }
/* Default gray if status unknown */
.badge.Unknown { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

.order-body { padding: 0 25px; }

.item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
    border-bottom: 1px solid var(--border);
}
.item-row:last-of-type { border-bottom: none; }

.item-left { display: flex; align-items: center; gap: 15px; }
.item-img-placeholder {
    width: 50px; height: 50px;
    background: #f1f5f9;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    color: #cbd5e1; font-size: 1.5rem;
}

.item-name { font-weight: 500; color: var(--dark); font-size: 1rem; }
.item-meta { font-size: 0.85rem; color: var(--gray); margin-top: 2px; }
.item-price { font-weight: 600; color: var(--dark); font-size: 1rem; }

/* View Details Action Bar */
.order-footer {
    padding: 15px 25px;
    background: #fff;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: flex-end;
}

.btn-view-details {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s;
}
.btn-view-details:hover {
    color: var(--primary-hover);
    transform: translateX(3px);
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: var(--white);
    border-radius: 12px;
    border: 1px dashed #cbd5e1;
}
.btn-shop {
    display: inline-block;
    background: var(--primary);
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
    margin-top: 20px;
    font-weight: 500;
    transition: background 0.2s;
}
.btn-shop:hover { background: var(--primary-hover); }
</style>

<div class="page-container">
    <h1 class="page-title">My Orders</h1>

    <?php
    /* ================= FETCH ORDERS ================= */
    $order_q = mysqli_query($conn,"
        SELECT * FROM orders 
        WHERE user_id='".intval($user_id)."'
        ORDER BY created_at DESC
    ");

    if ($order_q && mysqli_num_rows($order_q) > 0) {

        while ($order = mysqli_fetch_assoc($order_q)) {

            $order_id = $order['id'];
            $date = date("d M Y", strtotime($order['created_at']));
            $total = number_format($order['total_price'], 2);

            // LOGIC FIX: Convert Database Status (0/1) to Text
            $db_status = $order['status']; 
            $status_display = 'Unknown';
            $status_class = 'Unknown';

            // Check if status is numeric (0, 1) or string (Pending)
            if($db_status === '0' || $db_status === 0 || strtolower($db_status) == 'pending') {
                $status_display = 'Pending';
                $status_class = 'Pending';
            } elseif ($db_status === '1' || $db_status === 1 || strtolower($db_status) == 'completed' || strtolower($db_status) == 'delivered') {
                $status_display = 'Delivered';
                $status_class = 'Delivered';
            } elseif ($db_status === '2' || $db_status === 2 || strtolower($db_status) == 'cancelled') {
                $status_display = 'Cancelled';
                $status_class = 'Cancelled';
            } else {
                $status_display = ucfirst($db_status);
                $status_class = ucfirst($db_status);
            }
            ?>

            <div class="order-card">
                <div class="order-header">
                    <div class="header-info">
                        <div class="info-group">
                            <span class="label">Date Placed</span>
                            <span class="value"><?= $date; ?></span>
                        </div>
                        <div class="info-group">
                            <span class="label">Total Amount</span>
                            <span class="value">₹<?= $total; ?></span>
                        </div>
                        <div class="info-group">
                            <span class="label">Order ID</span>
                            <span class="value">ORD-<?= str_pad($order_id, 4, '0', STR_PAD_LEFT); ?></span>
                        </div>
                    </div>
                    <span class="badge <?= $status_class; ?>">
                        <?= $status_display; ?>
                    </span>
                </div>

                <div class="order-body">
                    <?php
                    $item_q = mysqli_query($conn,"
                        SELECT product_name, quantity, price
                        FROM order_items
                        WHERE order_id='".intval($order_id)."'
                    ");

                    if ($item_q && mysqli_num_rows($item_q) > 0) {
                        while ($item = mysqli_fetch_assoc($item_q)) {
                        ?>
                            <div class="item-row">
                                <div class="item-left">
                                    <div>
                                        <div class="item-name"><?= $item['product_name']; ?></div>
                                        <div class="item-meta">Qty: <?= $item['quantity']; ?></div>
                                    </div>
                                </div>
                                <div class="item-price">₹<?= number_format($item['price'], 2); ?></div>
                            </div>
                        <?php
                        }
                    } else {
                        echo "<div style='padding:20px;color:var(--gray);'>No items details found.</div>";
                    }
                    ?>
                </div>

                <div class="order-footer">
                    <a href="order-details.php?id=<?= $order_id; ?>" class="btn-view-details">
                        View Order Details &rarr;
                    </a>
                </div>
            </div>

        <?php
        }

    } else {
    ?>
    <div class="empty-state">
        <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="80" style="opacity:0.5;margin-bottom:15px;">
        <h2>No Orders Yet</h2>
        <p style="color:var(--gray);">Looks like you haven't placed any orders yet.</p>
        <a href="../products.php" class="btn-shop">Start Shopping</a>
    </div>
    <?php } ?>

</div>

<?php include("../includes/footer.php"); ?>