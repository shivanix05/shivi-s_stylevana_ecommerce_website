<?php
include("function.php"); // Ya config.php jo bhi aapne banaya hai
session_start();

// Connection check
$cn = make_connection(); 

if(isset($_GET["r"])) {
    $id = $_GET["r"];
    
    // Query check: Table ka naam 'shop' hi hai na? 
    // Aur ID column 'pid' hi hai na?
    $str = "DELETE FROM shop WHERE pid = '$id'";
    
    if(mysqli_query($cn, $str)) {
        // Success: Wapas bhej do
        header("location:product.php?msg=success");
        exit();
    } else {
        // Agar SQL mein koi error aaye
        echo "Query Failed: " . mysqli_error($cn);
    }
} else {
    // Agar ID URL mein nahi mili
    echo "Product ID missing in URL!";
}
?>