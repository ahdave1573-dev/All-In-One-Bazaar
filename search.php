<?php
// ::::: 1. CONNECT DATABASE FIRST ::::::
$conn = null;
if (file_exists("config/db.php")) {
    include_once("config/db.php");
} elseif (file_exists("db.php")) {
    include_once("db.php");
}

// ::::: 2. INCLUDE HEADER ::::::
include("includes/header.php");

// ::::: 3. GET SEARCH KEYWORD & CATEGORY ::::::
$search = "";
$search_cat = "";
if (isset($_GET['search']) && trim($_GET['search']) !== "" && $conn) {
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
}
if (isset($_GET['cat']) && $_GET['cat'] !== "") {
    $search_cat = intval($_GET['cat']);
}

// Fetch categories for filter
$categories = [];
if($conn){
    $cq = mysqli_query($conn, "SELECT id, name FROM categories WHERE status=0 ORDER BY name");
    while($cr = mysqli_fetch_assoc($cq)){ $categories[] = $cr; }
}
?>

<style>
.search-header{
    background: linear-gradient(135deg, #eff6ff, #fff);
    padding:40px 5%;
    border-bottom:1px solid #e2e8f0;
}
.search-header h1{font-size:1.6rem;color:var(--dark)}
.search-header span{color:var(--primary)}

.search-bar-container {
    max-width: 700px;
    margin-top: 15px;
    height: 50px;
    display: flex;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.search-cat-select {
    border: none;
    background: #f3f4f6;
    padding: 0 15px;
    font-size: 0.85rem;
    color: #4b5563;
    outline: none;
    border-right: 1px solid #e5e7eb;
    cursor: pointer;
    font-weight: 500;
    height: 100%;
    flex-shrink: 0;
}
.search-input {
    flex: 1;
    border: none;
    padding: 0 18px;
    font-size: 0.95rem;
    outline: none;
    height: 100%;
}
.search-btn {
    background: var(--accent);
    color: #fff;
    border: none;
    padding: 0 20px;
    cursor: pointer;
    font-size: 1.1rem;
    transition: 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    flex-shrink: 0;
    min-width: 55px;
}
.search-btn:hover { background: #d97706; }

.results-container{
    max-width:1200px;
    margin:40px auto;
    padding:0 20px;
}
.results-count {
    color: var(--gray);
    font-size: 0.9rem;
    margin-bottom: 20px;
}
.product-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(230px,1fr));
    gap:25px;
}
.product-card{
    background:#fff;
    border-radius:15px;
    border:1px solid #f1f5f9;
    overflow: hidden;
    transition: 0.3s;
}
.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.06);
}
.pro-img{
    height:200px;
    display:flex;
    align-items:center;
    justify-content:center;
    background: #f8fafc;
    padding: 15px;
    position: relative;
}
.pro-img img{
    max-width:90%;
    max-height:90%;
    object-fit:contain;
}
.pro-info{padding:18px}
.pro-cat-label {
    font-size: 0.72rem;
    color: var(--primary);
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}
.pro-name { font-size: 0.95rem; font-weight: 600; margin-bottom: 8px; }
.pro-price-box { display: flex; align-items: baseline; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
.pro-price{ font-weight:700; color:var(--dark); font-size: 1.05rem; }
.pro-price-old { color: #94a3b8; font-size: 0.8rem; text-decoration: line-through; }
.pro-discount { color: #16a34a; font-size: 0.72rem; font-weight: 700; }
.view-details-btn {
    display: inline-flex; align-items: center; gap: 6px;
    color: var(--primary); font-weight: 600;
    font-size: 0.85rem; transition: 0.3s;
}
.view-details-btn:hover { gap: 10px; }
.deal-badge {
    position: absolute; top: 10px; left: 10px;
    background: #ef4444; color: #fff;
    padding: 3px 10px; border-radius: 6px;
    font-size: 0.7rem; font-weight: 700;
}
.no-results{
    text-align:center;
    padding:60px;
    border:1px dashed #cbd5e1;
    border-radius:15px;
    background: #fff;
}
.no-results h2 { margin-bottom: 10px; }
.no-results a { color: var(--primary); font-weight: 600; }
</style>

<div class="search-header">
    <div style="max-width:1200px;margin:auto;">
        <h1>
            <?= $search ? 'Search Results for "<span>'.htmlspecialchars($search).'</span>"' : 'Search Products' ?>
        </h1>

        <form method="GET" class="search-bar-container">
            <select name="cat" class="search-cat-select">
                <option value="">All Categories</option>
                <?php foreach($categories as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($search_cat == $c['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="search" class="search-input"
                   value="<?= htmlspecialchars($search) ?>"
                   placeholder="Search for products, brands and more...">
            <button class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
</div>

<div class="results-container">

<?php
if ($search == "" && $search_cat == "") {
    echo '<div class="no-results"><h2>🔍 Enter a keyword to search</h2><p>Try searching for "shoes", "laptop", "books" etc.</p></div>';
}
elseif (!$conn) {
    echo '<div class="no-results"><h2>DB connection failed</h2></div>';
}
else {
    $conditions = [];
    if($search != ""){
        $conditions[] = "(p.name LIKE '%$search%' OR p.description LIKE '%$search%')";
    }
    if($search_cat != ""){
        $conditions[] = "p.category_id = '$search_cat'";
    }
    $conditions[] = "p.status = 0";
    
    $whereSQL = "WHERE " . implode(" AND ", $conditions);
    
    $sql = "SELECT p.id, p.name, p.image, p.selling_price, p.original_price, c.name as category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            $whereSQL
            ORDER BY p.id DESC";

    $result = mysqli_query($conn, $sql);
    $count = $result ? mysqli_num_rows($result) : 0;

    if ($count > 0) {
        echo '<div class="results-count">Found <strong>'.$count.'</strong> product'.($count>1?'s':'').'</div>';
        echo '<div class="product-grid">';

        while ($row = mysqli_fetch_assoc($result)) {
            $img = (!empty($row['image']) && file_exists("uploads/products/".$row['image']))
                ? "uploads/products/".$row['image']
                : ((!empty($row['image']) && file_exists("assets/images/".$row['image']))
                    ? "assets/images/".$row['image']
                    : "https://placehold.co/300?text=No+Image");

            $price = $row['selling_price'] ?? 0;
            $discount = 0;
            if(!empty($row['original_price']) && $row['original_price'] > $row['selling_price']){
                $discount = round((($row['original_price'] - $row['selling_price']) / $row['original_price']) * 100);
            }

            echo '
            <div class="product-card">
                <div class="pro-img">
                    '.($discount > 0 ? '<span class="deal-badge">'.$discount.'% OFF</span>' : '').'
                    <img src="'.$img.'" alt="'.htmlspecialchars($row['name']).'">
                </div>
                <div class="pro-info">
                    <div class="pro-cat-label">'.htmlspecialchars($row['category_name'] ?? 'General').'</div>
                    <h3 class="pro-name">'.htmlspecialchars($row['name']).'</h3>
                    <div class="pro-price-box">
                        <span class="pro-price">₹'.number_format($price).'</span>
                        '.($discount > 0 ? '<span class="pro-price-old">₹'.number_format($row['original_price']).'</span>
                        <span class="pro-discount">'.$discount.'% off</span>' : '').'
                    </div>
                    <a href="product-details.php?id='.$row['id'].'" class="view-details-btn">
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>';
        }

        echo '</div>';

    } else {
        echo '<div class="no-results">
                <h2>No products found</h2>
                <p>Try a different keyword or <a href="products.php">browse all products</a>.</p>
              </div>';
    }
}
?>

</div>

<?php include("includes/footer.php"); ?>
