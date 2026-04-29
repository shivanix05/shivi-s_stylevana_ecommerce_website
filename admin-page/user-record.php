<?php 
include("function.php"); 
session_start();

// Admin protection: Check if admin is logged in
if(!isset($_SESSION["admin"])) { 
    header("location:adminlogin.php"); 
    exit(); 
} 

$cn = make_connection();

// Search Logic
$search_query = "";
if(isset($_POST['search_btn'])) {
    $search_txt = mysqli_real_escape_string($cn, $_POST['search_txt']);
    $search_query = " WHERE name LIKE '%$search_txt%' OR gmail LIKE '%$search_txt%' OR mobilenumber LIKE '%$search_txt%'";
}

// Fetching all users - Latest registrations on top
$str = "SELECT * FROM userdetail $search_query ORDER BY sno DESC";
$rs = mysqli_query($cn, $str);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Records | Stylevana Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="user-record.css" />
    <style>
      
    </style>
</head>
<body>

    <?php include("header.php"); ?>

    <div class="admin-wrapper">
        
        <?php include("sidebar.php"); ?>

        <main class="main-content">
            
            <div class="page-header">
                <h1>User Records</h1>
                
                <form method="POST" class="search-box">
                    <i class="fas fa-search" style="color: #DDD; align-self: center;"></i>
                    <input type="text" name="search_txt" placeholder="Search by name, email or mobile..." value="<?php echo isset($_POST['search_txt']) ? $_POST['search_txt'] : ''; ?>">
                    <button type="submit" name="search_btn" class="search-btn">Find</button>
                </form>
            </div>

            <div class="customer-card">
                <table>
                    <thead>
                        <tr>
                            <th>Customer Profile</th>
                            <th>Email Address</th>
                            <th>Mobile Number</th>
                            <th>City</th>
                            <th>Status</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($rs) > 0) {
                            while($row = mysqli_fetch_array($rs)) { 
                                // Photo path handling
                                $photo = $row['userphoto'];
                                $image_src = (!empty($photo)) ? "uploads/".$photo : "https://cdn-icons-png.flaticon.com/512/149/149071.png";
                        ?>
                        <tr>
                            <td>
                                <div class="user-info-cell">
                                    <img src="<?php echo $image_src; ?>" class="user-img" onerror="this.src='https://cdn-icons-png.flaticon.com/512/149/149071.png'">
                                    <div>
                                        <span class="user-name"><?php echo $row['name']; ?></span>
                                        <span class="user-sno">ID: #CUST-<?php echo $row['sno']; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td style="color: #666;"><?php echo $row['gmail']; ?></td>
                            <td style="font-weight: 500; color: #444;"><?php echo $row['mobilenumber']; ?></td>
                            <td><?php echo (!empty($row['city'])) ? $row['city'] : '<span style="color:#DDD;">N/A</span>'; ?></td>
                            <td><span class="status-badge">Active</span></td>
                            <td style="text-align: center;">
                                <a href="user-details.php?id=<?php echo $row['sno']; ?>" class="view-link">
                                    View Full Details <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='6' class='empty-state'>No user records found matching your criteria.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <?php include("footer.php"); ?>

</body>
</html>