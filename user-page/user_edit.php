<?php
// Includes the database configuration and starts a session
include("config.php");
session_start();

// Redirects to the login page if the user is not logged in
if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();
}

$name = $_SESSION["user"];
$message = "";

// Handle form submission for updating profile
if (isset($_POST["update_profile"])) {
    $name = mysqli_real_escape_string($cn, $_POST['name']);
    $email = mysqli_real_escape_string($cn, $_POST['email']);
    $mobile = mysqli_real_escape_string($cn, $_POST['mobile_number']);
    $address = mysqli_real_escape_string($cn, $_POST['address']);

    // Update the user's data in the database
    $update_query = "UPDATE userdetail SET name = '$name', email = '$email', mobile_number = '$mobile', address = '$address' WHERE name = '$name'";

    if (mysqli_query($cn, $update_query)) {
        // Update the session variable with the new name if it was changed
        $_SESSION["user"] = $name;
        $name = $name; // Update the local variable as well
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile: " . mysqli_error($cn);
    }
}

// Fetch current user data to pre-populate the form
$query = "SELECT * FROM userdetail WHERE name = '$name'";
$result = mysqli_query($cn, $query);
$user_data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Shivi's Stylevana</title>
    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="afterl-style.css">
    <link rel="stylesheet" href="profile-style.css">
    <style>body {
    background-color: #f7f3f0;
}

.profile-container {
    background-color: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 50px auto;
    text-align: center;
}

.profile-container h2 {
    font-size: 2em;
    color: #4b3d37;
    margin-bottom: 20px;
}

.message {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.profile-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-group {
    text-align: left;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #5c4b43;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1em;
    box-sizing: border-box;
}

.form-actions {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}

.update-button,
.cancel-button {
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1em;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.update-button {
    background-color: #7b584b;
    color: #fff;
}

.update-button:hover {
    background-color: #5c4b43;
}

.cancel-button {
    background-color: #e9e9e9;
    color: #555;
}

.cancel-button:hover {
    background-color: #dcdcdc;
}
</style>
</head>
<body>
    <?php include("header.php"); ?>

    <main class="container main-content">
        <div class="profile-container">
            <h2>Edit Profile</h2>
            <?php if (!empty($message)) { ?>
                <div class="message">
                    <?php echo $message; ?>
                </div>
            <?php } ?>

            <form action="edit-profile.php" method="post" class="profile-form">
                <div class="form-group">
                    <label for="name">User Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" rows="4" required><?php echo htmlspecialchars($user_data['address']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="mobile_number">Mobile Number:</label>
                    <input type="text" id="mobile_number" name="mobile_number" value="<?php echo htmlspecialchars($user_data['mobile_number']); ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit" name="update_profile" class="update-button">Update Profile</button>
                    <a href="after-login.php" class="cancel-button">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <?php include("footer.php"); ?>
</body>
</html>
