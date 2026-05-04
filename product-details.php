<?php
// ================== DB CONNECTION ==================
if (file_exists("config/db.php")) {
    include_once("config/db.php");
} else {
    if (file_exists("db.php")) {
        include_once("db.php");
    }
}

// ================== HEADER ==================
include("includes/header.php");

// ================== GET PRODUCT ==================
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT p.*, c.name as category_name 
                               FROM products p 
                               LEFT JOIN categories c ON p.category_id = c.id 
                               WHERE p.id='$product_id'");

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: products.php");
    exit();
}

$product = mysqli_fetch_assoc($result);

// Calculate discount
$discount = 0;
if(!empty($product['original_price']) && $product['original_price'] > $product['selling_price']){
    $discount = round((($product['original_price'] - $product['selling_price']) / $product['original_price']) * 100);
}
?>

<style>
:root {
    --primary: #2563eb;
    --primary-dark: #1e40af;
    --dark: #1e293b;
    --gray: #64748b;
    --light: #f8fafc;
    --success: #16a34a;
    --danger: #ef4444;
}

.detail-container{max-width:1200px;margin:40px auto;padding:0 20px}

/* Breadcrumb */
.breadcrumb {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.85rem; color: var(--gray);
    margin-bottom: 25px; flex-wrap: wrap;
}
.breadcrumb a { color: var(--primary); }
.breadcrumb a:hover { text-decoration: underline; }
.breadcrumb i { font-size: 0.7rem; }

.product-wrapper{
    display:grid;grid-template-columns:1fr 1fr;gap:50px;
    background:#fff;padding:40px;border-radius:20px;
    box-shadow:0 5px 25px rgba(0,0,0,.04);
    border: 1px solid #f0f0f0;
}
/* Product Gallery */
.product-gallery { display: flex; flex-direction: column; gap: 15px; width: 100%; }
.product-image-box {
    background: var(--light); border-radius: 15px;
    display: flex; align-items: center; justify-content: center;
    padding: 30px; position: relative; height: 400px;
    overflow: hidden; width: 100%;
}
.product-image-box img {
    max-width: 100%; max-height: 100%; object-fit: contain;
    transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
}
.product-image-box img.img-fade { opacity: 0; transform: scale(0.98); }

