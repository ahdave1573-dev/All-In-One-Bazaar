<?php
session_start();

include('../config/constants.php');
include('../config/db.php');
include('../config/auth.php');

/* ================= ORDER ID CHECK ================= */
if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = intval($_GET['id']);
$user_id  = $_SESSION['user_id'];

/* ================= CANCEL ORDER LOGIC (FIXED 🔥) ================= */
if (isset($_POST['cancel_order'])) {

    $oid = intval($_POST['order_id']);

    $check = mysqli_query($conn,"
        SELECT id FROM orders 
        WHERE id='$oid'
        AND user_id='$user_id'
        AND (status='0' OR LOWER(status)='pending' OR status='Processing')
        LIMIT 1
    ");

    if ($check && mysqli_num_rows($check) == 1) {

        // 🔥 FIX: use STRING instead of number
        mysqli_query($conn,"
            UPDATE orders 
            SET status='Cancelled'
            WHERE id='$oid'
        ");

        $_SESSION['order_msg'] = "Order cancelled successfully.";
        header("Location: order-details.php?id=".$oid);
        exit();
    }
}

/* ================= FETCH ORDER ================= */
$order_run = mysqli_query($conn,"
    SELECT * FROM orders 
    WHERE id='$order_id' AND user_id='$user_id'
    LIMIT 1
");

if (!$order_run || mysqli_num_rows($order_run) == 0) {
    die("<h3 style='padding:20px'>Order not found or access denied.</h3>");
}

$order = mysqli_fetch_assoc($order_run);

/* ================= STATUS HANDLING (FIXED 🔥) ================= */
$status_raw = strtolower(trim((string)$order['status']));

$is_pending   = in_array($status_raw, ['0','pending','processing']);
$is_completed = in_array($status_raw, ['1','completed','delivered']);
$is_cancelled = in_array($status_raw, ['2','cancelled']);

/* ================= FETCH USER ================= */
$user_run = mysqli_query($conn,"
    SELECT full_name, email, phone, address 
    FROM users 
    WHERE id='$user_id'
    LIMIT 1
");
$user = mysqli_fetch_assoc($user_run);

/* ================= HEADER ================= */
include('../includes/header.php');
?>

<style>
:root{--primary:#2563eb;}
.dashboard-wrapper{display:flex;min-height:80vh;background:#f8fafc}
.sidebar-area{width:260px;background:#fff;border-right:1px solid #e2e8f0}
.main-content{flex:1;padding:30px}
.details-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden}
.details-header{background:var(--primary);color:#fff;padding:15px 20px;display:flex;justify-content:space-between;align-items:center}
.back-btn{background:rgba(255,255,255,.2);color:#fff;padding:6px 16px;border-radius:6px;text-decoration:none}
.info-box{background:#f8fafc;padding:15px;border-radius:8px;border:1px solid #e2e8f0}
.info-title{font-weight:700;margin-bottom:10px;border-bottom:1px solid #e2e8f0;padding-bottom:6px}
.product-table{width:100%;border-collapse:collapse;margin-top:20px}
.product-table th{background:#f1f5f9;padding:12px;text-align:left}
.product-table td{padding:12px;border-bottom:1px solid #f1f5f9}
.grand-total{text-align:right;padding:20px;font-size:1.2rem;font-weight:700;color:var(--primary)}
.status{padding:5px 12px;border-radius:20px;font-size:.85rem}
.pending{background:#fff7ed;color:#ea580c}
.completed{background:#f0fdf4;color:#16a34a}
.cancelled{background:#fef2f2;color:#dc2626}
</style>

<div class="dashboard-wrapper">

<div class="sidebar-area">
    <?php include('../includes/sidebar.php'); ?>
</div>

<div class="main-content">
<div class="details-card">

<div class="details-header">
    <h4>Order Details</h4>
    <a href="orders.php" class="back-btn">Back</a>
</div>

<div style="padding:25px">

<?php
if (isset($_SESSION['order_msg'])) {
    echo "<p style='color:green;font-weight:600'>".$_SESSION['order_msg']."</p>";
    unset($_SESSION['order_msg']);
}
?>

<div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:25px">

<!-- ADDRESS -->
<div style="flex:1">
<div class="info-box">
<div class="info-title">Delivery Address</div>
<p><strong><?= htmlspecialchars($user['full_name']); ?></strong></p>
<p><?= htmlspecialchars($user['email']); ?></p>
<p><?= htmlspecialchars($user['phone']); ?></p>
<p><?= nl2br(htmlspecialchars($user['address'])); ?></p>
</div>
</div>

<!-- ORDER INFO -->
<div style="flex:1">
<div class="info-box">
<div class="info-title">Order Info</div>

<p><b>Order ID:</b> ORD-<?= str_pad($order['id'],4,'0',STR_PAD_LEFT); ?></p>
<p><b>Tracking No:</b> <?= htmlspecialchars($order['tracking_no']); ?></p>
<p><b>Order Date:</b> <?= date('d M Y', strtotime($order['created_at'])); ?></p>
<p><b>Payment Mode:</b> <?= htmlspecialchars($order['payment_method']); ?></p>

<p style="margin-top:10px">
<b>Status:</b>
<?php if ($is_pending): ?>
<span class="status pending">Pending</span>
<?php elseif ($is_completed): ?>
<span class="status completed">Delivered</span>
<?php elseif ($is_cancelled): ?>
<span class="status cancelled">Cancelled</span>
<?php endif; ?>
</p>

<?php if ($is_pending): ?>
<form method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');" style="margin-top:10px">
<input type="hidden" name="order_id" value="<?= $order['id']; ?>">
<button type="submit" name="cancel_order"
style="background:#dc2626;color:#fff;border:none;padding:10px 20px;border-radius:6px;font-weight:600;cursor:pointer;">
Cancel Order
</button>
</form>
<?php endif; ?>

</div>
</div>

</div>

<h4>Ordered Items</h4>

<table class="product-table">
<thead>
<tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th></tr>
</thead>
<tbody>

<?php
$item_run = mysqli_query($conn,"
SELECT product_name, quantity, price 
FROM order_items 
WHERE order_id='$order_id'
");

while ($item = mysqli_fetch_assoc($item_run)) {
$total = $item['quantity'] * $item['price'];
?>
<tr>
<td><?= htmlspecialchars($item['product_name']); ?></td>
<td>₹<?= number_format($item['price']); ?></td>
<td><?= $item['quantity']; ?></td>
<td><strong>₹<?= number_format($total); ?></strong></td>
</tr>
<?php } ?>
</tbody>
</table>

<div class="grand-total">
Grand Total: ₹<?= number_format($order['total_price']); ?>
</div>

</div>
</div>
</div>
</div>

<?php include('../includes/footer.php'); ?>
