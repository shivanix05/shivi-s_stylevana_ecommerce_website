<?php 
require_once __DIR__ . "/config.php"; 
session_start();

// --- LOGIN LOGIC ---
if (isset($_POST["login-btn"])) {
    $gmail = mysqli_real_escape_string($cn, $_POST['gmail']);
    $password = mysqli_real_escape_string($cn, $_POST['password']);
    
    $sql = "SELECT * FROM userdetail WHERE gmail='$gmail' AND password='$password'";
    $result = mysqli_query($cn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION["user"] = $gmail;
        header('Location:after-login.php');
        exit();
    } else {
        echo "<script>alert('Incorrect password or Email')</script>";
    }
}

// --- SIGNUP LOGIC ---
if(isset($_POST["singupbtn"])) {
    $name = mysqli_real_escape_string($cn, $_POST["name"]);
    $address = mysqli_real_escape_string($cn, $_POST["address"]);
    $mobilenumber = mysqli_real_escape_string($cn, $_POST["mobilenumber"]);
    $state = mysqli_real_escape_string($cn, $_POST["state"]);
    $city = mysqli_real_escape_string($cn, $_POST["city"]);
    $pincode = mysqli_real_escape_string($cn, $_POST["pincode"]);
    $age = (int)$_POST["age"];
    $gmail = mysqli_real_escape_string($cn, $_POST["gmail"]);
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];

    // Profile Photo Upload Logic
    $filename = "";
    if(!empty($_FILES["userphoto"]["name"])) {
        $filename = time() . "_" . $_FILES["userphoto"]["name"];
        $tempname = $_FILES["userphoto"]["tmp_name"];
        if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
        move_uploaded_file($tempname, "uploads/" . $filename);
    }

    if ($password != $confirmpassword) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        $check_email = mysqli_query($cn, "SELECT * FROM userdetail WHERE gmail='$gmail'");
        if(mysqli_num_rows($check_email) > 0) {
            echo "<script>alert('Email already registered!');</script>";
        } else {
            // Aapke DB screenshot ke columns ke hisaab se
            $str = "INSERT INTO userdetail (name, address, mobilenumber, state, city, pincode, age, gmail, password, confirmpassword, userphoto) 
                    VALUES ('$name', '$address', '$mobilenumber', '$state', '$city', '$pincode', $age, '$gmail', '$password', '$confirmpassword', '$filename')";
            
            if(mysqli_query($cn, $str)) {
                $_SESSION["user"] = $gmail; 
                echo "<script>alert('Registration Successful! Welcome to Shivi\'s Stylevana.'); window.location.href='after-login.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error: " . mysqli_error($cn) . "');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Sign Up - Shivi's Stylevana</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --primary-bg: #F8F5F2; --accent-color: #D9A299; --dark-text: #282c3f; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--primary-bg); display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        
        .brand-logo { text-align: center; margin-bottom: 20px; }
        .brand-logo img { width: 60px; margin-bottom: 5px; }
        .brand-logo h1 { font-family: 'Playfair Display', serif; font-size: 32px; color: var(--dark-text); }
        .brand-logo span { color: var(--accent-color); }

        .auth-container { background: #fff; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); padding: 35px; width: 100%; max-width: 450px; position: relative; }
        h2 { font-family: 'Playfair Display', serif; margin-bottom: 20px; text-align: center; }
        
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; font-size: 11px; font-weight: 600; color: #666; margin-bottom: 5px; text-transform: uppercase; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; outline: none; }
        
        .auth-btn { width: 100%; padding: 12px; border: none; border-radius: 25px; background: var(--dark-text); color: #fff; font-weight: 600; cursor: pointer; margin-top: 15px; }
        .auth-btn:hover { background: var(--accent-color); }
        
        .switch-form { margin-top: 20px; text-align: center; border-top: 1px solid #eee; padding-top: 15px; }
        .switch-form button { background: none; border: none; color: var(--accent-color); font-weight: bold; cursor: pointer; text-decoration: underline; }
        
        /* Layout Fixes */
        #signup-form { display: none; }
        #login-form { display: block; }
        .scroll-area { max-height: 380px; overflow-y: auto; padding-right: 10px; }
        .scroll-area::-webkit-scrollbar { width: 4px; }
        .scroll-area::-webkit-scrollbar-thumb { background: var(--accent-color); border-radius: 10px; }
    </style>
</head>
<body>

    <div class="brand-logo">
        <img src="logo.png" alt="Logo">
        <h1>Shivi's <span>Stylevana</span></h1>
    </div>

    <div class="auth-container">
        <div id="login-form">
            <h2>Login</h2>
            <form method="post" action="">
                <div class="form-group"><label>Gmail</label><input type="email" name="gmail" required></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
                <button type="submit" name="login-btn" class="auth-btn">Sign In</button>
            </form>
            <div class="switch-form"><p>New user? <button type="button" onclick="toggleForms('signup')">Create Account</button></p></div>
        </div>

        <div id="signup-form">
            <h2>Sign Up</h2>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="scroll-area">
                    
                    <div class="form-group"><label>Full Name</label><input type="text" name="name" required></div>
                    <div class="form-group"><label>Mobile</label><input type="text" name="mobilenumber" maxlength="10" required></div>
                    <div class="form-group"><label>Profile Photo</label><input type="file" name="userphoto" accept="image/*"></div>
                    <div class="form-group"><label>Address</label><input type="text" name="address"></div>

                    <div class="form-group"><label>City</label><input type="text" name="city"></div>
                    <div class="form-group"><label>State</label><input type="text" name="state"></div>
                    <div class="form-group"><label>Pin Code</label><input type="text" name="pincode"></div>
                    <div class="form-group"><label>Age</label><input type="number" name="age"></div>
                    <div class="form-group"><label>Gmail</label><input type="email" name="gmail" required></div>
                    <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
                    <div class="form-group"><label>Confirm Password</label><input type="password" name="confirmpassword" required></div>
                </div>
                <button type="submit" name="singupbtn" class="auth-btn">Register</button>
            </form>
            <div class="switch-form"><p>Have an account? <button type="button" onclick="toggleForms('login')">Login</button></p></div>
        </div>
    </div>

    <script>
        function toggleForms(target) {
            const login = document.getElementById('login-form');
            const signup = document.getElementById('signup-form');
            
            if (target === 'signup') {
                login.style.display = 'none';
                signup.style.display = 'block';
            } else {
                login.style.display = 'block';
                signup.style.display = 'none';
            }
        }
    </script>
</body>
</html>
