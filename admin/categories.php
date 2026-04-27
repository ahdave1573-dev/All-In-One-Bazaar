<?php
require_once('includes/auth_check.php');
include("config/db.php");

/* ================= CATEGORY CHECK ================= */
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$cat_id = (int) $_GET['id'];

/* ================= FETCH CATEGORY ================= */
$cat_query = mysqli_query(
    $conn,
    "SELECT * FROM categories WHERE id='$cat_id' AND status='0' LIMIT 1"
);

if (mysqli_num_rows($cat_query) == 0) {
    header("Location: index.php");
    exit();
}

$category = mysqli_fetch_assoc($cat_query);

/* ================= INCLUDE HEADER ================= */
include('includes/header.php');
?>

<style>
body { background-color:#f8fafc; }

/* HEADER */
.category-header{
    background:#fff;
    padding:40px 0;
    text-align:center;
    margin-bottom:30px;
    box-shadow:0 4px 6px rgba(0,0,0,0.05);
}
.category-title{
    font-size:2rem;
    font-weight:700;
    color:#0f172a;
}
.category-breadcrumb{
    font-size:0.9rem;
    color:#64748b;
}
.category-breadcrumb a{
    color:#2563eb;
    text-decoration:none;
}

/* GRID */
.product-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(240px,1fr));
    gap:25px;
    padding-bottom:50px;
}

/* CARD */
.product-card{
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:12px;
    overflow:hidden;
    display:flex;
    flex-direction:column;
    transition:0.3s;
}
.product-card:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 20px rgba(0,0,0,0.08);
    border-color:#2563eb;
}

/* IMAGE */
.prod-img-box{
    height:200px;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:15px;
    border-bottom:1px solid #f1f5f9;
}
.prod-img{
    max-width:100%;
    max-height:100%;
    object-fit:contain;
}

/* BODY */
.prod-body{
    padding:20px;
    display:flex;
    flex-direction:column;
    flex:1;
}
.prod-brand{
    font-size:0.8rem;
    color:#94a3b8;
    text-transform:uppercase;
}
.prod-title{
    font-size:1rem;
    font-weight:600;
    margin:8px 0 12px;
    color:#1e293b;
    line-height:1.4;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
}

/* PRICE */
.price-row{
    margin-top:auto;
    margin-bottom:15px;
}
.selling-price{
    font-size:1.1rem;
    font-weight:700;
    color:#0f172a;
}
.original-price{
    font-size:0.9rem;
    text-decoration:line-through;
    color:#94a3b8;
    margin-left:8px;
}

/* BUTTON */
.btn-view{
    display:block;
    text-align:center;
    padding:10px;
    background:#2563eb;
    color:#fff;
    border-radius:8px;
    font-weight:500;
    text-decoration:none;
}
.btn-view:hover{
    background:#1d4ed8;
    color:#fff;
}

/* EMPTY */
.empty-state{
    grid-column:1/-1;
    text-align:center;
    padding:60px 20px;
}
</style>

<!-- ================= CATEGORY HEADER ================= -->
<div class="category-header">
    <div class="container">
        <h1 class="category-title"><?= htmlspecialchars($category['name']); ?></h1>
        <div class="category-breadcrumb">
            <a href="index.php">Home</a> /
            <a href="categories.php">Collections</a> /
            <span><?= htmlspecialchars($category['name']); ?></span>
        </div>
    </div>
</div>

<!-- ================= PRODUCTS ================= -->
<div class="container">
    <div class="product-grid">

<?php
$product_query = mysqli_query(
    $conn,
    "SELECT * FROM products WHERE category_id='$cat_id' AND status='0'"
);

if (mysqli_num_rows($product_query) > 0) {
    while ($item = mysqli_fetch_assoc($product_query)) {
?>
        <div class="product-card">

            <div class="prod-img-box">
                <!-- ✅ IMAGE PATH FIX HERE -->
                <img 
                    src="assets/images/<?= htmlspecialchars($item['image']); ?>"
                    alt="<?= htmlspecialchars($item['name']); ?>"
                    class="prod-img"
                    onerror="this.onerror=null;this.src='assets/images/no-image.png';"
                >
            </div>

            <div class="prod-body">
                <div class="prod-brand"><?= htmlspecialchars($category['name']); ?></div>

                <div class="prod-title">
                    <?= htmlspecialchars($item['name']); ?>
                </div>

                <div class="price-row">
                    <span class="selling-price">₹<?= number_format($item['selling_price']); ?></span>
                    <span class="original-price">₹<?= number_format($item['original_price']); ?></span>
                </div>

                <a href="product-details.php?product=<?= $item['slug']; ?>" class="btn-view">
                    View Details
                </a>
            </div>

        </div>
<?php
    }
} else {
?>
        <div class="empty-state">
            <img src="assets/images/no-image.png" width="120" style="opacity:0.5;margin-bottom:15px;">
            <h4 style="color:#64748b;">No Products Found</h4>
            <p style="color:#94a3b8;">We couldn't find any products in this category.</p>
            <a href="categories.php" style="color:#2563eb;font-weight:600;">Browse other categories</a>
        </div>
<?php } ?>

    </div>
</div>

<?php include('includes/footer.php'); ?>
