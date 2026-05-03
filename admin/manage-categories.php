<?php
require_once('includes/auth_check.php');
include("../config/db.php");

/* ======================
   DELETE CATEGORY
====================== */
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];

    // Fetch image name
    $imgQuery = mysqli_query($conn, "SELECT image FROM categories WHERE id='$id' LIMIT 1");
    if ($imgQuery && mysqli_num_rows($imgQuery) == 1) {
        $cat = mysqli_fetch_assoc($imgQuery);

        if (!empty($cat['image'])) {
            $path = "../uploads/categories/" . $cat['image'];
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    mysqli_query($conn, "DELETE FROM categories WHERE id='$id'");
    $_SESSION['success'] = "Category deleted successfully";
    header("Location: manage-categories.php");
    exit();
}

/* ======================
   FETCH CATEGORIES
====================== */
$query  = "SELECT * FROM categories ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Categories | All In One Bazaar</title>
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
.header-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}
h2{margin:0;color:#0f172a}

/* ADD BUTTON */
.add-btn{
    background:#16a34a;
    color:#fff;
    padding:10px 18px;
    border-radius:8px;
    text-decoration:none;
    font-weight:600;
    display:flex;
    align-items:center;
    gap:8px;
}

/* TABLE */
.table-wrapper{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 6px rgba(0,0,0,.08);
}
table{width:100%;border-collapse:collapse}
thead tr{background:#f8fafc;border-bottom:2px solid #e2e8f0}
th,td{padding:14px;text-align:center;font-size:14px}
th{text-transform:uppercase;color:#475569}
td{border-bottom:1px solid #e2e8f0;color:#334155}

/* IMAGE */
.cat-img{
    width:50px;
    height:50px;
    object-fit:cover;
    border-radius:6px;
    border:1px solid #e2e8f0;
}

/* BADGE */
.badge{
    padding:5px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}
.visible{background:#dcfce7;color:#166534}
.hidden{background:#fee2e2;color:#991b1b}

/* ACTION */
.action-btn{
    width:32px;
    height:32px;
    border-radius:6px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    text-decoration:none;
}
.edit{background:#3b82f6}
.delete{background:#ef4444}

/* SUCCESS */
.success-msg{
    background:#dcfce7;
    color:#166534;
    padding:15px;
    border-radius:8px;
    margin-bottom:15px;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<?php include 'includes/sidebar.php'; ?>

<!-- MAIN -->
<div class="main-content">

    <div class="header-bar">
        <h2>📂 Manage Categories</h2>
        <a href="add-category.php" class="add-btn">
            <i class="fa fa-plus"></i> Add Category
        </a>
    </div>

    <?php
    if (isset($_SESSION['success'])) {
        echo "<div class='success-msg'>".$_SESSION['success']."</div>";
        unset($_SESSION['success']);
    }
    ?>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="text-align:left">Name</th>
                    <th>Slug</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if(mysqli_num_rows($result)>0):
                while($row=mysqli_fetch_assoc($result)):
                $imgPath = "../uploads/categories/".$row['image'];
            ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td style="text-align:left;font-weight:500"><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['slug']) ?></td>

                    <td>
                        <?php if(!empty($row['image']) && file_exists($imgPath)): ?>
                            <img src="<?= $imgPath ?>" class="cat-img">
                        <?php else: ?>
                            <img src="../uploads/categories/no-image.png" class="cat-img">
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if($row['status']==0): ?>
                            <span class="badge visible">Visible</span>
                        <?php else: ?>
                            <span class="badge hidden">Hidden</span>
                        <?php endif; ?>
                    </td>

                    <td><?= date("d M Y",strtotime($row['created_at'])) ?></td>

                    <td>
                        <a href="edit-category.php?id=<?= $row['id'] ?>" class="action-btn edit">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="manage-categories.php?delete_id=<?= $row['id'] ?>"
                           onclick="return confirm('Delete this category?')"
                           class="action-btn delete">
                           <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="7">No Categories Found</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
