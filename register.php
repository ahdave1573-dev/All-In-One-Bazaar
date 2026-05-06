<?php
// ::::: 1. CONNECT DATABASE :::::
if(file_exists("config/db.php")){
    include("config/db.php");
} else {
    include("db.php"); 
}

$error = "";

// ::::: 2. HANDLE REGISTRATION :::::
if (isset($_POST['register'])) {
    // Get Inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // NEW: Get Phone and Address
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Check Email
    $check_query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        $error = "Email is already registered! Please Login.";
    } else {
        // Hash Password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // NEW: Updated Insert Query including phone and address
        $sql = "INSERT INTO users (full_name, email, password, phone, address) 
                VALUES ('$name', '$email', '$hashed_password', '$phone', '$address')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Registration Successful! Please Login.'); window.location.href='login.php';</script>";
            exit;
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration | All In One Bazaar</title>
    <style>
        /* ::::: CSS STYLES ::::: */
        body{
            margin:0; font-family:Arial, Helvetica, sans-serif;
            min-height:100vh; /* Changed to min-height for scrolling */
            background:linear-gradient(120deg,#0b5ed7,#6610f2);
            display:flex; align-items:center; justify-content:center;
            padding: 20px; /* Added padding for small screens */
        }
        .register-wrapper{
            width:850px; /* Slightly wider */
            background:#fff; display:flex;
            border-radius:10px; overflow:hidden;
            box-shadow:0 20px 40px rgba(0,0,0,0.2);
            /* Removed fixed height to allow growing */
        }
        .brand{
            width:40%; background:linear-gradient(160deg,#0b5ed7,#0dcaf0);
            color:#fff; padding:40px;
            display:flex; flex-direction:column; justify-content:center;
        }
        .brand h1{ font-size:32px; margin-bottom:10px; }
        .brand p{ font-size:15px; line-height:1.6; }
        
        .register-box{ width:60%; padding:40px; display: flex; flex-direction: column; justify-content: center; }
        .register-box h2{ margin-bottom:20px; color:#333; }
        
        .register-box input, .register-box textarea {
            width:100%; padding:12px; margin-bottom:15px;
            border:1px solid #ccc; border-radius:5px; box-sizing: border-box;
            font-family: inherit;
        }
        
        .register-box button{
            width:100%; padding:12px; background:#0b5ed7;
            color:#fff; border:none; border-radius:5px;
            font-size:16px; cursor:pointer; transition: 0.3s;
        }
        .register-box button:hover{ background:#084298; }
        
        .register-box p{ margin-top:15px; text-align:center; }
        .register-box a{ color:#0b5ed7; text-decoration:none; font-weight:bold; }
        .error{ background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; border: 1px solid #fca5a5; }

        @media(max-width:768px){
            .register-wrapper{ flex-direction:column; width:100%; max-width: 450px; }
            .brand,.register-box{ width:100%; }
            .brand{ padding: 30px; text-align: center; }
        }
    </style>
</head>
<body>

<div class="register-wrapper">

    <div class="brand">
        <h1>Join Us!</h1>
        <p>
            Create your account to explore and shop the best electronics 
            with secure access and easy order management.
        </p>
    </div>

    <div class="register-box">
        <h2>Create Account</h2>

        <?php if($error != "") echo "<div class='error'>$error</div>"; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            
            <input type="email" name="email" placeholder="Email Address" required>
            
            <input type="text" name="phone" placeholder="Phone Number" required pattern="[0-9]{10}" title="Enter valid 10 digit number">
            
            <textarea name="address" rows="2" placeholder="Delivery Address" required></textarea>

            <input type="password" name="password" placeholder="Create Password" required>
            
            <button type="submit" name="register">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login Here</a></p>
    </div>

</div>

</body>
</html>