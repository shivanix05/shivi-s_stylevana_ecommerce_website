<?php
// 1. Database Connection Function
function make_connection() {
    $host = "localhost";
    $user = "root";
    $pass = "root123";
    $db   = "shivi-stylevana";

    $cn = mysqli_connect($host, $user, $pass, $db);

    if (!$cn) {
        die("Connection Failed: " . mysqli_connect_error());
    }
    return $cn;
}

// 2. Function to get Total Count from any table
// Ise tum Dashboard par counts dikhane ke liye use kar sakti ho
function get_total_count($table_name) {
    $cn = make_connection();
    $query = "SELECT * FROM $table_name";
    $result = mysqli_query($cn, $query);
    
    if ($result) {
        return mysqli_num_rows($result);
    } else {
        return 0;
    }
}

// 3. Data Sanitization (Security ke liye)
// Form se aane waale data ko clean karne ke liye
function clean_input($data) {
    $cn = make_connection();
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    // 'with' word ko delete kar diya hai
    $data = mysqli_real_escape_string($cn, $data); 
    return $data;
}
// 4. Check if Admin is Logged In
function check_admin_login() {
    if (!isset($_SESSION["admin"])) {
        header("location:adminlogin.php");
        exit();
    }
}
?>