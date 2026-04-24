<?php
// ::::: 1. START SESSION FIRST (Most Important Fix) :::::
session_start();

// ::::: 2. CONNECT DATABASE :::::
include('../config/db.php');

// ::::: 3. CHECK LOGIN STATUS :::::
// Have session start che, etle aa check barabar kaam karse
if(!isset($_SESSION['user_id'])){
    // Jo login nathi, to login page par moklo
    header('location: ../login.php');
    exit();
}

// ::::: 4. HANDLE FORM SUBMISSION :::::
$msg = "";
if(isset($_POST['update_profile'])){
    $id = $_SESSION['user_id'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Update Query
    $sql = "UPDATE users SET 
            full_name = '$full_name',
            phone = '$phone',
            address = '$address'
            WHERE id = $id";

    $res = mysqli_query($conn, $sql);

    if($res){
        $msg = "<div class='alert success'><i class='fas fa-check-circle'></i> Profile Updated Successfully!</div>";
        // Session variable update karo jethi header ma naam badlai jay
        $_SESSION['user_name'] = $full_name;
    } else {
        $msg = "<div class='alert error'><i class='fas fa-exclamation-circle'></i> Failed to Update Profile.</div>";
    }
}

// ::::: 5. FETCH USER DATA :::::
$id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id=$id";
$res = mysqli_query($conn, $sql);

if(mysqli_num_rows($res) > 0){
    $row = mysqli_fetch_assoc($res);
    $full_name = $row['full_name'];
    $email = $row['email'];
    // Errors prevent karva mate checks
    $phone = isset($row['phone']) ? $row['phone'] : "";
    $address = isset($row['address']) ? $row['address'] : "";
} else {
    // Jo user ID database ma na male (rare case)
    header('location: ../login.php');
    exit();
}

// ::::: 6. INCLUDE HEADER :::::
include('../includes/header.php');
?>

<style>
    /* PAGE LAYOUT */
    .profile-container {
        max-width: 900px;
        margin: 50px auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 40px;
    }

    /* SIDEBAR CARD */
    .profile-sidebar {
        background: var(--white);
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        border: 1px solid #e2e8f0;
        height: fit-content;
    }
    .profile-img-circle {
        width: 100px; height: 100px;
        background: #eff6ff;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 15px;
        font-size: 3rem; color: var(--primary);
    }
    .user-title { font-size: 1.2rem; font-weight: 700; color: var(--dark); }
    .user-email { color: var(--gray); font-size: 0.9rem; margin-bottom: 20px; }
    
    .sidebar-menu a {
        display: block; padding: 12px; border-radius: 8px;
        color: var(--gray); margin-bottom: 5px; text-align: left; transition: 0.3s;
    }
    .sidebar-menu a:hover, .sidebar-menu a.active {
        background: #eff6ff; color: var(--primary); font-weight: 600;
    }
    .sidebar-menu i { width: 25px; }

    /* FORM CARD */
    .profile-content {
        background: var(--white); padding: 40px;
        border-radius: 15px; border: 1px solid #e2e8f0;
    }
    .content-header { border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 30px; }
    .content-header h2 { font-size: 1.5rem; color: var(--dark); }

    /* FORM STYLES */
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; }
    .form-input {
        width: 100%; padding: 12px; border: 1px solid #cbd5e1;
        border-radius: 8px; font-size: 1rem; outline: none; transition: 0.3s;
    }
    .form-input:focus { border-color: var(--primary); }
    .form-input[readonly] { background-color: #f1f5f9; cursor: not-allowed; }

    .btn-save {
        background: var(--primary); color: white; border: none; padding: 12px 30px;
        border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s;
    }
    .btn-save:hover { background: var(--primary-dark); }

    /* ALERTS */
    .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.95rem; }
    .success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

    @media (max-width: 768px) { .profile-container { grid-template-columns: 1fr; } }
</style>

<div class="profile-container">

    <div class="profile-sidebar">
        <div class="profile-img-circle"><i class="fas fa-user"></i></div>
        <div class="user-title"><?php echo $full_name; ?></div>
        <div class="user-email"><?php echo $email; ?></div>
        
        <div class="sidebar-menu">
            <a href="dashboard.php"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="profile.php" class="active"><i class="fas fa-user-edit"></i> Edit Profile</a>
            <a href="orders.php"><i class="fas fa-box"></i> My Orders</a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i> My Cart</a>
            <a href="logout.php" style="color: #ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="profile-content">
        <div class="content-header"><h2>Edit Profile</h2></div>
        
        <?php echo $msg; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-input" value="<?php echo $full_name; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-input" value="<?php echo $email; ?>" readonly>
                <small style="color: var(--gray);">Email cannot be changed.</small>
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-input" value="<?php echo $phone; ?>" placeholder="Enter your phone number">
            </div>

            <div class="form-group">
                <label class="form-label">Shipping Address</label>
                <textarea name="address" class="form-input" rows="3" placeholder="Enter full address for delivery"><?php echo $address; ?></textarea>
            </div>

            <button type="submit" name="update_profile" class="btn-save">Save Changes</button>
        </form>
    </div>

</div>

<?php
include('../includes/footer.php');
?>