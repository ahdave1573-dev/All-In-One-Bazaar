<?php
require_once('includes/auth_check.php');
include("../config/db.php");

/* ======================
   GET CATEGORY ID
====================== */
if (!isset($_GET['id'])) {
    header("Location: manage-categories.php");
    exit();
}

$id = (int)$_GET['id'];

/* ======================
   FETCH CATEGORY
====================== */
$catQuery = mysqli_query($conn, "SELECT * FROM categories WHERE id='$id' LIMIT 1");
if (!$catQuery || mysqli_num_rows($catQuery) != 1) {
    header("Location: manage-categories.php");
    exit();
}
$category = mysqli_fetch_assoc($catQuery);

/* ======================
   UPDATE CATEGORY
====================== */
if (isset($_POST['update_category'])) {

    $name   = mysqli_real_escape_string($conn, $_POST['name']);
    $slug   = mysqli_real_escape_string($conn, $_POST['slug']);
    $status = isset($_POST['status']) ? 1 : 0;

    $newImage = $category['image'];

    /* IMAGE UPDATE */
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newImage = time() . "." . $ext;

        $uploadPath = "../uploads/categories/" . $newImage;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {

            // delete old image
            if (!empty($category['image'])) {
                $oldPath = "../uploads/categories/" . $category['image'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }
    }

    mysqli_query($conn, "
        UPDATE categories SET
            name='$name',
            slug='$slug',
            image='$newImage',
            status='$status'
        WHERE id='$id'
    ");

    $_SESSION['success'] = "Category updated successfully";
    header("Location: manage-categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Category | All In One Bazaar</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
*{box-sizing:border-box}
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:#f1f5f9;
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
    text-decoration:none;
    display:flex;
    gap:12px;
}

/* MAIN */
.main-content{
    margin-left:260px;
    width:calc(100% - 260px);
    padding:30px;
}

/* FORM */
.form-box{
    max-width:600px;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 4px 6px rgba(0,0,0,.08);
}
.form-group{margin-bottom:20px}
label{display:block;margin-bottom:8px;font-weight:600;color:#334155}
input[type=text],input[type=file]{
    width:100%;
    padding:12px;
    border:1px solid #e2e8f0;
    border-radius:8px;
}
.preview-img{
    width:120px;
    height:120px;
    object-fit:cover;
    border-radius:8px;
    border:1px solid #e2e8f0;
    margin-top:10px;
}
.submit-btn{
    background:#2563eb;
    color:#fff;
    padding:12px 20px;
    border:none;
    border-radius:8px;
    font-size:15px;
    cursor:pointer;
}
.submit-btn:hover{background:#1d4ed8}
</style>
</head>

<body>

<!-- SIDEBAR -->
<?php include 'includes/sidebar.php'; ?>

<!-- MAIN -->
<div class="main-content">
    <h2>Edit Category</h2>

    <div class="form-box">
        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" value="<?= htmlspecialchars($category['slug']) ?>" required>
            </div>

            <div class="form-group">
                <label>Category Image</label>
                <input type="file" name="image" accept="image/*">
                <?php if (!empty($category['image'])): ?>
                    <img src="../uploads/categories/<?= $category['image'] ?>" class="preview-img">
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="status" <?= $category['status']==1 ? 'checked' : '' ?>>
                    Hide Category
                </label>
            </div>

            <button type="submit" name="update_category" class="submit-btn">
                Update Category
            </button>

        </form>
    </div>
</div>

</body>
</html>
