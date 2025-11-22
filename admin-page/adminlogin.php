<?php
$cn = mysqli_connect("localhost", "root", "123456789", "shivi-stylevana");
if (isset($_POST["login-btn"])) {
    
    $sql="select * from adminpanel where adminname='$_POST[loginname]' and pass='$_POST[loginpassword]'";
    $result = mysqli_query($cn,$sql);
   
    if (mysqli_num_rows($result)>0) {
        session_start();
   //     echo "<script type='text/javascript'> alert('testing'); </script>";
        $_SESSION["admin"] = $_POST['loginname'];
        header('Location:adminafter.php');
        
    }
    else{
        echo"<script type=text/javascript>alert('incorrect password')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Sign Up - Shivi's Stylevana</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-bg: #F0E4D3;
            --primary-text: #333;
            --accent-color: #D9A299;
            --secondary-accent: #DCC5B2;
            --dark-text: #222;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--primary-bg);
            color: var(--primary-text);
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .auth-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .auth-container h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5em;
            color: var(--dark-text);
            margin-bottom: 10px;
        }

        .auth-container p {
            font-size: 0.9em;
            color: #777;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent-color);
        }
        
        .form-actions {
            margin-top: 25px;
        }

        .form-actions .auth-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 50px;
            background-color: var(--accent-color);
            color: #fff;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .form-actions .auth-btn:hover {
            background-color: #a88476;
            transform: translateY(-2px);
        }

        .switch-form {
            margin-top: 20px;
        }

        .switch-form button {
            background: none;
            border: none;
            color: var(--accent-color);
            font-weight: 500;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .switch-form button:hover {
            text-decoration: underline;
        }

        #signup-form {
            display: none;
        }

/* Responsive styling */
        @media (max-width: 480px) {
            .auth-container {
                padding: 30px 20px;
            }

            .auth-container h2 {
                font-size: 2em;
            }
            
            .form-actions .auth-btn {
                padding: 12px;
                font-size: 1em;
            }
        }
    </style>
</head>
<body>

        
       <div class="auth-container"> 
        
        <!-- Login Form -->
        <div id="login-form">
            <h2>Welcome to Admin Pannel</h2>
        
            <form method="post">
                <div class="form-group">
                    <label>Admin name</label>
                    <input type="text" id="login-email" name="loginname" required>

                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="login-password" name="loginpassword" required>
                </div>
                <div class="form-actions">
                    <input type="submit" name="login-btn" class="auth-btn" value="User Login"> 
                </div>
            </form>
          
        </div>

           
    
</body>
</html>
