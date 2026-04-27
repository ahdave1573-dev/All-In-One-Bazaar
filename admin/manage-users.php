<?php
require_once('includes/auth_check.php');
include('../config/db.php');

// ===== FETCH USERS =====
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users | All In One Bazaar</title>
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

    /* Table Styling */
    .table-wrapper {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead tr {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }
    th {
        padding: 15px;
        text-align: left;
        color: #475569;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
    }
    td {
        padding: 15px;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }

    /* Action Buttons */
    .action-btn {
        padding: 8px;
        border-radius: 6px;
        text-decoration: none;
        color: #fff;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        transition: 0.2s;
    }
    .delete { background: #ef4444; }
    .delete:hover { background: #dc2626; }

</style>
</head>

<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        
        <div class="header-bar">
            <h2>👥 Registered Users</h2>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Joined On</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                <?php if(mysqli_num_rows($users) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($users)): ?>
                    <tr>
                        <td style="font-weight:bold; color:#64748b;"><?= $row['id']; ?></td>
                        
                        <td style="font-weight:600; color:#0f172a;">
                            <?= htmlspecialchars($row['full_name']); ?>
                        </td>
                        
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        
                        <td><?= $row['phone'] ?: '<span style="color:#94a3b8;">-</span>'; ?></td>
                        
                        <td>
                            <?php 
                                if($row['address']){
                                    echo substr($row['address'], 0, 20) . '...';
                                } else {
                                    echo '<span style="color:#94a3b8;">-</span>';
                                }
                            ?>
                        </td>
                        
                        <td><?= date("d M Y", strtotime($row['created_at'])); ?></td>
                        
                        <td>
                            <a href="delete-user.php?id=<?= $row['id']; ?>" 
                               class="action-btn delete"
                               onclick="return confirm('Are you sure you want to delete this user?')"
                               title="Delete User">
                               <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:30px; color:#64748b;">
                            No users found in the database.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>