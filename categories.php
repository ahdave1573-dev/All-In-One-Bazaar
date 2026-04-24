<?php
// =====================
// 1. DATABASE CONNECTION
// =====================
if (file_exists("config/db.php")) {
    include_once("config/db.php");
} else {
    if (file_exists("db.php")) {
        include_once("db.php");
    }
}

// =====================
// 2. HEADER
// =====================
include("includes/header.php");
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* PAGE HEADER */
    .page-header{
        background:linear-gradient(135deg,#eff6ff 0%,#ffffff 100%);
        padding:60px 5%;
        text-align:center;
        border-bottom:1px solid #e2e8f0;
    }
    .page-header h1{
        font-size:2.5rem;
        color:#0f172a;
        margin-bottom:10px;
        font-weight:700;
        font-family: 'Poppins', sans-serif;
    }
    .page-header p{
        color:#64748b;
        font-size:1.1rem;
        max-width:600px;
        margin:0 auto;
        font-family: 'Poppins', sans-serif;
    }

    /* CONTAINER */
    .cat-container{
        max-width:1200px;
        margin:50px auto;
        padding:0 20px;
        min-height:50vh;
        font-family: 'Poppins', sans-serif;
    }

    /* GRID */
    .category-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
        gap:30px;
    }

    /* CARD */
    .category-card{
        background:#ffffff;
        border-radius:15px;
        border:1px solid #f1f5f9;
        text-align:center;
        padding:40px 20px;
        transition:0.3s;
        position:relative;
        text-decoration:none;
        display: block;
    }
    .category-card:hover{
        transform:translateY(-8px);
        box-shadow:0 15px 30px rgba(0,0,0,0.08);
        border-color:#2563eb;
    }

    /* --- 🔥 IMAGE FIX START --- */
    .cat-icon-circle{
        width: 100px;         /* Increased size slightly */
        height: 100px;
        background: #eff6ff;
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        
        /* This prevents the image from touching edges */
        padding: 20px;        
        box-sizing: border-box; 
    }

    .cat-icon-circle img{
        width: 100%;
        height: 100%;
        
        /* This ensures the WHOLE image is visible */
        object-fit: contain;  
        
        /* Optional: Makes white backgrounds transparent-ish */
        mix-blend-mode: multiply; 
    }
    /* --- 🔥 IMAGE FIX END --- */

    .cat-icon-circle i{
        font-size:32px;
        color:#2563eb;
    }

    /* TITLE */
    .cat-title{
        font-size:1.25rem;
        font-weight:600;
        color:#0f172a;
        margin-bottom:8px;
    }

    /* TEXT */
    .cat-count{
        color:#64748b;
        font-size:0.9rem;
    }

    /* ARROW ANIMATION */
    .go-icon{
        position:absolute;
        bottom:18px;
        left:50%;
        transform:translateX(-50%);
        opacity:0;
        transition:0.3s;
        color:#2563eb;
    }
    .category-card:hover .go-icon{
        opacity:1;
        bottom:14px;
    }

    /* EMPTY STATE */
    .empty-state{
        text-align:center;
        padding:50px;
        grid-column:1/-1;
        color:#64748b;
    }
</style>

<div class="page-header">
    <h1>Browse Categories</h1>
    <p>Explore our wide range of categories — find exactly what you need.</p>
</div>

<div class="cat-container">
    <div class="category-grid">

        <?php
        // =====================
        // 3. FETCH CATEGORIES WITH PRODUCT COUNT
        // =====================
        $sql = "SELECT c.*, COUNT(p.id) as product_count 
                FROM categories c 
                LEFT JOIN products p ON c.id = p.category_id AND p.status=0
                WHERE c.status = 0 
                GROUP BY c.id 
                ORDER BY c.name ASC";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {

                // Path Check
                $imgPath = "uploads/categories/" . $row['image'];
                ?>

                <a href="products.php?cat=<?= $row['id']; ?>" class="category-card">

                    <div class="cat-icon-circle">
                        <?php if (!empty($row['image']) && file_exists($imgPath)) { ?>
                            <img src="<?= $imgPath; ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                        <?php } else { ?>
                            <i class="fas fa-layer-group"></i>
                        <?php } ?>
                    </div>

                    <h3 class="cat-title"><?= htmlspecialchars($row['name']); ?></h3>
                    <p class="cat-count"><?= $row['product_count'] ?> Products</p>
                    <i class="fas fa-arrow-right go-icon"></i>

                </a>

                <?php
            }
        } else {
            echo "<div class='empty-state'>No categories found</div>";
        }
        ?>

    </div>
</div>

<?php
// =====================
// 4. FOOTER
// =====================
include("includes/footer.php");
?>