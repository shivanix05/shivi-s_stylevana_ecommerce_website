<?php
require_once __DIR__ . "/config.php";
session_start();

if(isset($_POST['btn_review'])) {
    $pid = $_POST['pid'];
    $user = $_SESSION['user']; // logged in user ki email
    $rating = $_POST['rating'];
    $comment = mysqli_real_escape_string($cn, $_POST['comment']);

    $sql = "INSERT INTO reviews (pid, user_email, rating, comment) VALUES ('$pid', '$user', '$rating', '$comment')";
    
    if(mysqli_query($cn, $sql)) {
        header("Location: order.php?pid=$pid&status=success");
    } else {
        echo "Error: " . mysqli_error($cn);
    }
}
?>