/* Gallery Arrows */
.gallery-btn {
    position: absolute; top: 50%; transform: translateY(-50%);
    background: #fff; border: 1px solid #e2e8f0; color: var(--dark);
    width: 40px; height: 40px; border-radius: 50%; font-size: 1.1rem;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: 0.3s; z-index: 10;
}
.gallery-btn:hover { background: var(--primary); color: #fff; }
.prev-btn { left: 15px; }
.next-btn { right: 15px; }

/* Thumbnails */
.thumbnail-list {
    display: flex; gap: 12px; overflow-x: auto;
    padding-bottom: 5px; scrollbar-width: none;
}
.thumbnail-list::-webkit-scrollbar { display: none; }
.thumb-img {
    width: 80px; height: 80px; border-radius: 10px;
    object-fit: cover; border: 2px solid transparent;
    cursor: pointer; padding: 5px; background: var(--light);
    transition: all 0.3s ease; flex-shrink: 0;
}
.thumb-img:hover { border-color: var(--secondary); }
.thumb-img.active { border-color: var(--accent); box-shadow: 0 4px 10px rgba(245,158,11,0.2); }

.discount-badge {
    position: absolute; top: 15px; left: 15px;
    background: var(--danger); color: #fff;
    padding: 6px 14px; border-radius: 8px;
    font-size: 0.85rem; font-weight: 700;
}

/* Product Info */
.p-category {
    display: inline-block;
    background: #eff6ff; color: var(--primary);
    padding: 5px 14px; border-radius: 20px;
    font-size: 0.8rem; font-weight: 600;
    margin-bottom: 12px; text-transform: uppercase;
    letter-spacing: 0.5px;
}
.p-title{font-size:1.8rem;margin-bottom:15px;font-weight:700;line-height:1.3;color:var(--dark)}

.price-section {
    display: flex; align-items: baseline; gap: 12px;
    margin-bottom: 8px; flex-wrap: wrap;
}
.p-price{font-size:2rem;color:var(--dark);font-weight:700}
.p-price-old{font-size:1.2rem;color:#94a3b8;text-decoration:line-through}
.p-discount-tag {
    background: #dcfce7; color: var(--success);
    padding: 4px 12px; border-radius: 6px;
    font-size: 0.85rem; font-weight: 700;
}
.p-tax-info { font-size: 0.8rem; color: var(--gray); margin-bottom: 20px; }

.stock-badge{
    display:inline-block;padding:6px 14px;border-radius:20px;
    font-size:14px;font-weight:600;margin-bottom:20px;
}
.in-stock{background:#ecfdf5;color:#047857}
.out-stock{background:#fef2f2;color:#b91c1c}

.p-description{color:#555;line-height:1.8;margin-bottom:25px;font-size:0.95rem}

/* Action Buttons */
.action-buttons {
    display: flex; gap: 12px; flex-wrap: wrap;
    margin-top: 10px;
}
.qty-input{
    width:70px;padding:12px;border:2px solid #e2e8f0;
    border-radius:10px;text-align:center;font-size:1rem;font-weight:600;
}
.btn-add-cart{
    background:var(--primary);color:#fff;border:none;
    padding:14px 30px;border-radius:10px;font-size:1rem;
    font-weight:600;cursor:pointer;transition:0.3s;
    display: inline-flex; align-items: center; gap: 8px;
}
.btn-add-cart:hover { background: var(--primary-dark); transform: translateY(-2px); }
.btn-buy-now{
    background: #f97316; color:#fff;border:none;
    padding:14px 30px;border-radius:10px;font-size:1rem;
    font-weight:600;cursor:pointer;transition:0.3s;
    display: inline-flex; align-items: center; gap: 8px;
}
.btn-buy-now:hover { background: #ea580c; transform: translateY(-2px); }
.btn-disabled{background:#cbd5e1;cursor:not-allowed}
.btn-disabled:hover { transform: none; }

/* Features list */
.product-features {
    margin-top: 25px; padding-top: 20px;
    border-top: 1px solid #f1f5f9;
    display: flex; gap: 25px; flex-wrap: wrap;
}
.pf-item { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--gray); }
.pf-item i { color: var(--primary); font-size: 1rem; }

/* Related Products */
.related-section { margin-top: 50px; }
.related-section h2 { font-size: 1.5rem; font-weight: 700; margin-bottom: 25px; }
.related-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:25px}
.r-card{
    background:#fff;padding:20px;border-radius:15px;
    border:1px solid #f0f0f0;text-align:center;
    text-decoration:none;color:#000;transition:0.3s;
}
.r-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
.r-img{height:180px;display:flex;align-items:center;justify-content:center}
.r-img img{max-width:100%;max-height:160px;object-fit:contain}
.r-cat { font-size: 0.75rem; color: var(--primary); margin-top: 10px; text-transform: uppercase; }
.r-name { font-size: 0.9rem; font-weight: 600; margin: 5px 0; }
.r-price{color:var(--dark);font-weight:700}
.r-price-old { color: #94a3b8; font-size: 0.8rem; text-decoration: line-through; margin-left: 5px; }

@media(max-width:900px){.product-wrapper{grid-template-columns:1fr}}
</style>

<div class="detail-container">

<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="index.php">Home</a> <i class="fas fa-chevron-right"></i>
    <?php if(!empty($product['category_name'])): ?>
        <a href="products.php?cat=<?= $product['category_id'] ?>"><?= htmlspecialchars($product['category_name']) ?></a> 
        <i class="fas fa-chevron-right"></i>
    <?php endif; ?>
    <span><?= htmlspecialchars($product['name']) ?></span>
</div>

<div class="product-wrapper">

    <!-- ================= IMAGE GALLERY (DYNAMIC DATABASE LOOP) ================= -->
    <?php
    $gallery_images = [];

    // 1. Fetch Primary Image
    $imageFile = trim($product['image']);
    if ($imageFile != "" && file_exists(__DIR__ . "/assets/images/" . $imageFile)) {
        $gallery_images[] = SITEURL . "assets/images/" . $imageFile;
    } elseif ($imageFile != "" && file_exists(__DIR__ . "/uploads/products/" . $imageFile)) {
        $gallery_images[] = SITEURL . "uploads/products/" . $imageFile;
    } else {
        $gallery_images[] = "https://placehold.co/500x500?text=No+Image";
    }
    
    // 2. Fetch Additional Images from database safely
    try {
        $img_query = mysqli_query($conn, "SELECT image_path FROM product_images WHERE product_id = '$product_id' ORDER BY id ASC");
        if ($img_query) {
            while ($img_row = mysqli_fetch_assoc($img_query)) {
                $path = trim($img_row['image_path']);
                if ($path != "") {
                    if (file_exists(__DIR__ . "/uploads/products/" . $path)) {
                        $gallery_images[] = SITEURL . "uploads/products/" . $path;
                    } elseif (file_exists(__DIR__ . "/assets/images/" . $path)) {
                        $gallery_images[] = SITEURL . "assets/images/" . $path;
                    } else {
                        // Fallback to absolute or string if needed
                        $gallery_images[] = $path;
                    }
                }
            }
        }
    } catch (mysqli_sql_exception $e) {
        // Table probably doesn't exist yet, we will gracefully fallback to single image
    }

    $has_multiple = count($gallery_images) > 1;
    ?>
    <div class="product-gallery">
        <!-- Main Image Container -->
        <div class="product-image-box">
            <?php if($discount > 0): ?>
                <span class="discount-badge"><?= $discount ?>% OFF</span>
            <?php endif; ?>
            
            <?php if($has_multiple): ?>
                <button class="gallery-btn prev-btn" id="prevImage" aria-label="Previous image"><i class="fas fa-chevron-left"></i></button>
            <?php endif; ?>
            
            <img id="mainProductImage" src="<?= htmlspecialchars($gallery_images[0]) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            
            <?php if($has_multiple): ?>
                <button class="gallery-btn next-btn" id="nextImage" aria-label="Next image"><i class="fas fa-chevron-right"></i></button>
            <?php endif; ?>
        </div>
        
        <!-- Thumbnail Drawer (Only Render if we have more than 1 image) -->
        <?php if($has_multiple): ?>
        <div class="thumbnail-list">
            <?php foreach($gallery_images as $index => $galleryImg): ?>
                <img src="<?= htmlspecialchars($galleryImg) ?>" 
                     class="thumb-img <?= $index === 0 ? 'active' : '' ?>" 
                     data-src="<?= htmlspecialchars($galleryImg) ?>" 
                     alt="Gallery Thumbnail <?= $index + 1 ?>">
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- ================= DETAILS ================= -->
    <div>
        <!-- Category Tag -->
        <?php if(!empty($product['category_name'])): ?>
            <a href="products.php?cat=<?= $product['category_id'] ?>" class="p-category">
                <?= htmlspecialchars($product['category_name']) ?>
            </a>
        <?php endif; ?>

        <h1 class="p-title"><?= htmlspecialchars($product['name']) ?></h1>

        <!-- Price Section -->
        <div class="price-section">
            <span class="p-price">₹<?= number_format($product['selling_price']) ?></span>
            <?php if($discount > 0): ?>
                <span class="p-price-old">₹<?= number_format($product['original_price']) ?></span>
                <span class="p-discount-tag"><?= $discount ?>% off</span>
            <?php endif; ?>
        </div>
        <p class="p-tax-info">Inclusive of all taxes</p>

        <!-- 🔥 STOCK STATUS -->
        <?php if ($product['qty'] > 0): ?>
            <div class="stock-badge in-stock">✓ In Stock (<?= $product['qty'] ?> left)</div>
        <?php else: ?>
            <div class="stock-badge out-stock">✗ Out of Stock</div>
        <?php endif; ?>

        <p class="p-description">
            <?= nl2br(htmlspecialchars($product['description'])) ?>
        </p>

        <!-- 🔥 ACTION BUTTONS -->
        <?php if ($product['qty'] > 0): ?>
        <form action="user/cart.php" method="GET" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <input type="hidden" name="add" value="<?= $product['id'] ?>">
            <input type="number" name="qty" class="qty-input"
                   value="1" min="1" max="<?= $product['qty'] ?>">
            <button class="btn-add-cart">
                <i class="fas fa-shopping-cart"></i> Add to Cart
            </button>
            <a href="user/cart.php?add=<?= $product['id'] ?>&qty=1&checkout=1" class="btn-buy-now">
                <i class="fas fa-bolt"></i> Buy Now
            </a>
        </form>
        <?php else: ?>
            <button class="btn-add-cart btn-disabled" disabled>
                <i class="fas fa-ban"></i> Out of Stock
            </button>
        <?php endif; ?>

        <!-- Product Features -->
        <div class="product-features">
            <div class="pf-item"><i class="fas fa-truck"></i> Free Delivery</div>
            <div class="pf-item"><i class="fas fa-undo"></i> 30-Day Returns</div>
            <div class="pf-item"><i class="fas fa-shield-alt"></i> Secure Payment</div>
            <div class="pf-item"><i class="fas fa-certificate"></i> Genuine Products</div>
        </div>
    </div>

</div>

<!-- ================== RELATED PRODUCTS ================== -->
<div class="related-section">
    <h2>You may also like</h2>

    <div class="related-grid">
    <?php
    $cat_id = $product['category_id'];
    $r_res = mysqli_query(
        $conn,
        "SELECT p.*, c.name as category_name 
         FROM products p 
         LEFT JOIN categories c ON p.category_id = c.id
         WHERE p.id!='$product_id' AND p.status=0 
         AND p.category_id='$cat_id'
         ORDER BY p.id DESC LIMIT 4"
    );

    // If not enough from same category, get from all
    if(!$r_res || mysqli_num_rows($r_res) < 2){
        $r_res = mysqli_query(
            $conn,
            "SELECT p.*, c.name as category_name 
             FROM products p 
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.id!='$product_id' AND p.status=0 
             ORDER BY p.id DESC LIMIT 4"
        );
    }

    while ($row = mysqli_fetch_assoc($r_res)) {
        $r_image = trim($row['image']);
        if ($r_image != "" && file_exists(__DIR__ . "/assets/images/" . $r_image)) {
            $r_img = SITEURL . "assets/images/" . $r_image;
        } elseif ($r_image != "" && file_exists(__DIR__ . "/uploads/products/" . $r_image)) {
            $r_img = SITEURL . "uploads/products/" . $r_image;
        } else {
            $r_img = "https://placehold.co/300x300?text=No+Image";
        }

        $r_discount = 0;
        if(!empty($row['original_price']) && $row['original_price'] > $row['selling_price']){
            $r_discount = round((($row['original_price'] - $row['selling_price']) / $row['original_price']) * 100);
        }
    ?>
        <a href="product-details.php?id=<?= $row['id'] ?>" class="r-card">
            <div class="r-img">
                <img src="<?= $r_img ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            </div>
            <div class="r-cat"><?= htmlspecialchars($row['category_name'] ?? 'General') ?></div>
            <div class="r-name"><?= htmlspecialchars($row['name']) ?></div>
            <div>
                <span class="r-price">₹<?= number_format($row['selling_price']) ?></span>
                <?php if($r_discount > 0): ?>
                    <span class="r-price-old">₹<?= number_format($row['original_price']) ?></span>
                <?php endif; ?>
            </div>
        </a>
    <?php } ?>
    </div>
</div>

</div>

<!-- ================= GALLERY SCRIPT ================= -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const mainImg = document.getElementById("mainProductImage");
    const thumbs = document.querySelectorAll(".thumb-img");
    const prevBtn = document.getElementById("prevImage");
    const nextBtn = document.getElementById("nextImage");
    const galleryContainer = document.querySelector(".product-gallery"); // For hover pause
    
    let images = Array.from(thumbs).map(t => t.getAttribute("data-src"));
    
    // Protect if less than 2 images are fetched
    if(images.length <= 1) return;

    let currentIndex = 0;
    let autoSlideInterval;

    // 1. Function to handle switching images with fade animation
    function updateMainImage(index) {
        if(index < 0) index = images.length - 1;
        if(index >= images.length) index = 0;
        if(currentIndex === index) return;
        
        currentIndex = index;
        mainImg.classList.add("img-fade");
        
        setTimeout(() => {
            mainImg.src = images[currentIndex];
            thumbs.forEach(t => t.classList.remove("active"));
            thumbs[currentIndex].classList.add("active");
            mainImg.classList.remove("img-fade");
        }, 300);
    }

    // 2. Start Auto-Slide Timer (every 3 seconds)
    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            updateMainImage(currentIndex + 1);
        }, 3000);
    }

    // 3. Stop Auto-Slide Timer
    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    // 4. Bind event listeners
    thumbs.forEach((thumb, index) => {
        thumb.addEventListener("click", () => {
            updateMainImage(index);
            // reset timer on manual interaction
            stopAutoSlide();
            startAutoSlide();
        });
    });

    if(prevBtn) {
        prevBtn.addEventListener("click", () => {
            updateMainImage(currentIndex - 1);
            stopAutoSlide();
            startAutoSlide();
        });
    }

    if(nextBtn) {
        nextBtn.addEventListener("click", () => {
            updateMainImage(currentIndex + 1);
            stopAutoSlide();
            startAutoSlide();
        });
    }

    // 5. Pause auto-slide when hovering over the gallery
    if(galleryContainer) {
        galleryContainer.addEventListener("mouseenter", stopAutoSlide);
        galleryContainer.addEventListener("mouseleave", startAutoSlide);
    }

    // Initialize auto-slide on page load
    startAutoSlide();
});
</script>

<?php include("includes/footer.php"); ?>
