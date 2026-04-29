<?php
$cn = mysqli_connect("localhost", "root", "root123", "shivi-stylevana");
if (isset($_POST["login-btn"])) {
    
    $sql="select * from adminpanel where adminname='$_POST[loginname]' and pass='$_POST[loginpassword]'";
    $result = mysqli_query($cn,$sql);
   
    if (mysqli_num_rows($result)>0) {
        session_start();
        $_SESSION["admin"] = $_POST['loginname'];
        header('Location:admindashboard.php');
        exit(); // Good practice to exit after redirect
    }
    else{
        echo"<script type='text/javascript'>alert('incorrect password')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Shivi's Stylevana</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="adminloginstyle.css" />
</head>
<body>

    <div class="login-card">
        <h2>Admin Portal</h2>
        
        <form method="post">
            <div class="form-group">
                <label>Admin Username</label>
                <input type="text" name="loginname" placeholder="Enter username" required>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="loginpassword" placeholder="Enter password" required>
            </div>
            
            <input type="submit" name="login-btn" class="login-btn" value="Login to Dashboard">
        </form>

        <p class="footer-text">Shivi's Stylevana &copy; 2026</p>
    </div>

</body>
</html>