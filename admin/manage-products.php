<?php
require_once('includes/auth_check.php');
include("../config/db.php"); // DB connection file

// Fetch products with category name
$query = "
SELECT p.*, c.name AS category_name 
FROM products p
JOIN categories c ON p.category_id = c.id
ORDER BY p.created_at DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Products | All In One Bazaar</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ===== EXISTING CSS (NO CHANGE) ===== */
* { box-sizing: border-box; }
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f1f5f9;
    margin: 0;
    padding: 0;
    display: flex;
    min-height: 100vh;
}
.sidebar {
    width: 260px;
    background: #0f172a;
    color: #fff;
    display: flex;
    flex-direction: column;
    position: fixed;
    height: 100%;
    left: 0;
    top: 0;
}
.logo-section {
    padding: 20px;
    font-size: 24px;
    font-weight: bold;
    border-bottom: 1px solid #1e293b;
}
.logo-section span { color: #3b82f6; }
.menu { list-style: none; padding: 0; margin: 20px 0; flex-grow: 1; }
.menu li a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 25px;
    color: #94a3b8;
    text-decoration: none;
}
.menu li a.active, .menu li a:hover {
    background: #1e293b;
    color: #fff;
    border-left: 4px solid #3b82f6;
}
.logout-link {
    padding: 15px 25px;
    color: #ef4444 !important;
    text-decoration: none;
}
.main-content {
    margin-left: 260px;
    width: calc(100% - 260px);
    padding: 30px;
}
.header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}
.add-btn {
    background: #16a34a;
    color: #fff;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
}
.table-wrapper {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
}
table { width: 100%; border-collapse: collapse; }
thead tr { background: #f8fafc; }
th, td {
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid #e2e8f0;
}
.product-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
}

/* ===== EXISTING BADGES ===== */
.badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; }
.badge-visible { background: #dcfce7; color: #166534; }
.badge-hidden { background: #fee2e2; color: #991b1b; }
.badge-yes { background: #dbeafe; color: #1e40af; }
.badge-no { background: #f1f5f9; color: #64748b; }

/* ===== 🔥 NEW STOCK BADGES (ONLY ADD) ===== */
.stock-in { background:#dcfce7; color:#166534; }
.stock-low { background:#ffedd5; color:#9a3412; }
.stock-out { background:#fee2e2; color:#991b1b; }
</style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

<div class="header-bar">
    <h2>📦 Manage Products</h2>
    <a href="add-product.php" class="add-btn">+ Add Product</a>
</div>

<div class="table-wrapper">
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Image</th>
    <th style="text-align:left;">Name</th>
    <th>Category</th>
    <th>Price</th>
    <th>Qty</th>

    <!-- 🔥 NEW COLUMN -->
    <th>Stock</th>

    <th>Status</th>
    <th>Trending</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php
if(mysqli_num_rows($result)>0){
while($row=mysqli_fetch_assoc($result)){
?>
<tr>
    <td><?= $row['id']; ?></td>
    <td><img src="../uploads/products/<?= $row['image']; ?>" class="product-img"></td>
    <td style="text-align:left"><?= $row['name']; ?></td>
    <td><?= $row['category_name']; ?></td>
    <td>
        <del style="color:#94a3b8">₹<?= $row['original_price']; ?></del><br>
        <strong>₹<?= $row['selling_price']; ?></strong>
    </td>
    <td><?= $row['qty']; ?></td>

    <!-- 🔥 STOCK STATUS LOGIC -->
    <td>
        <?php
        if($row['qty'] == 0){
            echo "<span class='badge stock-out'>Out of Stock</span>";
        } elseif($row['qty'] <= 5){
            echo "<span class='badge stock-low'>Low Stock</span>";
        } else {
            echo "<span class='badge stock-in'>In Stock</span>";
        }
        ?>
    </td>

    <td>
        <?= $row['status']==0 
            ? "<span class='badge badge-visible'>Visible</span>"
            : "<span class='badge badge-hidden'>Hidden</span>" ?>
    </td>
    <td>
        <?= $row['trending']==1
            ? "<span class='badge badge-yes'>Yes</span>"
            : "<span class='badge badge-no'>No</span>" ?>
    </td>
    <td>
        <a href="edit-product.php?id=<?= $row['id']; ?>" class="action-btn edit"><i class="fas fa-edit"></i></a>
        <a href="delete-product.php?id=<?= $row['id']; ?>" class="action-btn delete"
           onclick="return confirm('Delete product?')"><i class="fas fa-trash"></i></a>
    </td>
</tr>
<?php
}} else {
    echo "<tr><td colspan='10'>No Products Found</td></tr>";
}
?>
</tbody>
</table>
</div>

</div>
</body>
</html>