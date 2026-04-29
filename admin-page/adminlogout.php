<?php
    // Session start karna zaroori hai taaki usey khatam kiya ja sake
    session_start();

    // Saare session variables ko clear karna
    session_unset();

    // Session ko puri tarah destroy karna
    session_destroy();

    // Logout ke baad wapas login page par bhejna
    header("location:adminlogin.php");
    exit();
?>