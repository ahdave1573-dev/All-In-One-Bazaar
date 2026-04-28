<?php
require_once('includes/auth_check.php');
include("../config/db.php");

// ===== ADD PRODUCT =====
if (isset($_POST['save_product'])) {

    // Form Data
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $name        = mysqli_real_escape_string($conn, $_POST['name']);
    $slug        = mysqli_real_escape_string($conn, $_POST['slug']);
    $small_desc  = mysqli_real_escape_string($conn, $_POST['small_desc']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $original_price = mysqli_real_escape_string($conn, $_POST['original_price']);
    $selling_price  = mysqli_real_escape_string($conn, $_POST['selling_price']);
    $qty         = mysqli_real_escape_string($conn, $_POST['qty']);

    $meta_title       = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_keywords    = mysqli_real_escape_string($conn, $_POST['meta_keywords']);
    $meta_description = mysqli_real_escape_string($conn, $_POST['meta_description']);

    $status   = isset($_POST['status']) ? '1' : '0';
    $trending = isset($_POST['trending']) ? '1' : '0';

    // ===== IMAGE UPLOAD =====
    // ===== IMAGE UPLOAD =====
$image_name = "";

if (!empty($_FILES['image']['name'])) {

    // product name clean
    $clean_name = strtolower($name);
    $clean_name = preg_replace('/[^a-z0-9]/', '-', $clean_name);

    // image extension
    $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

    // final image name => product-name_time.jpg
    $image_name = $clean_name . "_" . time() . "." . $image_ext;

    $upload_path = "../uploads/products/";

    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    move_uploaded_file($_FILES['image']['tmp_name'], $upload_path . $image_name);
}


    // ===== INSERT QUERY =====
    $query = "INSERT INTO products
    (category_id,name,slug,small_description,description,
    original_price,selling_price,qty,image,
    meta_title,meta_keywords,meta_description,
    status,trending)
    VALUES
    ('$category_id','$name','$slug','$small_desc','$description',
    '$original_price','$selling_price','$qty','$image_name',
    '$meta_title','$meta_keywords','$meta_description',
    '$status','$trending')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('✅ Product Added Successfully');window.location='add-product.php';</script>";
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product | All In One Bazaar</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Global Reset */
    * { box-sizing: border-box; }
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f1f5f9;
        margin: 0;
        padding: 0;
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar Styling */
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
        z-index: 100;
    }
    .logo-section {
        padding: 20px;
        font-size: 24px;
        font-weight: bold;
        border-bottom: 1px solid #1e293b;
        color: #fff;
    }
    .logo-section span { color: #3b82f6; }
    
    .menu {
        list-style: none;
        padding: 0;
        margin: 20px 0;
        flex-grow: 1;
    }
    .menu li a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 25px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 15px;
        transition: 0.3s;
    }
    .menu li a:hover, .menu li a.active {
        background: #1e293b;
        color: #fff;
        border-left: 4px solid #3b82f6;
    }
    .logout-link {
        padding: 15px 25px;
        color: #ef4444 !important;
        text-decoration: none;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    /* Main Content Area */
    .main-content {
        margin-left: 260px;
        width: calc(100% - 260px);
        padding: 30px;
    }

    /* Header Bar */
    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    h2 { color: #0f172a; margin: 0; font-size: 24px; }

    .back-btn {
        background: #64748b;
        color: #fff;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: 0.3s;
    }
    .back-btn:hover { background: #475569; }

    /* Form Container */
    .form-box {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        max-width: 1000px;
    }

    /* Form Elements */
    label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        color: #334155;
        font-size: 14px;
    }
    input[type="text"],
    input[type="number"],
    input[type="file"],
    textarea,
    select {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        color: #1e293b;
        background: #f8fafc;
        transition: border 0.3s;
    }
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #3b82f6;
        background: #fff;
    }
    
    /* Grid Layout */
    .row {
        display: flex;
        gap: 20px;
    }
    .col-half {
        flex: 1;
    }

    /* Checkboxes */
    .checkbox-group {
        display: flex;
        gap: 30px;
        margin: 10px 0 25px 0;
        padding: 15px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    .checkbox-group label {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-weight: 500;
    }
    input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    /* Section Divider */
    h3.section-title {
        color: #3b82f6;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 10px;
        margin-top: 20px;
        margin-bottom: 20px;
        font-size: 18px;
    }

    /* Save Button */
    .save-btn {
        background: #0f172a;
        color: #fff;
        padding: 14px 30px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        width: 100%;
        transition: 0.3s;
    }
    .save-btn:hover {
        background: #1e293b;
    }
</style>
</head>

<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        
        <div class="header-bar">
            <h2>➕ Add New Product</h2>
            <a href="manage-products.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
        </div>

        <div class="form-box">
            <form method="POST" enctype="multipart/form-data">

                <h3 class="section-title">Product Details</h3>

                <div class="row">
                    <div class="col-half">
                        <label>Category</label>
                        <select name="category_id" required>
                            <option value="">-- Select Category --</option>
                            <?php
                            $cat = mysqli_query($conn, "SELECT * FROM categories");
                            while ($row = mysqli_fetch_assoc($cat)) {
                                echo "<option value='{$row['id']}'>{$row['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-half">
                        <label>Product Name</label>
                        <input type="text" name="name" required placeholder="Enter product name">
                    </div>
                </div>

                <label>Slug (URL)</label>
                <input type="text" name="slug" required placeholder="e.g. nike-sports-shoes">

                <label>Small Description</label>
                <textarea name="small_desc" rows="2" placeholder="Short summary of the product"></textarea>

                <label>Description</label>
                <textarea name="description" rows="5" placeholder="Full product details..."></textarea>

                <div class="row">
                    <div class="col-half">
                        <label>Original Price</label>
                        <input type="number" name="original_price" required placeholder="0.00">
                    </div>
                    <div class="col-half">
                        <label>Selling Price</label>
                        <input type="number" name="selling_price" required placeholder="0.00">
                    </div>
                </div>

                <div class="row">
                    <div class="col-half">
                        <label>Quantity</label>
                        <input type="number" name="qty" required placeholder="Stock Qty">
                    </div>
                    <div class="col-half">
                        <label>Product Image</label>
                        <input type="file" name="image" required style="padding:9px;">
                    </div>
                </div>

                <div class="checkbox-group">
                    <label><input type="checkbox" name="status"> Hide Product</label>
                    <label><input type="checkbox" name="trending"> Trending</label>
                </div>

                <h3 class="section-title">SEO Optimization</h3>

                <label>Meta Title</label>
                <input type="text" name="meta_title" placeholder="Title for Search Engine">

                <label>Meta Keywords</label>
                <textarea name="meta_keywords" rows="2" placeholder="keywords, tags, etc"></textarea>

                <label>Meta Description</label>
                <textarea name="meta_description" rows="3" placeholder="Description for search results"></textarea>

                <button type="submit" name="save_product" class="save-btn">
                    <i class="fas fa-save"></i> Save Product
                </button>

            </form>
        </div>
    </div>

</body>
</html>