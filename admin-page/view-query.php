<?php 
require_once __DIR__ . '/config.php'; 
session_start();

// 1. Admin Security Check
if (!isset($_SESSION["admin"])){
    header("location:login.php");
    exit();    
}

// 2. URL se user ki email lena (Jis user se chat karni hai)
if(!isset($_GET['email'])) {
    header("location:queries.php");
    exit();
}


$customer_email = $_GET['email'];

// --- 3. REPLY SUBMISSION LOGIC ---
if(isset($_POST['submit_reply'])) {
    $fid = $_POST['fid'];
    $reply_text = mysqli_real_escape_string($conn, $_POST['reply_msg']);
    
    if(!empty($reply_text)) {
        // userfeedback table mein admin_reply update kar rahe hain
        $update = "UPDATE userfeedback SET admin_reply = '$reply_text' WHERE fid = '$fid'";
        if(mysqli_query($conn, $update)) {
            echo "<script>alert('Reply Sent Successfully!'); window.location.href='view-query.php?email=$customer_email';</script>";
        } else {
            echo "<script>alert('Error updating reply: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo $customer_email; ?> | Admin</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="view-query.css" />

</head>
<body>

    <?php include("header.php"); ?>

    <div class="admin-wrapper">
        <?php include("sidebar.php"); ?>

        <main class="main-content">
            <a href="queries.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to All Queries</a>

            <div class="chat-container">
                <div class="chat-header">
                    <h2 style="font-family: 'Playfair Display'; margin: 0;">Conversation</h2>
                    <p style="color: var(--accent); font-size: 0.9rem; font-weight: 500;">User: <?php echo $customer_email; ?></p>
                </div>

                <div class="chat-window" id="chatWindow">
                    <?php 
                    // History fetch karna usi email ke liye
                    $chat_res = mysqli_query($conn, "SELECT * FROM userfeedback WHERE gmail = '$customer_email' ORDER BY submitted_at ASC");
                    
                    if($chat_res && mysqli_num_rows($chat_res) > 0) {
                        while($row = mysqli_fetch_array($chat_res)) {
                        ?>
                            <div class="msg-box user-msg">
                                <small><strong><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['name']); ?></strong> • <?php echo date('d M, h:i A', strtotime($row['submitted_at'])); ?></small>
                                <p style="margin: 5px 0; font-weight: 600; color: #555;">Sub: <?php echo htmlspecialchars($row['subject']); ?></p>
                                <p style="margin: 0;"><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>

                                <?php if(empty($row['admin_reply'])) { ?>
                                    <div class="reply-section">
                                        <form method="POST">
                                            <input type="hidden" name="fid" value="<?php echo $row['fid']; ?>">
                                            <textarea name="reply_msg" rows="3" placeholder="Type your reply here..." required></textarea>
                                            <button type="submit" name="submit_reply" class="send-btn">
                                                <i class="fas fa-paper-plane"></i> Send Response
                                            </button>
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>

                            <?php if(!empty($row['admin_reply'])) { ?>
                                <div class="msg-box admin-msg">
                                    <small><i class="fas fa-check-circle"></i> Stylevana Official Response</small>
                                    <p style="margin: 0;"><?php echo nl2br(htmlspecialchars($row['admin_reply'])); ?></p>
                                </div>
                            <?php } ?>
                        <?php 
                        }
                    } else {
                        echo "<div style='text-align:center; margin-top:100px; color:#ccc;'><i class='fas fa-ghost' style='font-size:3rem;'></i><p>No messages found.</p></div>";
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>

    <?php include("footer.php"); ?>

    <script>
        // Automatic scroll to bottom of chat
        var chatBox = document.getElementById("chatWindow");
        chatBox.scrollTop = chatBox.scrollHeight;
    </script>
</body>
</html>