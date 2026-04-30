<?php
session_start();

// 1. Login Check
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

// 2. Includes
include('../config/constants.php');
include('../config/db.php');
include('../includes/header.php');

$user_id = $_SESSION['user_id'];

// 3. HANDLE REMOVE ACTION (PHP Logic)
if(isset($_POST['remove_id'])){
    $wishlist_id = mysqli_real_escape_string($conn, $_POST['remove_id']);
    
    // Delete query
    $delete_sql = "DELETE FROM wishlist WHERE id='$wishlist_id' AND user_id='$user_id'";
    $run_delete = mysqli_query($conn, $delete_sql);
    
    if($run_delete){
        // Refresh page to show changes
        echo "<script>window.location.href='wishlist.php';</script>";
    }
}

// 4. Fetch Wishlist Items
$query = "
    SELECT w.id AS wid, p.* FROM wishlist w 
    JOIN products p ON w.product_id = p.id 
    WHERE w.user_id = '$user_id' 
    ORDER BY w.id DESC
";
$result = mysqli_query($conn, $query);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
        --primary: #2563eb;
        --dark: #1e293b;
        --light: #f8fafc;
        --red: #ef4444;
        --white: #ffffff;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--light);
        color: var(--dark);
    }

    /* Container */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        min-height: 70vh;
    }

    h2 {
        font-size: 1.8rem;
        margin-bottom: 30px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 15px;
    }

    /* GRID LAYOUT */
    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 30px;
    }

    /* CARD STYLING */
    .wish-card {
        background: var(--white);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        position: relative; /* For absolute positioning of remove btn */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .wish-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
    }

    /* IMAGE AREA */
    .wish-img {
        height: 220px;
        padding: 20px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .wish-img img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        mix-blend-mode: multiply;
    }

    /* REMOVE BUTTON (Trash Icon) */
    .btn-remove {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 35px;
        height: 35px;
        background: #fff;
        border-radius: 50%;
        color: var(--red);
        border: 1px solid #fee2e2;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 3px 6px rgba(0,0,0,0.05);
        transition: 0.3s;
    }

    .btn-remove:hover {
        background: var(--red);
        color: #fff;
        transform: scale(1.1);
    }

    /* BODY */
    .wish-body {
        padding: 20px;
        text-align: center;
    }

    .wish-body h4 {
        margin: 0 0 10px;
        font-size: 1rem;
        color: var(--dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .price {
        color: var(--primary);
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 15px;
    }

    /* VIEW BUTTON */
    .btn-view {
        display: block;
        width: 100%;
        padding: 10px;
        background: var(--dark);
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: 0.3s;
    }
    
    .btn-view:hover {
        background: var(--primary);
    }

    /* EMPTY STATE */
    .empty-box {
        text-align: center;
        padding: 50px;
        color: #94a3b8;
    }
    .empty-box i { font-size: 3rem; margin-bottom: 15px; opacity: 0.5; }
</style>

<div class="container">
    <h2>My Wishlist</h2>

    <?php if(mysqli_num_rows($result) > 0): ?>
        
        <div class="wishlist-grid">
            <?php while($row = mysqli_fetch_assoc($result)): 
                
                // Image Check
                $img = (!empty($row['image']) && file_exists("../uploads/products/".$row['image']))
                        ? "../uploads/products/".$row['image']
                        : "https://placehold.co/300x300?text=Product";
            ?>
                <div class="wish-card">
                    <div class="wish-img">
                        
                        <form method="post" action="" onsubmit="return confirm('Remove from wishlist?');">
                            <input type="hidden" name="remove_id" value="<?= $row['wid'] ?>">
                            <button type="submit" class="btn-remove" title="Remove">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>

                        <img src="<?= $img ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    </div>

                    <div class="wish-body">
                        <h4><?= htmlspecialchars($row['name']) ?></h4>
                        <div class="price">₹<?= number_format($row['selling_price']) ?></div>
                        
                        <a href="../product-details.php?id=<?= $row['id'] ?>" class="btn-view">
                            View Product
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    <?php else: ?>
        
        <div class="empty-box">
            <i class="far fa-heart"></i>
            <h3>Your wishlist is empty</h3>
            <p>Go back to the shop and find something you like!</p>
            <a href="../products.php" style="color: #2563eb; font-weight: 600;">Browse Products</a>
        </div>

    <?php endif; ?>

</div>

<?php include('../includes/footer.php'); ?>