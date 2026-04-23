<?php
session_start();
include("../config/db.php");

/* 🔐 Already logged-in admin → Dashboard */
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

/* 🛑 NO-CACHE HEADERS */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

$error = "";

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // Plain text password

    // Admin check
    $query = "SELECT * FROM admin WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $admin = mysqli_fetch_assoc($result);

        // Password match
        if ($password == $admin['password']) {

            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];

            // ✅ Redirect to dashboard
            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Invalid Password!";
        }

    } else {
        $error = "Admin Account not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | All In One Bazaar.com</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

body{
    background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.container{
    display:flex;
    background:#fff;
    width:900px;
    height:550px;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
}

.left-panel{
    flex:1;
    background:linear-gradient(135deg,#0ea5e9 0%,#2563eb 100%);
    color:#fff;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
    padding:40px;
}

.left-panel h2{
    font-size:2.2rem;
    font-weight:700;
    margin-bottom:15px;
}

.left-panel p{
    font-size:0.95rem;
    line-height:1.6;
    opacity:0.9;
    max-width:300px;
}

.right-panel{
    flex:1;
    display:flex;
    flex-direction:column;
    justify-content:center;
    padding:50px;
}

.login-header{
    margin-bottom:30px;
}

.login-header h3{
    font-size:1.8rem;
    color:#1e293b;
    font-weight:600;
}

.form-group{
    margin-bottom:20px;
}

input{
    width:100%;
    padding:12px 15px;
    border:1px solid #cbd5e1;
    border-radius:6px;
    font-size:14px;
    background:#f8fafc;
}

input:focus{
    border-color:#2563eb;
    background:#fff;
    outline:none;
    box-shadow:0 0 0 3px rgba(37,99,235,0.1);
}

.btn-login{
    width:100%;
    padding:12px;
    background:#2563eb;
    color:#fff;
    border:none;
    border-radius:6px;
    font-size:1rem;
    font-weight:600;
    cursor:pointer;
    margin-top:10px;
}

.btn-login:hover{
    background:#1d4ed8;
}

.error-msg{
    background:#fef2f2;
    color:#ef4444;
    padding:10px;
    border-radius:6px;
    margin-bottom:20px;
    border:1px solid #fecaca;
    text-align:center;
}

.footer-link{
    margin-top:20px;
    text-align:center;
    font-size:0.85rem;
    color:#64748b;
}

.footer-link a{
    color:#2563eb;
    text-decoration:none;
    font-weight:600;
}

.footer-link a:hover{
    text-decoration:underline;
}

@media(max-width:768px){
    .container{flex-direction:column;height:auto;width:95%;}
}
</style>
</head>

<body>

<div class="container">

    <div class="left-panel">
        <h2>Welcome Back!</h2>
        <p>Login to continue managing All In One Bazaar.com admin panel.</p>
    </div>

    <div class="right-panel">

        <div class="login-header">
            <h3>Admin Login</h3>
        </div>

        <?php if($error!=""): ?>
            <div class="error-msg"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" name="login" class="btn-login">Login</button>
        </form>

        <div class="footer-link">
            <a href="../index.php">← Back to Website</a>
        </div>

    </div>

</div>

</body>
</html>
