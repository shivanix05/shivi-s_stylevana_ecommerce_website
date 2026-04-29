<?php
require_once __DIR__ . "/config.php";

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($cn, $_POST['query']);
    // Check karo 'shop' table aur 'productname' column name sahi hai?
    $query = "SELECT * FROM shop WHERE productname LIKE '%$search%' LIMIT 5";
    $result = mysqli_query($cn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Yahan selectSuggestion function call hona zaroori hai
            echo "
            <div class='suggestion-item' onclick='selectSuggestion(\"".$row['productname']."\")'>
                <img src='".$row['productphoto']."' style='width:30px; height:30px; object-fit:cover; border-radius:4px;'>
                <span>".$row['productname']."</span>
            </div>";
        }
    } else {
        echo "<div class='suggestion-item' style='padding:10px;'>No product found</div>";
    }
}
?>
