<?php include("config.php"); 

if (isset($_POST["login-btn"])) {
    
    $sql="select * from userdetail where gmail='$_POST[gmail]' and password='$_POST[password]'";
    $result = mysqli_query($cn,$sql);
    echo"$sql";
    if (mysqli_num_rows($result)>0) {
        session_start();
        echo "<script type='text/javascript'> alert('testing'); </script>";
        $_SESSION["user"] = $_POST['gmail'];
        header('Location:after-login.php');
        
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
        
        <div id="login-form">
            <h2>Welcome Back!</h2>
            <p>Log in to continue your journey with us.</p>
            <form method="post">
                <div class="form-group">
                    <label>Gmail</label>
                    <input type="text" id="login-email" name="gmail" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                <div class="form-actions">
                    <input type="submit" name="login-btn" class="auth-btn" value="login">
                </div>
            </form>
            <div class="switch-form">
                <p>Don't have an account? <button onclick="toggleForms()">Sign Up</button></p>
            </div>
        </div>

            <div id="signup-form">
            <h2>Create an Account</h2>
            <p>Join our community and elevate your style!</p>
            <form method="post">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" id="signup-name" name="name" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" id="signup-address" name="address" required>
                </div>
                <div class="form-group">
                    <label >Mobile Number</label>
                    <input type="tel" id="signup-mobile" name="mobilenumber" required>
                </div>
                   <div class="form-group">
                    <label >State</label>
                    <input type="text" id="signup-city" name="state" required>
                </div>
                <div class="form-group">
                    <label >City</label>
                    <input type="text" id="signup-city" name="city" required>
                </div>
                <div class="form-group">
                    <label>Pin Code</label>
                    <input type="text" id="signup-pincode" name="pincode" required>
                </div>
                <div class="form-group">
                    <label>Age</label>
                    <input type="number" id="signup-age" name="age" required>
                </div>
                <div class="form-group">
                    <label>Gmail</label>
                    <input type="email" id="signup-email" name="gmail" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="signup-password" name="password" required>
                </div>
                <div class="form-group">
                    <label >Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirmpassword" required>
                </div>
                <div class="form-actions">
                    <button type="submit" name="singupbtn" class="auth-btn">Sign Up</button>
                </div>
            </form>
            <div class="switch-form">
                <p>Already have an account? <button onclick="toggleForms()">Login</button></p>
            </div>
        </div> 
    </div>
    <script>

        // JavaScript to toggle between the login and sign-up forms
        const loginForm = document.getElementById('login-form');
        const signupForm = document.getElementById('signup-form');

        function toggleForms() {
            if (loginForm.style.display === 'none') {
                // Show login form, hide sign up form
                loginForm.style.display = 'block';
                signupForm.style.display = 'none';
            } else {
                // Show sign up form, hide login form
                loginForm.style.display = 'none';
                signupForm.style.display = 'block';
            }
        }
    </script> 
    
    
    <?php
      if(isset($_POST["singupbtn"]))
           {
      $name = $_POST["name"];
      $address = $_POST["address"];
      $mobilenumber = $_POST["mobilenumber"];
    $state = $_POST["state"];
      $city = $_POST["city"];
      $pincode = $_POST["pincode"];
      $age = $_POST["age"];
      $gmail = $_POST["gmail"];
      $password = $_POST["password"];
      $confirmpassword = $_POST["confirmpassword"];

      
      if ($password == $confirmpassword) {
         
          $str="insert into userdetail (name,address,mobilenumber,state,city,pincode,age,gmail,password,confirmpassword)values('$name','$address','$mobilenumber','$state','$city','$pincode',$age,'$gmail','$password','$confirmpassword')";
          mysqli_query($cn,$str);
          echo"<script type=text/javascript>alert('Registration Successful!')</script>";
      } else {
          
          echo"<script type=text/javascript>alert('Passwords do not match.')</script>";
      }
  
           }
      ?>

</body>
</html>