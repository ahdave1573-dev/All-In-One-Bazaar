<?php
// Start Session
session_start();

// ::::: FIX: Point to the 'config' folder :::::
if(file_exists("config/db.php")){
    include("config/db.php");
} else {
    // Fallback if file is still in root (just in case)
    include("db.php");
}

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if user exists
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];

        // Verify Password
        if (password_verify($password, $hashed_password)) {
            // Success: Set Session Variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['full_name']; // Ensure DB column matches
            $_SESSION['user_email'] = $row['email'];
            
            // Redirect to Home Page
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "Email not found! Please register first.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login | All In One Bazaar</title>
    <style>
        body{ margin:0; font-family:Arial, Helvetica, sans-serif; height:100vh; background:linear-gradient(120deg,#0b5ed7,#6610f2); display:flex; align-items:center; justify-content:center; }
        .login-wrapper{ width:800px; background:#fff; display:flex; border-radius:10px; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,0.2); min-height: 450px; }
        .brand{ width:45%; background:linear-gradient(160deg,#0b5ed7,#0dcaf0); color:#fff; padding:40px; display:flex; flex-direction:column; justify-content:center; }
        .brand h1{ font-size:32px; margin-bottom:10px; }
        .brand p{ font-size:15px; line-height:1.6; }
        .login-box{ width:55%; padding:40px; display: flex; flex-direction: column; justify-content: center; }
        .login-box h2{ margin-bottom:20px; color:#333; }
        .login-box input{ width:100%; padding:12px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px; box-sizing: border-box; }
        .login-box button{ width:100%; padding:12px; background:#0b5ed7; color:#fff; border:none; border-radius:5px; font-size:16px; cursor:pointer; transition: 0.3s; }
        .login-box button:hover{ background:#084298; }
        .login-box p{ margin-top:15px; text-align:center; }
        .login-box a{ color:#0b5ed7; text-decoration:none; font-weight:bold; }
        .error{ background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; border: 1px solid #fca5a5; }
        @media(max-width:768px){ .login-wrapper{ flex-direction:column; width:90%; } .brand,.login-box{ width:100%; } .brand{ padding: 30px; } }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="brand">
        <h1>Welcome Back!</h1>
        <p>To keep connected with us please login with your personal info and continue your digital shopping journey.</p>
    </div>
    <div class="login-box">
        <h2>User Login</h2>
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p>New to All In One Bazaar? <a href="register.php">Register Here</a></p>
    </div>
</div>
</body>
</html>