<?php
require_once __DIR__ . "/config.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    exit();
}

if (isset($_GET['id'])) {
    $p_id = $_GET['id'];
    $u_email = $_SESSION['user'];

    // Deleting from your table 'wishlist' where column is 'pid'
    $delete_query = "DELETE FROM wishlist WHERE pid = '$p_id' AND user_email = '$u_email'";

    if (mysqli_query($cn, $delete_query)) {
        header("location:wishlist.php?msg=removed");
    } else {
        echo "Error: " . mysqli_error($cn);
    }
} else {
    header("location:wishlist.php");
}
?>
