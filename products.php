<?php
session_start();
include('config/constants.php');
include('includes/functions.php');
include('includes/header.php');

include(file_exists("config/db.php") ? "config/db.php" : "db.php");

/* ================= FILTER LOGIC ================= */
$conditions = ["p.status=0"];
$page_title = "All Products";

if(isset($_GET['cat']) && $_GET['cat']!=""){
    $cat = intval($_GET['cat']);
    $conditions[] = "p.category_id='$cat'";
    // Get category name for title
    $cn = mysqli_query($conn, "SELECT name FROM categories WHERE id='$cat'");
    if($cn && $cr = mysqli_fetch_assoc($cn)){
        $page_title = htmlspecialchars($cr['name']);
    }
}

if(isset($_GET['search']) && $_GET['search']!=""){
    $search = mysqli_real_escape_string($conn,$_GET['search']);
    $conditions[] = "(p.name LIKE '%$search%' OR p.description LIKE '%$search%')";
    $page_title = 'Search: "' . htmlspecialchars($search) . '"';
}

// Price range filter
if(isset($_GET['min_price']) && $_GET['min_price'] !== ""){
    $min = intval($_GET['min_price']);
    $conditions[] = "p.selling_price >= $min";
}
if(isset($_GET['max_price']) && $_GET['max_price'] !== ""){
    $max = intval($_GET['max_price']);
    $conditions[] = "p.selling_price <= $max";
}

// Sort
$sort = $_GET['sort'] ?? 'newest';
$orderBy = "p.id DESC";
switch($sort){
    case 'price_low': $orderBy = "p.selling_price ASC"; break;
    case 'price_high': $orderBy = "p.selling_price DESC"; break;
    case 'name_az': $orderBy = "p.name ASC"; break;
    case 'name_za': $orderBy = "p.name DESC"; break;
    case 'popular': $orderBy = "p.trending DESC, p.id DESC"; break;
    default: $orderBy = "p.id DESC";
}

$whereSQL = "WHERE ".implode(" AND ",$conditions);
$result = mysqli_query($conn,"SELECT p.*, c.name as category_name 
                              FROM products p 
                              LEFT JOIN categories c ON p.category_id = c.id 
                              $whereSQL ORDER BY $orderBy");
$total_products = $result ? mysqli_num_rows($result) : 0;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

:root{
    --primary:#2563eb;
    --primary-dark:#1e40af;
    --dark:#1e293b;
    --light:#f8fafc;
    --gray:#94a3b8;
    --red:#ef4444;
    --green:#16a34a;
}

body{font-family:'Poppins',sans-serif;background:var(--light);margin:0}

.shop-container{
    max-width:1300px;
    margin:0 auto;
    padding:30px 20px;
    display:flex;
    gap:25px;
}

/* ===== SIDEBAR ===== */
.sidebar{width:260px;flex-shrink:0}
.widget{
    background:#fff;
    border-radius:14px;
    padding:20px;
    margin-bottom:15px;
    box-shadow:0 4px 15px rgba(0,0,0,.03);
    border: 1px solid #f0f0f0;
}
.widget h3{margin:0 0 12px;font-size:1rem;font-weight:600;color:var(--dark)}

/* SEARCH */
.search-box{display:flex;border:1px solid #e2e8f0;border-radius:8px;overflow:hidden}
.search-box input{border:none;padding:10px;width:100%;outline:none;font-size:0.9rem}
.search-box button{background:var(--primary);color:#fff;border:none;padding:0 14px;cursor:pointer}

/* CATEGORY */
.cat-list{list-style:none;padding:0;margin:0}
.cat-list li{margin-bottom:4px}
.cat-list a{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:9px 12px;
    border-radius:8px;
    color:#475569;
    text-decoration:none;
    font-size:0.9rem;
    transition:0.2s;
}
.cat-list a.active,
.cat-list a:hover{
    background:#eff6ff;
    color:var(--primary);
    font-weight:600;
}
.cat-count-badge {
    background: #f1f5f9;
    color: var(--gray);
    font-size: 0.75rem;
    padding: 2px 8px;
    border-radius: 10px;
    font-weight: 500;
}
.cat-list a.active .cat-count-badge,
.cat-list a:hover .cat-count-badge {
    background: #dbeafe;
    color: var(--primary);
}

/* PRICE FILTER */
.price-inputs {
    display: flex; gap: 8px; align-items: center;
    margin-bottom: 10px;
}
.price-inputs input {
    width: 100%; padding: 8px 10px;
    border: 1px solid #e2e8f0; border-radius: 6px;
    font-size: 0.85rem; outline: none;
}
.price-inputs input:focus { border-color: var(--primary); }
.price-filter-btn {
    width: 100%;
    background: var(--primary); color: #fff;
    border: none; padding: 8px;
    border-radius: 8px; cursor: pointer;
    font-size: 0.85rem; font-weight: 600;
    transition: 0.3s;
}
.price-filter-btn:hover { background: var(--primary-dark); }

/* ===== SHOP CONTENT ===== */
.shop-content{flex:1;min-width:0}

.shop-top-bar {
    display: flex; justify-content: space-between;
    align-items: center; margin-bottom: 20px;
    flex-wrap: wrap; gap: 12px;
}
.shop-top-bar h2 { font-size: 1.4rem; font-weight: 700; margin: 0; }
.product-count { color: var(--gray); font-size: 0.85rem; margin-top: 2px; }

.sort-select {
    padding: 8px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.85rem;
    color: var(--dark);
    outline: none;
    background: #fff;
    cursor: pointer;
}
.sort-select:focus { border-color: var(--primary); }

.pro-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(230px,1fr));
    gap:22px;
}

.pro-card{
    background:#fff;
    border-radius:16px;
    border:1px solid #f0f0f0;
    overflow:hidden;
    transition:.3s;
}
.pro-card:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 25px rgba(0,0,0,.06);
}

