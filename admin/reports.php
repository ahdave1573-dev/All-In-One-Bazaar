<?php
require_once('includes/auth_check.php');
include("../config/db.php");

/* ==========================
   DATE FILTER (SAFE)
========================== */
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';

$where = "WHERE 1";

if (!empty($from) && !empty($to)) {
    $from = mysqli_real_escape_string($conn, $from);
    $to   = mysqli_real_escape_string($conn, $to);
    $where .= " AND DATE(order_date) BETWEEN '$from' AND '$to'";
}

/* ==========================
   SUMMARY DATA
========================== */

// TOTAL SALES
$totalSales = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(total_price) AS total FROM orders $where"
    )
)['total'] ?? 0;

// TOTAL ORDERS
$totalOrders = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM orders $where"
    )
)['total'] ?? 0;

// COMPLETED SALES (FIXED LOGIC)
$completedWhere = $where . " AND status IN ('Completed', 'Delivered')";

$completedSales = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(total_price) AS total FROM orders $completedWhere"
    )
)['total'] ?? 0;

/* ==========================
   ORDERS LIST
========================== */
$ordersQuery = mysqli_query(
    $conn,
    "SELECT o.*, u.full_name
     FROM orders o
     JOIN users u ON o.user_id = u.id
     $where
     ORDER BY o.order_date DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sales Reports</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    font-family:'Segoe UI',sans-serif;
    background:#f1f5f9;
}
.main-content {
    padding: 30px;
}
h2{color:#0f172a}
.filters,.summary{
    background:#fff;
    padding:20px;
    border-radius:12px;
    margin-bottom:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}
.filters form{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
    align-items:center;
}
input,button{
    padding:10px;
    border-radius:6px;
    border:1px solid #d1d5db;
}
button{
    background:#2563eb;
    color:#fff;
    border:none;
    cursor:pointer;
}
.summary-box{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}
.card{
    flex:1;
    min-width:220px;
    background:#f8fafc;
    padding:20px;
    border-radius:12px;
    text-align:center;
}
.card h3{
    margin:0;
    color:#2563eb;
}
.table-wrapper{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #e5e7eb;
}
th{
    background:#0f172a;
    color:#fff;
}
.badge{
    padding:6px 12px;
    border-radius:20px;
    color:#fff;
    font-size:13px;
}
.Pending{ background:#f59e0b; }
.Processing{ background:#2563eb; }
.Completed{ background:#16a34a; }
.Delivered{ background:#16a34a; }
.Cancelled{ background:#dc2626; }
</style>
</head>

<body>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">

<h2>📊 Sales Reports</h2>

<!-- FILTER -->
<div class="filters">
<form method="GET">
    <label>From:</label>
    <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
    <label>To:</label>
    <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">
    <button type="submit">Generate Report</button>
</form>
</div>

<!-- SUMMARY -->
<div class="summary">
<div class="summary-box">
    <div class="card">
        <p>Total Sales</p>
        <h3>₹<?= number_format($totalSales,2) ?></h3>
    </div>
    <div class="card">
        <p>Total Orders</p>
        <h3><?= $totalOrders ?></h3>
    </div>
    <div class="card">
        <p>Delivered Sales</p>
        <h3>₹<?= number_format($completedSales,2) ?></h3>
    </div>
</div>
</div>

<!-- ORDERS TABLE -->
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
</tr>
</thead>
<tbody>
<?php
if ($ordersQuery && mysqli_num_rows($ordersQuery) > 0) {
    while ($row = mysqli_fetch_assoc($ordersQuery)) {
        ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td>₹<?= number_format($row['total_price'],2) ?></td>
            <td><?= htmlspecialchars($row['payment_method']) ?></td>
            <td>
                <span class="badge <?= $row['status'] ?>">
                    <?= $row['status'] ?>
                </span>
            </td>
            <td><?= date("d M Y", strtotime($row['order_date'])) ?></td>
        </tr>
        <?php
    }
} else {
    echo "<tr><td colspan='6'>No Data Found</td></tr>";
}
?>
</tbody>
</table>
</div>

</div> <!-- main-content end -->
</body>
</html>
