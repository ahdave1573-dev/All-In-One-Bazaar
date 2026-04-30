<?php
// ::::: 1. SECURITY & CONFIGURATION :::::
session_start();

// Include Config & DB
include('../config/constants.php');
include('../config/db.php');

// Include Auth (Redirects to login if not logged in)
include('../config/auth.php');

// ::::: 2. PASSWORD UPDATE LOGIC :::::
$msg = "";
$msg_type = ""; // success OR danger

if(isset($_POST['change_pass_btn'])) {
    
    // User ID Session se lo
    $user_id = $_SESSION['user_id'];
    
    // Inputs lo aur sanitize karo
    $current_pass = mysqli_real_escape_string($conn, $_POST['current_pass']);
    $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
    $confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_pass']);

    // 1. Database se purana password nikalo
    $sql_check = "SELECT password FROM users WHERE id = '$user_id' LIMIT 1";
    $res_check = mysqli_query($conn, $sql_check);
    
    if(mysqli_num_rows($res_check) > 0) {
        $row = mysqli_fetch_assoc($res_check);
        $db_pass_hash = $row['password'];

        // 2. Check karo ki Current Password sahi hai ya nahi
        if(password_verify($current_pass, $db_pass_hash)) {
            
            // 3. Check karo ki New Password aur Confirm Password match ho rahe hain
            if($new_pass === $confirm_pass) {
                
                // 4. Naye Password ko Encrypt (Hash) karo
                $new_pass_hash = password_hash($new_pass, PASSWORD_DEFAULT);
                
                // 5. Database Update karo
                $sql_update = "UPDATE users SET password = '$new_pass_hash' WHERE id = '$user_id'";
                $res_update = mysqli_query($conn, $sql_update);

                if($res_update) {
                    $msg = "Password changed successfully!";
                    $msg_type = "success";
                } else {
                    $msg = "Something went wrong. Please try again.";
                    $msg_type = "danger";
                }

            } else {
                $msg = "New Password and Confirm Password do not match.";
                $msg_type = "danger";
            }
        } else {
            $msg = "Incorrect Current Password.";
            $msg_type = "danger";
        }
    } else {
        $msg = "User not found.";
        $msg_type = "danger";
    }
}

// ::::: 3. INCLUDE HEADER :::::
include('../includes/header.php');
?>

<style>
    /* Reuse Dashboard Layout */
    .dashboard-wrapper { display: flex; min-height: 80vh; background: #f8fafc; }
    
    /* Sidebar Styling (Agar include nahi kiya to yahan styling hai) */
    .sidebar-area { width: 280px; flex-shrink: 0; background: #fff; border-right: 1px solid #e2e8f0; }
    .main-content { flex: 1; padding: 30px; }

    /* Form Card Styling */
    .form-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        max-width: 600px;
        margin: 0 auto; /* Center the form */
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
    
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark); }
    
    .form-control {
        width: 100%; padding: 12px;
        border: 1px solid #cbd5e1; border-radius: 8px;
        transition: 0.3s;
    }
    .form-control:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
    
    .btn-submit {
        background: var(--primary); color: white;
        padding: 12px 25px; border: none; border-radius: 8px;
        font-weight: 600; cursor: pointer; width: 100%;
        transition: 0.3s;
    }
    .btn-submit:hover { background: #1d4ed8; }

    /* Alert Styling */
    .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.95rem; }
    .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .alert-danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    /* Sidebar Links Styling (Internal) */
    .sidebar-menu { padding: 20px; }
    .sidebar-menu a {
        display: block; padding: 12px 15px;
        color: var(--gray); font-weight: 500;
        text-decoration: none; border-radius: 8px;
        margin-bottom: 5px; transition: 0.3s;
    }
    .sidebar-menu a:hover { background: #f1f5f9; color: var(--primary); }
    .sidebar-menu a.active { background: var(--primary); color: white; }

    @media (max-width: 900px) {
        .dashboard-wrapper { flex-direction: column; }
        .sidebar-area { width: 100%; }
    }
</style>

<div class="dashboard-wrapper">
    
    <div class="sidebar-area">
        <?php 
        // Agar aapke paas sidebar.php alag file hai to ye uncomment karein:
        // include('../includes/sidebar.php'); 
        
        // Agar file nahi hai, to ye direct code use karein:
        ?>
        <div class="sidebar-menu">
            <h4 style="padding-left:15px; margin-bottom:20px; color:var(--dark);">My Account</h4>
            <a href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="profile.php"><i class="fas fa-user me-2"></i> My Profile</a>
            <a href="orders.php"><i class="fas fa-box me-2"></i> My Orders</a>
            <a href="cart.php"><i class="fas fa-shopping-cart me-2"></i> My Cart</a>
            <a href="change-password.php" class="active"><i class="fas fa-lock me-2"></i> Change Password</a>
            <a href="logout.php" style="color: #ef4444;"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        
        <h2 style="margin-bottom: 25px; color: var(--dark);">Security Settings</h2>

        <div class="form-card">
            <h3 style="margin-bottom: 20px; font-size: 1.3rem;">Change Password</h3>

            <?php if($msg != ""): ?>
                <div class="alert alert-<?= $msg_type; ?>">
                    <?= $msg; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                
                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_pass" class="form-control" placeholder="Enter current password" required>
                </div>

                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_pass" class="form-control" placeholder="Enter new password" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_pass" class="form-control" placeholder="Re-type new password" required>
                </div>

                <button type="submit" name="change_pass_btn" class="btn-submit">Update Password</button>

            </form>
        </div>

    </div>
</div>

<?php 
// ::::: 4. INCLUDE FOOTER :::::
include('../includes/footer.php'); 
?>