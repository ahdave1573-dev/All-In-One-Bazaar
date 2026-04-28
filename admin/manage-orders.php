<?php
require_once('includes/auth_check.php');
include("../config/db.php");

/* ======================
   FETCH ORDERS + USERS
====================== */
$query = "
SELECT o.*, u.full_name 
FROM orders o
JOIN users u ON o.user_id = u.id
ORDER BY o.created_at DESC
";
$result = mysqli_query($conn, $query);

/* ======================
   STATUS NORMALIZER
====================== */
function orderStatus($status){
    if ($status === '0' || strtolower($status) === 'pending') return 'Pending';
    if ($status === '1' || strtolower($status) === 'completed' || strtolower($status) === 'delivered') return 'Delivered';
    if (strtolower($status) === 'processing') return 'Processing';
    if (strtolower($status) === 'cancelled') return 'Cancelled';
    return 'Pending';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Orders | All In One Bazaar</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
*{box-sizing:border-box}
body{
    font-family:'Segoe UI',sans-serif;
    background:#f1f5f9;
    margin:0;
    display:flex;
    min-height:100vh;
}

/* SIDEBAR */
.sidebar{
    width:260px;
    background:#0f172a;
    color:#fff;
    position:fixed;
    height:100%;
}
.logo-section{
    padding:20px;
    font-size:24px;
    font-weight:bold;
    border-bottom:1px solid #1e293b;
}
.logo-section span{color:#3b82f6}
.menu{list-style:none;padding:0;margin:20px 0}
.menu li a{
    display:flex;
    align-items:center;
    gap:12px;
    padding:15px 25px;
    color:#94a3b8;
    text-decoration:none;
}
.menu li a:hover,.menu li a.active{
    background:#1e293b;
    color:#fff;
    border-left:4px solid #3b82f6;
}
.logout-link{
    padding:15px 25px;
    color:#ef4444;
    display:flex;
    gap:12px;
    text-decoration:none;
}

/* MAIN */
.main-content{
    margin-left:260px;
    padding:30px;
    width:calc(100% - 260px);
}
h2{margin:0 0 20px;color:#0f172a}

/* TABLE */
.table-wrapper{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 6px rgba(0,0,0,.08);
}
table{width:100%;border-collapse:collapse}
thead tr{background:#f8fafc;border-bottom:2px solid #e2e8f0}
th,td{padding:14px;text-align:left;font-size:14px}
th{text-transform:uppercase;color:#475569}
td{border-bottom:1px solid #e2e8f0;color:#334155}

/* BADGES */
.badge{
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    color:#fff;
}
.Pending{background:#f59e0b}
.Processing{background:#3b82f6}
.Delivered{background:#10b981}
.Cancelled{background:#ef4444}

/* ACTION */
.action-btn{
    background:#eff6ff;
    color:#3b82f6;
    padding:6px 14px;
    border-radius:6px;
    text-decoration:none;
    font-weight:600;
}
.action-btn:hover{background:#3b82f6;color:#fff}
</style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <h2>Manage Orders</h2>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if(mysqli_num_rows($result)>0): 
                while($row=mysqli_fetch_assoc($result)):
                $status = orderStatus($row['status']);
                $date   = $row['created_at'] ?? $row['order_date'];
            ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td>₹<?= number_format($row['total_price'],2) ?></td>
                    <td><?= strtoupper($row['payment_method']) ?></td>
                    <td><span class="badge <?= $status ?>"><?= $status ?></span></td>
                    <td><?= date("d M Y", strtotime($date)) ?></td>
                    <td>
                        <a href="order-details.php?id=<?= $row['id'] ?>" class="action-btn">
                            <i class="fa fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="7" style="text-align:center">No Orders Found</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
