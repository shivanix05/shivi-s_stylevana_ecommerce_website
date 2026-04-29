<?php 
require_once __DIR__ . "/config.php"; 
session_start();

// Agar user login nahi hai to login page par bhej do
if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();    
}

$user_email = $_SESSION['user']; 

// --- FORM SUBMISSION LOGIC ---
if(isset($_POST['messagebtn'])) {
    // SQL Injection se bachne ke liye escaping
    $name = mysqli_real_escape_string($cn, $_POST['name']);
    $gmail = mysqli_real_escape_string($cn, $_POST['gmail']);
    $subject = mysqli_real_escape_string($cn, $_POST['subject']);
    $message = mysqli_real_escape_string($cn, $_POST['message']);
    $feedback = mysqli_real_escape_string($cn, $_POST['feedback']);

    // INSERT query aapki 'userfeedback' table ke liye
    // admin_reply ko shuruat mein empty choda hai
    $str = "INSERT INTO userfeedback (name, gmail, subject, message, feedback, admin_reply) VALUES ('$name', '$gmail', '$subject', '$message', '$feedback', '')";
    
    if(mysqli_query($cn, $str)) {
        echo "<script>alert('Message Sent Successfully! Check the History section below for updates.'); window.location.href='contact.php';</script>";
    } else {
        echo "<script>alert('Something went wrong: " . mysqli_error($cn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Shivi's Stylevana</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Playfair+Display:wght@700&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            --primary-bg: #FDF8F5;
            --accent-color: #D9A299;
            --dark-text: #222;
            --soft-pink: #fff0f3;
            --success-green: #03a685;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { 
            font-family: 'Quicksand', sans-serif; 
            background-color: var(--primary-bg); 
            color: #333; 
        }

        .container { max-width: 1100px; margin: 0 auto; padding: 40px 20px; }
        
        h1 { font-family: 'Playfair Display', serif; font-size: 3rem; text-align: center; margin-bottom: 10px; color: var(--dark-text); }
        .sub-text { text-align: center; margin-bottom: 50px; color: #777; font-size: 1.1rem; }

        .grid-container { display: grid; grid-template-columns: 1.6fr 1fr; gap: 40px; }
        
        .contact-form-section, .contact-info-section, .history-section {
            background: #fff; padding: 35px; border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 700; font-size: 14px; color: var(--dark-text); }
        .form-group input, .form-group textarea {
            width: 100%; padding: 12px 18px; border: 1px solid #eee;
            border-radius: 15px; background: #fafafa; font-family: inherit; transition: 0.3s;
        }
        .form-group input:focus, .form-group textarea:focus {
            outline: none; border-color: var(--accent-color); background: #fff; box-shadow: 0 0 0 4px var(--soft-pink);
        }

        .submit-btn {
            width: 100%; padding: 16px; background: var(--accent-color);
            color: white; border: none; border-radius: 50px;
            font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.3s;
            box-shadow: 0 8px 20px rgba(217,162,153,0.3);
        }
        .submit-btn:hover { transform: translateY(-3px); background: #c98e84; }

        .contact-detail { display: flex; gap: 18px; margin-bottom: 30px; }
        .contact-detail i { color: var(--accent-color); font-size: 1.4rem; margin-top: 5px; }
        .contact-detail h3 { font-size: 17px; margin-bottom: 5px; color: var(--dark-text); }
        .contact-detail p { font-size: 15px; color: #666; line-height: 1.5; }

        .map-placeholder { border-radius: 20px; overflow: hidden; margin-top: 25px; border: 1px solid #eee; }

        .history-section { margin-top: 50px; grid-column: span 2; }
        .history-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 30px; border-bottom: 2px solid var(--soft-pink); padding-bottom: 10px; }
        
        .query-card { 
            border: 1px solid #f0f0f0; background: #fdfcfb; 
            padding: 20px; margin-bottom: 20px; border-radius: 20px;
            transition: 0.3s;
        }
        .query-card:hover { border-color: var(--accent-color); }

        .status-badge { font-size: 11px; padding: 5px 12px; border-radius: 50px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; }
        .pending { background: #fff3cd; color: #856404; }
        .replied { background: #d4edda; color: #155724; }

        .admin-response { 
            background: #f0fdf9; padding: 20px; border-radius: 15px; 
            margin-top: 15px; border: 1px dashed var(--success-green); 
        }

        @media (max-width: 850px) { 
            .grid-container { grid-template-columns: 1fr; }
            .history-section { grid-column: span 1; }
        }
    </style>
</head>
<body>

    <?php include("header.php"); ?>

    <main class="container">
        <h1>Contact Us</h1>
        <p class="sub-text">Have a question or need help with your order? We're here for you!</p>

        <div class="grid-container">
            <div class="contact-form-section">
                <h2 style="margin-bottom: 25px; font-size: 22px;">Send a Message</h2>
                <form method="post">
                    <div class="form-group">
                        <label>Your Name</label>
                        <input type="text" name="name" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group">
                        <label>Your Email</label>
                        <input type="email" name="gmail" value="<?php echo $user_email; ?>" readonly style="color: #999; cursor: not-allowed;">
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" placeholder="What is this regarding?" required>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" rows="4" placeholder="Write your query here..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Feedback (Optional)</label>
                        <textarea name="feedback" rows="2" placeholder="Anything else you'd like to share?"></textarea>
                    </div>
                    <button type="submit" class="submit-btn" name="messagebtn">SEND MESSAGE 💌</button>
                </form>
            </div>

            <div class="contact-info-section">
                <h2 style="margin-bottom: 25px; font-size: 22px;">Our Details</h2>
                <div class="contact-detail">
                    <i class="fas fa-map-marker-alt"></i>
                    <div><h3>Visit Us</h3><p>Shivi's Stylevana Office, Damoh,<br>Madhya Pradesh, India</p></div>
                </div>
                <div class="contact-detail">
                    <i class="fas fa-phone-alt"></i>
                    <div><h3>Call Us</h3><p>+91 6264204873</p></div>
                </div>
                <div class="contact-detail">
                    <i class="fas fa-envelope"></i>
                    <div><h3>Email Support</h3><p>contact@shivivanastyle.com</p></div>
                </div>
                
                <div class="map-placeholder">
                   <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQv-Qzlvd4UDKuF3TCQlEI08pv0wmJGqzAsWw&s" style="width:100%; display:block;">
                </div>
            </div>

            <div class="history-section">
                <h2 class="history-title">💬 Conversation History</h2>
                
                <?php 
                // History fetch logic
                $history_query = "SELECT * FROM userfeedback WHERE gmail = '$user_email' ORDER BY fid DESC";
                $history_res = mysqli_query($cn, $history_query);
                
                if($history_res && mysqli_num_rows($history_res) > 0) {
                    while($row = mysqli_fetch_array($history_res)) {
                ?>
                    <div class="query-card">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                            <div>
                                <span style="font-size: 12px; color: #aaa;">
                                    <?php echo date('M d, Y | h:i A', strtotime($row['submitted_at'])); ?>
                                </span>
                                <h3 style="font-size: 18px; margin: 5px 0; color: var(--dark-text);"><?php echo htmlspecialchars($row['subject']); ?></h3>
                            </div>
                            <span class="status-badge <?php echo !empty($row['admin_reply']) ? 'replied' : 'pending'; ?>">
                                <?php echo !empty($row['admin_reply']) ? 'Replied' : 'Awaiting Reply'; ?>
                            </span>
                        </div>

                        <div style="margin: 15px 0; padding-left: 15px; border-left: 3px solid #eee;">
                            <p style="font-size: 14px; color: #555;"><strong>Me:</strong> <?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                        </div>

                        <?php if(!empty($row['admin_reply'])) { ?>
                            <div class="admin-response">
                                <strong style="color: var(--success-green); font-size: 14px;">
                                    <i class="fas fa-user-shield"></i> Stylevana Admin Team:
                                </strong>
                                <p style="font-size: 14px; margin-top: 8px; color: #333; line-height: 1.6;">
                                    <?php echo nl2br(htmlspecialchars($row['admin_reply'])); ?>
                                </p>
                            </div>
                        <?php } ?>
                    </div>
                <?php 
                    }
                } else {
                    echo "<div style='text-align:center; padding: 40px; color: #ccc;'>
                            <i class='far fa-comments' style='font-size: 40px; margin-bottom: 10px;'></i>
                            <p>No past conversations found. If you have any issue, feel free to message us!</p>
                          </div>";
                }
                ?>
            </div>
        </div>
    </main>
     <br>
    <?php include("footer.php"); ?>
</body>
</html>
