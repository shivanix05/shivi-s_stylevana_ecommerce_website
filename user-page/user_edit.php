<?php
require_once __DIR__ . "/config.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("location:login.php");
    exit();
}

$u_mail = $_SESSION['user'];

// 1. Current User Data Fetch
$res = mysqli_query($cn, "SELECT * FROM userdetail WHERE gmail='$u_mail'");
$user = mysqli_fetch_array($res);
$user_sno = $user['sno'];

// 2. Silent Log Function for Admin
function logForAdmin($cn, $sno, $field, $old, $new) {
    if ($old != $new) {
        $old = mysqli_real_escape_string($cn, $old);
        $new = mysqli_real_escape_string($cn, $new);
        mysqli_query($cn, "INSERT INTO user_update_history (user_sno, field_name, old_value, new_value) 
                           VALUES ('$sno', '$field', '$old', '$new')");
    }
}

// 3. Update Logic
if(isset($_POST['name'])) { 
    $new_name = mysqli_real_escape_string($cn, $_POST['name']);
    $new_gmail = mysqli_real_escape_string($cn, $_POST['gmail']); // Naya Gmail
    $new_mobile = mysqli_real_escape_string($cn, $_POST['mobilenumber']);
    $new_address = mysqli_real_escape_string($cn, $_POST['address']);
    $new_city = mysqli_real_escape_string($cn, $_POST['city']);

    // Check if new email already exists for someone else
    if($new_gmail != $user['gmail']) {
        $check_email = mysqli_query($cn, "SELECT sno FROM userdetail WHERE gmail='$new_gmail'");
        if(mysqli_num_rows($check_email) > 0) {
            echo "<script>alert('This Email is already registered with another account!'); window.history.back();</script>";
            exit();
        }
    }

    // Log changes before update
    logForAdmin($cn, $user_sno, 'name', $user['name'], $new_name);
    logForAdmin($cn, $user_sno, 'gmail', $user['gmail'], $new_gmail);
    logForAdmin($cn, $user_sno, 'mobilenumber', $user['mobilenumber'], $new_mobile);
    logForAdmin($cn, $user_sno, 'address', $user['address'], $new_address);
    logForAdmin($cn, $user_sno, 'city', $user['city'], $new_city);

    // Cropped Photo Handling
    $photo_sql = "";
    if(!empty($_POST['image_base64'])) {
        $data = $_POST['image_base64'];
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        $filename = time() . '_user.png';
        file_put_contents('uploads/' . $filename, $data);
        
        $photo_sql = ", userphoto='$filename'";
        logForAdmin($cn, $user_sno, 'userphoto', $user['userphoto'], $filename);
    }

    // Main Update Query
    $update_query = "UPDATE userdetail SET name='$new_name', gmail='$new_gmail', mobilenumber='$new_mobile', 
                     address='$new_address', city='$new_city' $photo_sql WHERE sno='$user_sno'";
    
    if(mysqli_query($cn, $update_query)) {
        // Update Session with NEW Email (Very Important)
        $_SESSION['user'] = $new_gmail; 
        echo "<script>alert('Profile & Email Updated Successfully!'); window.location.href='my-profile.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile | Shivi's Stylevana</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
    <style>
        :root { --bg: #F8F3ED; --rose: #D9A899; --white: #fff; --text: #444; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: var(--bg); color: var(--text); }
        .admin-wrapper { display: flex; justify-content: center; padding: 40px 20px; }
        .main-content { width: 100%; max-width: 600px; }
        .card { background: var(--white); border-radius: 30px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); text-align: center; }
        #upload-demo { width: 100%; margin: 0 auto; display: none; }
        .user-big-photo { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid var(--bg); margin: 0 auto 20px; display: block; cursor: pointer; }
        .info-row { margin-bottom: 20px; text-align: left; }
        .info-label { font-size: 0.7rem; color: #BBB; text-transform: uppercase; display: block; letter-spacing: 0.5px; margin-bottom: 5px; }
        .edit-input { width: 100%; padding: 12px 15px; border: 1px solid #F0F0F0; border-radius: 12px; background: #FAFAFA; font-size: 0.95rem; outline: none; }
        .edit-input:focus { border-color: var(--rose); background: #fff; }
        .save-btn { background: var(--rose); color: white; border: none; padding: 15px 40px; border-radius: 50px; font-weight: 600; cursor: pointer; width: 100%; font-size: 1rem; margin-top: 20px; }
        .back-link { display: block; margin-top: 20px; color: #BBB; text-decoration: none; font-size: 0.85rem; }
    </style>
</head>
<body>

    <?php include("header.php"); ?>

    <div class="admin-wrapper">
        <main class="main-content">
            <div class="card">
                <form id="profileForm" method="POST">
                    <div id="photo-area">
                        <?php 
                            $photo = $user['userphoto'];
                            $img = (!empty($photo)) ? "uploads/".$photo : "https://cdn-icons-png.flaticon.com/512/149/149071.png";
                        ?>
                        <img src="<?php echo $img; ?>" class="user-big-photo" id="currentPhoto" onclick="document.getElementById('upload').click()">
                    </div>

                    <div id="upload-demo"></div>
                    <input type="file" id="upload" style="display:none;" accept="image/*">
                    <input type="hidden" name="image_base64" id="image_base64">

                    <p id="help-text" style="font-size: 0.75rem; color: var(--rose); margin-bottom: 25px;">Click photo to choose & adjust</p>

                    <div class="info-row">
                        <span class="info-label">Full Name</span>
                        <input type="text" name="name" class="edit-input" value="<?php echo $user['name']; ?>" required>
                    </div>

                    <!-- Naya Gmail Input -->
                    <div class="info-row">
                        <span class="info-label">Email Address</span>
                        <input type="email" name="gmail" class="edit-input" value="<?php echo $user['gmail']; ?>" required>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Mobile</span>
                        <input type="text" name="mobilenumber" class="edit-input" value="<?php echo $user['mobilenumber']; ?>" required>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Address</span>
                        <input type="text" name="address" class="edit-input" value="<?php echo $user['address']; ?>">
                    </div>

                    <div class="info-row">
                        <span class="info-label">City</span>
                        <input type="text" name="city" class="edit-input" value="<?php echo $user['city']; ?>">
                    </div>

                    <button type="button" id="submit-btn" class="save-btn">Update Profile</button>
                    <a href="my-profile.php" class="back-link">Cancel</a>
                </form>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>

    <script>
        var $uploadCrop;
        function readFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#photo-area').hide();
                    $('#upload-demo').show();
                    $uploadCrop.croppie('bind', { url: e.target.result });
                    $('#help-text').text("Drag to adjust your photo");
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $uploadCrop = $('#upload-demo').croppie({
            viewport: { width: 150, height: 150, type: 'circle' },
            boundary: { width: 250, height: 250 },
            showZoomer: true
        });

        $('#upload').on('change', function () { readFile(this); });

        $('#submit-btn').on('click', function (ev) {
            if ($('#upload').val()) {
                $uploadCrop.croppie('result', {
                    type: 'base64',
                    size: 'viewport'
                }).then(function (resp) {
                    $('#image_base64').val(resp);
                    $('#profileForm').submit();
                });
            } else {
                $('#profileForm').submit();
            }
        });
    </script>
    <?php include("footer.php"); ?>
</body>
</html>
