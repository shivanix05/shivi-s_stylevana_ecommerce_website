<?php
require_once __DIR__ . "/config.php";
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'not_logged_in', 'wishlisted' => false]);
    exit();
}

if (!isset($_GET['pid'])) {
    echo json_encode(['error' => 'no_pid', 'wishlisted' => false]);
    exit();
}

$pid   = (int) $_GET['pid'];
$email = mysqli_real_escape_string($cn, $_SESSION['user']);

// Check if already wishlisted
$check = mysqli_query($cn, "SELECT wid FROM wishlist WHERE user_email='$email' AND pid='$pid'");

if ($check && mysqli_num_rows($check) > 0) {
    // Already wishlisted → remove it
    mysqli_query($cn, "DELETE FROM wishlist WHERE user_email='$email' AND pid='$pid'");
    echo json_encode(['wishlisted' => false]);
} else {
    // Not wishlisted → add it
    mysqli_query($cn, "INSERT INTO wishlist (user_email, pid) VALUES ('$email', '$pid')");
    echo json_encode(['wishlisted' => true]);
}
exit();