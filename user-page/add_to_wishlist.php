<?php
require_once __DIR__ . "/config.php";
session_start();

// Check if connection exists
if (!$cn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user'])) {
    echo "login_required";
    exit();
}

if (isset($_POST['product_id'])) {
    $pid = mysqli_real_escape_string($cn, $_POST['product_id']);
    $u_email = $_SESSION['user'];

    // Pehle check karo ki duplicate toh nahi hai
    $check = mysqli_query($cn, "SELECT * FROM wishlist WHERE user_email = '$u_email' AND pid = '$pid'");
    
    if (mysqli_num_rows($check) > 0) {
        echo "already_exists";
    } else {
        // Naya item insert karna
        $query = "INSERT INTO wishlist (user_email, pid) VALUES ('$u_email', '$pid')";
        if (mysqli_query($cn, $query)) {
            echo "success";
        } else {
            // Agar SQL query fail hui toh exact error dikhayega
            echo "Error: " . mysqli_error($cn);
        }
    }
} else {
    echo "no_pid";
}
?>
