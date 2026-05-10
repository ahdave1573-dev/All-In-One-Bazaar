<?php
require_once('includes/auth_check.php');
include("../config/db.php");

/* ================= ORDER ID CHECK ================= */
if(!isset($_GET['id'])){
    header("Location: manage-orders.php");
    exit();
}
$order_id = (int)$_GET['id'];

/* ================= UPDATE STATUS + STOCK REDUCE ================= */
if(isset($_POST['update_status'])){

    $new_status = $_POST['status'];

    // OLD STATUS FETCH
    $oldQ = mysqli_query($conn,"SELECT status FROM orders WHERE id='$order_id'");
    $oldRow = mysqli_fetch_assoc($oldQ);
    $old_status = $oldRow['status'];

    /* 🔒 RULES */
    // ❌ Cancelled → nothing allowed
    if($old_status === 'Cancelled'){
        $_SESSION['success'] = "❌ Cancelled order status cannot be changed.";
        header("Location: order-details.php?id=".$order_id);
        exit();
    }

    // ❌ Delivered → Cancelled BLOCK
    if(($old_status === 'Completed' || $old_status === 'Delivered') && $new_status === 'Cancelled'){
        $_SESSION['success'] = "❌ Delivered order cannot be cancelled.";
        header("Location: order-details.php?id=".$order_id);
        exit();
    }

    // UPDATE STATUS
    mysqli_query($conn,"UPDATE orders SET status='$new_status' WHERE id='$order_id'");

    /* 🔥 STOCK REDUCE ONLY ONCE */
    if($old_status !== 'Completed' && $old_status !== 'Delivered' && $new_status === 'Delivered'){

        $items = mysqli_query($conn,"
            SELECT product_id, quantity 
            FROM order_items 
            WHERE order_id='$order_id'
        ");

        while($row = mysqli_fetch_assoc($items)){
            $pid = $row['product_id'];
            $qty = $row['quantity'];

            mysqli_query($conn,"
                UPDATE products 
                SET qty = GREATEST(qty - $qty, 0)
                WHERE id='$pid'
            ");
        }
    }

    $_SESSION['success'] = "✅ Order status updated successfully.";
    header("Location: order-details.php?id=".$order_id);
    exit();
}

/* ================= FETCH ORDER ================= */
$orderQ = mysqli_query(
    $conn,
    "SELECT o.*, u.full_name, u.email, u.phone
     FROM orders o
     JOIN users u ON o.user_id = u.id
     WHERE o.id='$order_id'"
);
if(mysqli_num_rows($orderQ)==0){
    header("Location: manage-orders.php");
    exit();
}
$order = mysqli_fetch_assoc($orderQ);

/* ================= FETCH ITEMS ================= */
$itemQ = mysqli_query($conn,"SELECT * FROM order_items WHERE order_id='$order_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Details #<?= $order_id ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{font-family:Inter,Arial;background:#f8fafc;padding:40px}
.container{max-width:900px;margin:auto}
.card{background:#fff;border-radius:10px;padding:25px;margin-bottom:25px;border-top:3px solid #4f46e5;box-shadow:0 4px 8px rgba(0,0,0,.05)}
h2,h3{margin:0 0 15px;color:#4f46e5}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px}
.label{font-size:12px;color:#64748b;font-weight:600}
.value{font-weight:600}
.badge{padding:6px 14px;border-radius:20px;font-size:12px;font-weight:700}
.Pending{background:#fff7ed;color:#9a3412}
.Processing{background:#eff6ff;color:#1e40af}
.Delivered{background:#f0fdf4;color:#166534}
.Cancelled{background:#fef2f2;color:#991b1b}
table{width:100%;border-collapse:collapse;margin-top:10px}
th,td{padding:12px;border-bottom:1px solid #e5e7eb;text-align:left}
th{background:#eef2ff;font-size:12px;color:#4f46e5}
.total{text-align:right;font-weight:700;margin-top:15px}
.status-box{background:#eef2ff;padding:20px;border-radius:10px;display:flex;gap:20px;align-items:center}
select{padding:10px;border-radius:6px;border:1px solid #c7d2fe}
button{background:#4f46e5;color:#fff;border:none;padding:10px 20px;border-radius:6px;font-weight:600;cursor:pointer}
button:disabled{background:#94a3b8;cursor:not-allowed}
.success{background:#d1fae5;color:#065f46;padding:12px;border-radius:6px;margin-bottom:15px}
.back{display:inline-block;margin-bottom:15px;text-decoration:none;color:#4f46e5;font-weight:600}
</style>
</head>

<body>
<div class="container">

<a href="manage-orders.php" class="back">← Back to Orders</a>

<?php
if(isset($_SESSION['success'])){
    echo "<div class='success'>".$_SESSION['success']."</div>";
    unset($_SESSION['success']);
}
?>

<div class="card">
<h2>🧾 Order <?= $order['id'] ?> Details</h2>
<div class="grid">
    <div><div class="label">Customer Name</div><div class="value"><?= $order['full_name'] ?></div></div>
    <div><div class="label">Email</div><div class="value"><?= $order['email'] ?></div></div>
    <div><div class="label">Phone</div><div class="value"><?= $order['phone'] ?></div></div>
    <div><div class="label">Order Date</div><div class="value"><?= date("d M Y, h:i A",strtotime($order['order_date'])) ?></div></div>
    <div><div class="label">Payment</div><div class="value"><?= $order['payment_method'] ?></div></div>
    <div><div class="label">Status</div><span class="badge <?= $order['status'] ?>"><?= strtoupper($order['status']) ?></span></div>
</div>
<br>
<div class="label">Delivery Address</div>
<div class="value"><?= $order['address'] ?></div>
</div>

<div class="card">
<h3>📦 Ordered Items</h3>
<table>
<thead>
<tr><th>ID</th><th>Product Name</th><th>Qty</th><th>Price</th><th>Total</th></tr>
</thead>
<tbody>
<?php $i=1;$grand=0; while($item=mysqli_fetch_assoc($itemQ)): 
$line=$item['quantity']*$item['price']; $grand+=$line; ?>
<tr>
<td><?= str_pad($i++,2,'0',STR_PAD_LEFT) ?></td>
<td><?= $item['product_name'] ?></td>
<td><?= $item['quantity'] ?></td>
<td>₹<?= number_format($item['price'],2) ?></td>
<td>₹<?= number_format($line,2) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<div class="total">Grand Total Amount: ₹<?= number_format($grand,2) ?></div>
</div>


<div class="card">
<h3>⚙ Update Order Status</h3>
<form method="POST" class="status-box">

<select name="status"
<?= ($order['status']=='Cancelled' || $order['status']=='Completed' || $order['status']=='Delivered') ? 'disabled' : '' ?>>

    <option <?= $order['status']=="Pending"?'selected':'' ?> value="Pending">Pending</option>
    <option <?= $order['status']=="Processing"?'selected':'' ?> value="Processing">Processing</option>
    <option <?= ($order['status']=="Completed" || $order['status']=="Delivered")?'selected':'' ?> value="Delivered">Delivered</option>

    <?php if($order['status']!="Completed" && $order['status']!="Delivered"): ?>
        <option <?= $order['status']=="Cancelled"?'selected':'' ?> value="Cancelled">Cancelled</option>
    <?php endif; ?>

</select>

<button name="update_status"
<?= ($order['status']=='Cancelled' || $order['status']=='Completed' || $order['status']=='Delivered') ? 'disabled' : '' ?>>
    Update Status Now
</button>

</form>
</div>

</div>
</body>
</html>
