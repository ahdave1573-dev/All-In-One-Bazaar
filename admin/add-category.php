<?php
require_once('includes/auth_check.php');
include("../config/db.php");

if (isset($_POST['add_category'])) {

    $name        = mysqli_real_escape_string($conn, $_POST['name']);
    $slug        = mysqli_real_escape_string($conn, $_POST['slug']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status      = isset($_POST['status']) ? 1 : 0;

    /* ================= IMAGE UPLOAD (FIXED) ================= */
    $filename = "";

    if (!empty($_FILES['image']['name'])) {

        $image = $_FILES['image']['name'];
        $tmp   = $_FILES['image']['tmp_name'];

        // Get extension
        $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        // Clean slug (safe filename)
        $clean_slug = strtolower(trim($slug));
        $clean_slug = preg_replace('/[^a-z0-9\-]/', '-', $clean_slug);
        $clean_slug = preg_replace('/-+/', '-', $clean_slug);

        // Final image name
        $filename = $clean_slug . "_" . time() . "." . $ext;

        // Upload folder
        $upload_path = "../uploads/categories/" . $filename;

        move_uploaded_file($tmp, $upload_path);
    }

    /* ================= INSERT QUERY ================= */
    $insert = "INSERT INTO categories (name, slug, description, image, status)
               VALUES ('$name', '$slug', '$description', '$filename', '$status')";

    if (mysqli_query($conn, $insert)) {
        $_SESSION['success'] = "Category Added Successfully";
        header("Location: manage-categories.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Category | All In One Bazaar</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{background:#f1f5f9;display:flex;min-height:100vh}

/* SIDEBAR */
.sidebar{
    width:260px;background:#0f172a;color:#fff;
    position:fixed;left:0;top:0;height:100%;
    display:flex;flex-direction:column
}
.logo-section{
    padding:20px;font-size:24px;font-weight:bold;
    border-bottom:1px solid #1e293b
}
.logo-section span{color:#3b82f6}
.menu{list-style:none;margin:20px 0;flex:1}
.menu li a{
    display:flex;align-items:center;gap:12px;
    padding:15px 25px;color:#94a3b8;
    text-decoration:none;font-size:15px
}
.menu li a:hover,.menu li a.active{
    background:#1e293b;color:#fff;border-left:4px solid #3b82f6
}
.logout-link{
    padding:15px 25px;color:#ef4444;
    text-decoration:none;font-weight:bold
}

/* MAIN */
.main-content{
    margin-left:260px;width:calc(100% - 260px);
    padding:30px
}
.header-bar{
    display:flex;justify-content:space-between;
    align-items:center;margin-bottom:25px
}
.back-btn{
    background:#64748b;color:#fff;
    padding:10px 20px;border-radius:8px;
    text-decoration:none
}

/* FORM */
.form-box{
    background:#fff;padding:30px;
    border-radius:12px;max-width:700px;
    box-shadow:0 4px 6px rgba(0,0,0,.1)
}
label{font-weight:600;margin-bottom:6px;display:block}
input,textarea{
    width:100%;padding:12px;margin-bottom:18px;
    border:1px solid #cbd5e1;border-radius:8px
}
.checkbox-group{display:flex;align-items:center;margin-bottom:20px}
.checkbox-group input[type="checkbox"] { width: auto; margin-bottom: 0; margin-right: 10px; }
.save-btn{
    width:100%;background:#0f172a;color:#fff;
    padding:12px;border:none;border-radius:8px;
    font-size:16px;font-weight:bold;cursor:pointer
}
</style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

    <div class="header-bar">
        <h2>➕ Add Category</h2>
        <a href="manage-categories.php" class="back-btn">⬅ Back</a>
    </div>

    <div class="form-box">
        <form method="POST" enctype="multipart/form-data">

            <label>Category Name</label>
            <input type="text" name="name" required>

            <label>Slug (URL)</label>
            <input type="text" name="slug" required placeholder="hp-pavilion-15-laptop">

            <label>Description</label>
            <textarea name="description" rows="4"></textarea>

            <label>Category Image</label>
            <input type="file" name="image">

            <div class="checkbox-group">
                <input type="checkbox" name="status">
                <span>Hide Category</span>
            </div>

            <button type="submit" name="add_category" class="save-btn">
                <i class="fas fa-save"></i> Save Category
            </button>

        </form>
    </div>

</div>

</body>
</html>