.pro-img{
    height:220px;
    padding:20px;
    background:#f8fafc;
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
}
.pro-img img{
    max-width:100%;
    max-height:100%;
    object-fit:contain;
    pointer-events:none;
}

/* Discount Badge */
.deal-badge {
    position: absolute; top: 12px; left: 12px;
    background: var(--red); color: #fff;
    padding: 4px 10px; border-radius: 6px;
    font-size: 0.7rem; font-weight: 700;
}

/* ❤️ LIKE */
.like-btn{
    position:absolute;
    top:12px;
    right:12px;
    width:36px;
    height:36px;
    border-radius:50%;
    background:#fff;
    border:1px solid #e2e8f0;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    z-index:10;
    transition:0.3s;
}
.like-btn i{color:var(--gray);font-size:1rem}
.like-btn.liked{background:#fee2e2;border-color:#fca5a5}
.like-btn.liked i{color:var(--red)}

/* 👁️ VIEW */
.view-btn{
    position:absolute;
    bottom:12px;
    right:12px;
    width:38px;
    height:38px;
    border-radius:50%;
    background:var(--primary);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    transform:scale(0);
    transition:.3s;
    border: 2px solid #fff;
}
.pro-card:hover .view-btn{transform:scale(1)}

/* ===== DETAILS ===== */
.pro-details{padding:16px}

/* Category label */
.pro-cat-label {
    font-size: 0.72rem;
    color: var(--primary);
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

/* STOCK LABEL */
.stock{
    font-size:0.72rem;
    font-weight:600;
    display:inline-block;
    margin-bottom:5px;
    padding:3px 10px;
    border-radius:20px;
}
.in-stock{background:#dcfce7;color:var(--green)}
.out-stock{background:#fee2e2;color:var(--red)}

.pro-details h3{
    font-size:0.95rem;
    margin:4px 0 8px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
    font-weight:600;
}

.pro-price-box { display: flex; align-items: baseline; gap: 6px; flex-wrap: wrap; }
.price{color:var(--dark);font-weight:700;font-size:1.05rem}
.price-old { color: #94a3b8; font-size: 0.8rem; text-decoration: line-through; }
.price-off { color: var(--green); font-size: 0.72rem; font-weight: 700; }

/* No Products */
.no-products {
    text-align: center; padding: 60px 20px;
    background: #fff; border-radius: 16px;
    border: 1px dashed #e2e8f0;
}
.no-products h3 { margin-bottom: 10px; }
.no-products a { color: var(--primary); font-weight: 600; }

/* Action Buttons Card */
.pro-actions {
    display: flex;
    gap: 8px;
    margin-top: 15px;
}
.btn-cart, .btn-buy {
    flex: 1;
    padding: 10px 5px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    transition: 0.3s;
    cursor: pointer;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.btn-cart {
    background: #eff6ff;
    color: var(--primary);
    border: 1px solid var(--primary);
}
.btn-cart:hover {
    background: var(--primary);
    color: #fff;
}
.btn-buy {
    background: #f97316;
    color: #fff;
}
.btn-buy:hover {
    background: #ea580c;
    transform: translateY(-2px);
}
.btn-disabled {
    background: #f1f5f9;
    color: #94a3b8;
    border: 1px solid #e2e8f0;
    cursor: not-allowed;
    pointer-events: none;
}

/* Mobile */
@media (max-width: 768px) {
    .shop-container { flex-direction: column; }
    .sidebar { width: 100%; }
}
</style>

<div class="shop-container">

<!-- ===== LEFT SIDEBAR ===== -->
<aside class="sidebar">

<div class="widget">
<h3><i class="fas fa-search"></i> Search</h3>
<form class="search-box" method="GET">
    <?php if(isset($_GET['cat'])): ?>
        <input type="hidden" name="cat" value="<?= $_GET['cat'] ?>">
    <?php endif; ?>
    <input type="text" name="search" placeholder="Search products..." value="<?= $_GET['search'] ?? '' ?>">
    <button><i class="fas fa-search"></i></button>
</form>
</div>

<div class="widget">
<h3><i class="fas fa-list"></i> Categories</h3>
<ul class="cat-list">
    <li>
        <?php
        $all_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM products WHERE status=0"))['c'];
        ?>
        <a href="products.php" class="<?= !isset($_GET['cat'])?'active':'' ?>">
            All Products <span class="cat-count-badge"><?= $all_count ?></span>
        </a>
    </li>
    <?php
    $cats=mysqli_query($conn,"SELECT c.id, c.name, COUNT(p.id) as cnt 
                              FROM categories c 
                              LEFT JOIN products p ON c.id=p.category_id AND p.status=0
                              WHERE c.status=0 
                              GROUP BY c.id 
                              ORDER BY c.name");
    while($c=mysqli_fetch_assoc($cats)):
    ?>
    <li>
        <a href="products.php?cat=<?= $c['id'] ?>"
           class="<?= (isset($_GET['cat']) && $_GET['cat']==$c['id'])?'active':'' ?>">
            <?= htmlspecialchars($c['name']) ?> 
            <span class="cat-count-badge"><?= $c['cnt'] ?></span>
        </a>
    </li>
    <?php endwhile; ?>
</ul>
</div>

<div class="widget">
<h3><i class="fas fa-indian-rupee-sign"></i> Price Range</h3>
<form method="GET">
    <?php if(isset($_GET['cat'])): ?>
        <input type="hidden" name="cat" value="<?= $_GET['cat'] ?>">
    <?php endif; ?>
    <?php if(isset($_GET['search'])): ?>
        <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
    <?php endif; ?>
    <?php if(isset($_GET['sort'])): ?>
        <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort']) ?>">
    <?php endif; ?>
    <div class="price-inputs">
        <input type="number" name="min_price" placeholder="₹ Min" value="<?= $_GET['min_price'] ?? '' ?>">
        <span>-</span>
        <input type="number" name="max_price" placeholder="₹ Max" value="<?= $_GET['max_price'] ?? '' ?>">
    </div>
    <button class="price-filter-btn" type="submit"><i class="fas fa-filter"></i> Apply</button>
</form>
</div>

</aside>

<!-- ===== PRODUCTS ===== -->
<main class="shop-content">

<div class="shop-top-bar">
    <div>
        <h2><?= $page_title ?></h2>
        <div class="product-count">Showing <?= $total_products ?> products</div>
    </div>
    <form method="GET" id="sortForm">
        <?php if(isset($_GET['cat'])): ?>
            <input type="hidden" name="cat" value="<?= $_GET['cat'] ?>">
        <?php endif; ?>
        <?php if(isset($_GET['search'])): ?>
            <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
        <?php endif; ?>
        <select name="sort" class="sort-select" onchange="document.getElementById('sortForm').submit()">
            <option value="newest" <?= $sort=='newest'?'selected':'' ?>>Newest First</option>
            <option value="price_low" <?= $sort=='price_low'?'selected':'' ?>>Price: Low to High</option>
            <option value="price_high" <?= $sort=='price_high'?'selected':'' ?>>Price: High to Low</option>
            <option value="name_az" <?= $sort=='name_az'?'selected':'' ?>>Name: A to Z</option>
            <option value="name_za" <?= $sort=='name_za'?'selected':'' ?>>Name: Z to A</option>
            <option value="popular" <?= $sort=='popular'?'selected':'' ?>>Most Popular</option>
        </select>
    </form>
</div>

<?php if($total_products > 0): ?>
<div class="pro-grid">
<?php while($row=mysqli_fetch_assoc($result)):

$img = (!empty($row['image']) && file_exists("assets/images/".$row['image']))
        ? "assets/images/".$row['image']
        : ((!empty($row['image']) && file_exists("uploads/products/".$row['image']))
            ? "uploads/products/".$row['image']
            : "https://placehold.co/300x300?text=Product");

/* STOCK CHECK */
$inStock = ($row['qty'] > 0);

/* DISCOUNT */
$discount = 0;
if(!empty($row['original_price']) && $row['original_price'] > $row['selling_price']){
    $discount = round((($row['original_price'] - $row['selling_price']) / $row['original_price']) * 100);
}

/* WISHLIST */
$liked=false;
if(isset($_SESSION['user_id'])){
    $uid=$_SESSION['user_id'];
    $pid=$row['id'];
    $chk=mysqli_query($conn,"SELECT id FROM wishlist WHERE user_id='$uid' AND product_id='$pid'");
    if(mysqli_num_rows($chk)>0) $liked=true;
}
?>

<div class="pro-card">
    <div class="pro-img">
        <?php if($discount > 0): ?>
            <span class="deal-badge"><?= $discount ?>% OFF</span>
        <?php endif; ?>

        <button class="like-btn <?= $liked?'liked':'' ?>"
            onclick="toggleLike(this,<?= $row['id']?>)">
            <i class="<?= $liked?'fa-solid':'fa-regular'?> fa-heart"></i>
        </button>

        <a href="product-details.php?id=<?= $row['id']?>" class="view-btn">
            <i class="fas fa-eye"></i>
        </a>

        <img src="<?= $img ?>">
    </div>

    <div class="pro-details">
        <!-- Category -->
        <div class="pro-cat-label"><?= htmlspecialchars($row['category_name'] ?? 'General') ?></div>

        <!-- ✅ STOCK LABEL -->
        <span class="stock <?= $inStock?'in-stock':'out-stock' ?>">
            <?= $inStock ? 'In Stock' : 'Out of Stock' ?>
        </span>

        <h3><?= htmlspecialchars($row['name']) ?></h3>
        <div class="pro-price-box">
            <span class="price">₹<?= number_format($row['selling_price']) ?></span>
            <?php if($discount > 0): ?>
                <span class="price-old">₹<?= number_format($row['original_price']) ?></span>
                <span class="price-off"><?= $discount ?>% off</span>
            <?php endif; ?>
        </div>

        <!-- 🔥 ACTION BUTTONS -->
        <div class="pro-actions">
            <?php if($inStock): ?>
                <a href="user/cart.php?add=<?= $row['id'] ?>" class="btn-cart" title="Add to Cart">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
                <a href="user/cart.php?add=<?= $row['id'] ?>&checkout=1" class="btn-buy" title="Buy Now">
                    <i class="fas fa-bolt"></i> Buy
                </a>
            <?php else: ?>
                <span class="btn-disabled"><i class="fas fa-ban"></i> Out of Stock</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php endwhile; ?>
</div>

<?php else: ?>
<div class="no-products">
    <h3>No products found</h3>
    <p>Try adjusting your filters or <a href="products.php">view all products</a>.</p>
</div>
<?php endif; ?>

</main>

</div>

<script>
function toggleLike(btn,id){
    fetch("wishlist_action.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:"product_id="+id
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.status==="login"){alert("Please login first");return;}
        let icon=btn.querySelector("i");
        if(data.status==="added"){
            btn.classList.add("liked");
            icon.classList.replace("fa-regular","fa-solid");
        }
        if(data.status==="removed"){
            btn.classList.remove("liked");
            icon.classList.replace("fa-solid","fa-regular");
        }
    });
}
</script>

<?php include('includes/footer.php'); ?>
