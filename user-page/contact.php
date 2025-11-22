<?php include("config.php");
session_start();
if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();
}
?>

<?php
    if (isset($_POST["logoutbtn"])){
        session_destroy();
        header("location:login.php");
        exit();
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Shivi's Stylevana</title>
    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
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
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            color: var(--primary-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
 .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

/* Header Styling */
 .main-header {
            background: #fff;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
.header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
.logo a {
    font-family: 'Playfair Display', serif;
    font-size: 2em;
    font-weight: 700;
    color: black;
    text-decoration: none;
}

.logo span {
    font-size: 0.8em;
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    display: block;
    margin-top: -5px;
    color: #888;
}
.img-logo{
    height: 5.5em;
    margin: right 0%;
}
.logo{
     display: flex;
    
}
.main-nav a::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: 0;
    transform: translateX(-50%) scaleX(0);
    width: 100%;
    height: 2px;
    background-color: peachpuff;
    transition: transform 0.3s ease;
}

.main-nav a:hover::after {
    transform: translateX(-50%) scaleX(1);
}
.categories{
    text-decoration: none;
    border: none;
      font-size:17px;
    font-weight:50px;
}

.header-center {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

.search-container {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

 .search-container input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 50px;
            font-family: 'Poppins', sans-serif;
        }

 .search-container button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--dark-text);
            font-size: 1.2em;
            cursor: pointer;
        }
.header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
 .main-nav {
            display: flex;
            gap: 20px;
        }
 .main-nav a {
            text-decoration: none;
            color: var(--dark-text);
            font-weight: 500;
            padding: 5px 10px;
            position: relative;
        }
.option{
    border:none;
}
.user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
.user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent-color);
        }

.user-profile p {
            font-weight: 600;
            color: var(--dark-text);
            margin: 0;
        }

.header-icons .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--dark-text);
            font-size: 1.2em;
        }


main {
            padding: 3rem 0;
            flex-grow: 1;
        }

h1, h2 {
            text-align: center;
            color: var(--dark-text);
        }

h1 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
 h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

main p {
            text-align: center;
            color: var(--primary-text);
            margin-bottom: 3rem;
        }

.grid-container {
            display: grid;
            gap: 3rem;
            align-items: start;
        }

.contact-form-section, .contact-info-section {
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
 .contact-info-section {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

 .form-group {
            margin-bottom: 1.5rem;
        }

.form-group label {
            display: block;
            color: var(--primary-text);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

.form-group input, .form-group textarea {
            width: 100%;
            padding: 0.5rem 1rem;
            border: 1px solid var(--secondary-accent);
            border-radius: 0.5rem;
            background-color: #fff;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

.form-group input:focus, .form-group textarea:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(217, 162, 153, 0.5);
        }
        
.form-group textarea {
            resize: vertical;
        }

 .submit-btn {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--accent-color);
            color: #fff;
            font-weight: 600;
            border-radius: 9999px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, opacity 0.3s;
        }

.submit-btn:hover {
            opacity: 0.8;
        }

.contact-details {
            margin-bottom: 2rem;
        }

.contact-detail {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
.contact-detail i {
            font-size: 1.5rem;
            color: var(--accent-color);
            margin-top: 0.25rem;
        }

 .contact-detail h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark-text);
        }

.contact-detail p {
            font-size: 1rem;
            color: var(--primary-text);
            text-align: left;
            margin: 0;
        }

.map-placeholder {
            margin-top: 2rem;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
.map-placeholder img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Media Queries for Responsiveness */
        @media (min-width: 768px) {
            h1 { font-size: 3rem; }
            h2 { font-size: 2rem; }
            .grid-container {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        @media (max-width: 767px) {
            .search-bar {
                display: none;
            }
            nav {
                display: none;
            }
            header .flex-container {
                flex-direction: column;
                gap: 1rem;
            }
        }
        
        @media (min-width: 1024px) {
            .user-info p {
                display: block;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
        <?php include("header.php");?>
    <!-- Main Content -->
    <main class="container">
        <h1>Contact Us</h1>
        <p>We would love to hear from you. Please fill out the form below or reach out to us using the contact details provided.</p>

        <div class="grid-container">
            <!-- Contact Form Section -->
            <div class="contact-form-section">
                <h2>Send us a message</h2>
                <form id="contactForm" method="post">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" id="email" name="gmail" placeholder="Enter your email address" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="Enter the subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Your Message</label>
                        <textarea id="message" name="message" rows="5" placeholder="Type your message here..." required></textarea>
                    </div>
                     <div class="form-group">
                        <label for="message">Feedback</label>
                        <textarea id="message" name="feedback" rows="5" placeholder="Please give your feedback here..."></textarea>
                    </div>
                    <button type="submit" class="submit-btn" name="messagebtn">Send Message</button>
                </form>
            </div>

            <!-- Contact Information Section -->
            <div class="contact-info-section">
                <div class="contact-details">
                    <h2>Our Details</h2>
                    <div class="contact-detail">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h3>Address</h3>
                            <p>stylevana shop</p>
                        </div>
                    </div>
                    <div class="contact-detail">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <h3>Phone</h3>
                            <p>6264204873</p>
                        </div>
                    </div>
                                          <div class="contact-detail">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h3>Email</h3>
                            <p>contact@shivivanastyle.com</p>
                        </div>
                    </div>
                </div>
                <!-- Map Placeholder -->
                <div class="map-placeholder">
                    <a href="https://www.google.com/maps/search/times+college+damoh/@23.8218415,79.4390416,21z?entry=ttu&g_ep=EgoyMDI1MDgwNC4wIKXMDSoASAFQAw%3D%3D"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQv-Qzlvd4UDKuF3TCQlEI08pv0wmJGqzAsWw&s"></a>
                </div>
                
            </div>
            
        </div>
   </main>
   
<?php include("footer.php") ?>

     <?php
      if(isset($_POST['messagebtn']))
         {
      $name = $_POST['name'];
      $gmail = $_POST['gmail'];
      $subject = $_POST['subject'];
      $message= $_POST['message'];
       $feedback= $_POST['feedback'];


      $str="insert into userfeedback (name,gmail,subject,message,feedback)values('$name','$gmail','$subject','$message','$feedback')";
      echo $str;
      mysqli_query($cn,$str);
         }
     ?>

        
</body>
</html>
