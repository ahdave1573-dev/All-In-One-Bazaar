<?php
require_once('includes/auth_check.php');
include("../config/db.php");

// Check product id
if(!isset($_GET['id'])){
    header("Location: manage-products.php");
    exit();
}

$id = $_GET['id'];

// Fetch product data
$productQuery = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
if(mysqli_num_rows($productQuery) == 0){
    header("Location: manage-products.php");
    exit();
}
$product = mysqli_fetch_assoc($productQuery);

// Fetch categories
$catQuery = mysqli_query($conn, "SELECT * FROM categories WHERE status=0");

// Update product
if(isset($_POST['update_product']))
{
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    $small_description = mysqli_real_escape_string($conn, $_POST['small_description']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $original_price = mysqli_real_escape_string($conn, $_POST['original_price']);
    $selling_price = mysqli_real_escape_string($conn, $_POST['selling_price']);
    $qty = mysqli_real_escape_string($conn, $_POST['qty']);
    $status = isset($_POST['status']) ? 1 : 0;
    $trending = isset($_POST['trending']) ? 1 : 0;
    $meta_title = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_keywords = mysqli_real_escape_string($conn, $_POST['meta_keywords']);
    $meta_description = mysqli_real_escape_string($conn, $_POST['meta_description']);

    // Image handling
    if($_FILES['image']['name'] != "")
    {
        $old_image = "../uploads/products/".$product['image'];
        if(file_exists($old_image)){
            unlink($old_image);
        }

        $image = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $filename = time().".".$ext;
        move_uploaded_file($tmp, "../uploads/products/".$filename);
    }
    else{
        $filename = $product['image'];
    }

    $update = "
    UPDATE products SET
        category_id='$category_id',
        name='$name',
        slug='$slug',
        small_description='$small_description',
        description='$description',
        original_price='$original_price',
        selling_price='$selling_price',
        qty='$qty',
        image='$filename',
        status='$status',
        trending='$trending',
        meta_title='$meta_title',
        meta_keywords='$meta_keywords',
        meta_description='$meta_description'
    WHERE id='$id'
    ";

    if(mysqli_query($conn, $update)){
        $_SESSION['success'] = "Product Updated Successfully";
        header("Location: manage-products.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Product | All In One Bazaar</title>
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
    }
    input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    /* Current Image Preview */
    .current-img-box {
        background: #f1f5f9;
        padding: 10px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 10px;
        border: 1px dashed #cbd5e1;
    }
    .current-img-box img {
        height: 80px;
        border-radius: 6px;
        object-fit: cover;
    }
    .img-label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 5px;
        display: block;
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

    /* Update Button */
    .update-btn {
        background: #2563eb;
        color: #fff;
        padding: 14px 30px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        width: 100%;
        transition: 0.3s;
        box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
    }
    .update-btn:hover {
        background: #1d4ed8;
    }

</style>
</head>

<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        
        <div class="header-bar">
            <h2>✏️ Edit Product</h2>
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
                            <?php while($cat = mysqli_fetch_assoc($catQuery)){ ?>
                                <option value="<?= $cat['id']; ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                    <?= $cat['name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-half">
                        <label>Product Name</label>
                        <input type="text" name="name" value="<?= $product['name']; ?>" required>
                    </div>
                </div>

                <label>Slug (URL)</label>
                <input type="text" name="slug" value="<?= $product['slug']; ?>" required>

                <label>Small Description</label>
                <textarea name="small_description" rows="2"><?= $product['small_description']; ?></textarea>

                <label>Detailed Description</label>
                <textarea name="description" rows="5"><?= $product['description']; ?></textarea>

                <div class="row">
                    <div class="col-half">
                        <label>Original Price</label>
                        <input type="number" step="0.01" name="original_price" value="<?= $product['original_price']; ?>" required>
                    </div>
                    <div class="col-half">
                        <label>Selling Price</label>
                        <input type="number" step="0.01" name="selling_price" value="<?= $product['selling_price']; ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-half">
                        <label>Quantity</label>
                        <input type="number" name="qty" value="<?= $product['qty']; ?>" required>
                    </div>
                    
                    <div class="col-half">
                        <label>Product Image</label>
                        <div class="row" style="align-items: center; gap: 10px;">
                            <div class="current-img-box">
                                <span class="img-label">Current</span>
                                <img src="../uploads/products/<?= $product['image']; ?>" alt="Current Product Image">
                            </div>
                            <div style="flex-grow: 1;">
                                <input type="file" name="image" style="padding: 9px; margin-bottom: 0;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" name="status" <?= $product['status'] == 1 ? 'checked' : '' ?>> 
                        Hide Product (Status)
                    </label>
                    <label>
                        <input type="checkbox" name="trending" <?= $product['trending'] == 1 ? 'checked' : '' ?>> 
                        Mark as Trending
                    </label>
                </div>

                <h3 class="section-title">SEO Optimization</h3>

                <label>Meta Title</label>
                <input type="text" name="meta_title" value="<?= $product['meta_title']; ?>">

                <label>Meta Keywords</label>
                <textarea name="meta_keywords" rows="2"><?= $product['meta_keywords']; ?></textarea>

                <label>Meta Description</label>
                <textarea name="meta_description" rows="3"><?= $product['meta_description']; ?></textarea>

                <button type="submit" name="update_product" class="update-btn">
                    <i class="fas fa-save"></i> Update Product
                </button>

            </form>
        </div>
    </div>

</body>
</html>