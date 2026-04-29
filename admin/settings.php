<?php
require_once('includes/auth_check.php');
include("../config/db.php");

/* =========================
   Fetch Existing Settings
========================= */
$settingsQuery = mysqli_query($conn, "SELECT * FROM settings LIMIT 1");
$settings = mysqli_fetch_assoc($settingsQuery);

/* =========================
   Save / Update Settings
========================= */
if(isset($_POST['save_settings']))
{
    $site_name = $_POST['site_name'];
    $site_email = $_POST['site_email'];
    $site_phone = $_POST['site_phone'];
    $site_address = $_POST['site_address'];

    if($settings){
        // Update
        $update = "
        UPDATE settings SET
            site_name='$site_name',
            site_email='$site_email',
            site_phone='$site_phone',
            site_address='$site_address'
        WHERE id='".$settings['id']."'
        ";
        mysqli_query($conn, $update);
        $_SESSION['success'] = "Site settings updated successfully";
    }else{
        // Insert
        $insert = "
        INSERT INTO settings (site_name, site_email, site_phone, site_address)
        VALUES ('$site_name','$site_email','$site_phone','$site_address')
        ";
        mysqli_query($conn, $insert);
        $_SESSION['success'] = "Site settings saved successfully";
    }

    header("Location: settings.php"); // Updated redirect to same page name if needed
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Site Settings | All In One Bazaar</title>
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

    /* Form Container */
    .form-box {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        max-width: 800px;
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
    input[type="email"],
    textarea {
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
    input:focus, textarea:focus {
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

    /* Save Button */
    .save-btn {
        background: #2563eb;
        color: #fff;
        padding: 14px 30px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .save-btn:hover {
        background: #1d4ed8;
    }

    /* Success Message */
    .success-msg {
        background: #dcfce7;
        color: #166534;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #bbf7d0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>
</head>

<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        
        <div class="header-bar">
            <h2>⚙ Website Settings</h2>
        </div>

        <?php
        if(isset($_SESSION['success'])){
            echo "<div class='success-msg'><i class='fas fa-check-circle'></i> ".$_SESSION['success']."</div>";
            unset($_SESSION['success']);
        }
        ?>

        <div class="form-box">
            <form method="POST">

                <label>Site Name</label>
                <input type="text" name="site_name" 
                       value="<?= $settings['site_name'] ?? ''; ?>" 
                       placeholder="Enter website name">

                <div class="row">
                    <div class="col-half">
                        <label>Site Email</label>
                        <input type="email" name="site_email" 
                               value="<?= $settings['site_email'] ?? ''; ?>" 
                               placeholder="admin@example.com">
                    </div>
                    <div class="col-half">
                        <label>Site Phone</label>
                        <input type="text" name="site_phone" 
                               value="<?= $settings['site_phone'] ?? ''; ?>" 
                               placeholder="+91 9876543210">
                    </div>
                </div>

                <label>Site Address</label>
                <textarea name="site_address" rows="4" 
                          placeholder="Enter office address..."><?= $settings['site_address'] ?? ''; ?></textarea>

                <button type="submit" name="save_settings" class="save-btn">
                    <i class="fas fa-save"></i> Save Settings
                </button>

            </form>
        </div>
    </div>

</body>
</html>