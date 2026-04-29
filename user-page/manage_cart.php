<?php
session_start();
require_once __DIR__ . "/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION["user"])) {
        echo "Please login first";
        exit();
    }

    $pid = mysqli_real_escape_string($cn, $_POST['pid']);
    $user = $_SESSION['user'];

    // Check karein ki kya ye product pehle se cart mein hai?
    $check = "SELECT * FROM cart WHERE user_email='$user' AND pid='$pid'";
    $res = mysqli_query($cn, $check);

    if (mysqli_num_rows($res) > 0) {
        // Agar hai, toh quantity badha do
        $query = "UPDATE cart SET qty = qty + 1 WHERE user_email='$user' AND pid='$pid'";
    } else {
        // Agar nahi hai, toh naya insert karo
        $query = "INSERT INTO cart (user_email, pid, qty) VALUES ('$user', '$pid', 1)";
    }

    if (mysqli_query($cn, $query)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($cn);
    }
}
?>
