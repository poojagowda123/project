<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

$stmt = $conn->prepare("SELECT email, profile_picture, description , phone FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($email, $profile_picture, $description,$phone);
$stmt->fetch();
$stmt->close();


if (isset($_POST['upload_qr']) && isset($_FILES['qr_code'])) {
    $file_name = $_FILES['qr_code']['name'];
    $file_tmp = $_FILES['qr_code']['tmp_name'];
    $target_dir = "qr_codes/";
    $file_path = $target_dir . time() . "_" . basename($file_name);

    if (move_uploaded_file($file_tmp, $file_path)) {
        $stmt = $conn->prepare("UPDATE users SET qr_code = ? WHERE id = ?");
        $stmt->bind_param("si", $file_path, $user_id);
        $stmt->execute();
        $message = "✅ QR code uploaded successfully!";
        $qr_code_path = $file_path;
    } else {
        $message = "❌ Failed to upload QR code.";
    }
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["update_profile"])) {
        $new_email = trim($_POST["email"]);
        $new_description = trim($_POST["description"]);
        $new_phone = trim($_POST["phone"]);


        if (!empty($_FILES["profile_picture"]["name"])) {
            $file_name = $_FILES["profile_picture"]["name"];
            $file_tmp = $_FILES["profile_picture"]["tmp_name"];
            $file_path = "profile_pics/" . time() . "_" . basename($file_name);
            move_uploaded_file($file_tmp, $file_path);
            $stmt = $conn->prepare("UPDATE users SET email = ?,phone=?, description = ?, profile_picture = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $new_email,  $new_phone, $new_description, $file_path, $user_id);
            $profile_picture = $file_path;
        } else {
            $stmt = $conn->prepare("UPDATE users SET email = ?,phone=?, description = ? WHERE id = ?");
            $stmt->bind_param("ssi", $new_email, $new_phone, $new_description, $user_id);
        }

        if ($stmt->execute()) {
            $message = "✅ Profile updated!";
            $email = $new_email;
            $description = $new_description;
            $phone=$new_phone;
        } else {
            $message = "❌ Failed to update profile.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - GigCircle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg,#020024,#00D4FF);
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: 600;
            display: block;
            margin-top: 15px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        button {
            background-color: #020024;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 14px;
        }
        button:hover {
            background-color: rgba(19, 74, 224, 1);
        }
        .message {
            text-align: center;
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-user-edit"></i> Edit Profile</h2>
        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

            <label>Description</label>
            <textarea name="description" rows="4" placeholder="Tell something about yourself..."><?= htmlspecialchars($description) ?></textarea>

            <label>Phone Number</label>
<input type="text" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>" placeholder="Enter your phone number">


            <label>Upload New Profile Picture</label>
            <input type="file" name="profile_picture" accept="image/*">

            <hr>
<h3>Upload UPI QR Code</h3>

<?php if (!empty($qr_code_path)): ?>
    <p>Current QR Code:</p>
    <img src="<?= htmlspecialchars($qr_code_path) ?>" alt="QR Code" style="max-width:200px; margin-bottom:10px;">
<?php endif; ?>

<input type="file" name="qr_code" accept="image/*" >
<button type="submit" name="upload_qr" class="btn">Upload QR Code</button>


           <button type="submit" name="update_profile"><i class="fas fa-save"></i> Save Changes</button>
           <a href="profile.php" style="color:black; padding:10px;">Back to profile</a>
        </form>
    </div>
</body>
</html>