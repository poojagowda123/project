<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Fetch user profile data
$stmt = $conn->prepare("SELECT username, email, phone, profile_picture, description, qr_code FROM users WHERE id = ?");
if (!$stmt) {
    die("SQL error (profile fetch): " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email,$phone, $profile_picture, $description, $qr_code_path);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - GigCircle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #020024, #00D4FF);
            color: #333;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #020024;
        }

        .profile-pic {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }

        .profile-pic img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #000;
        }

        .description-box {
            text-align: center;
            margin-bottom: 25px;
        }

        .description-box p {
            font-style: italic;
            color: #444;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-section p {
            margin-bottom: 10px;
        }

        .qr-preview {
            text-align: center;
            margin-top: 20px;
        }

        .qr-preview img {
            max-width: 200px;
            border: 2px solid #ccc;
            padding: 5px;
            border-radius: 10px;
        }

        .edit-link, .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-weight: 600;
            color: #020024;
            text-decoration: none;
        }

        .edit-link:hover, .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-user-circle"></i> My Profile</h2>

    <?php if ($profile_picture): ?>
        <div class="profile-pic">
            <img src="<?= htmlspecialchars($profile_picture) ?>" alt="Profile Picture">
        </div>
    <?php endif; ?>

    <div class="description-box">
        <p><?= nl2br(htmlspecialchars($description ?: 'No description added yet.')) ?></p>
    </div>

    <div class="info-section">
        <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($phone ?: 'Not provided') ?></p>
        <!-- <input type="text" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>" pattern="[0-9]{10}" title="Enter 10-digit phone number"> -->


    </div>

    <?php if ($qr_code_path): ?>
        <div class="qr-preview">
            <p><strong>My UPI QR Code:</strong></p>
            <img src="<?= htmlspecialchars($qr_code_path) ?>" alt="QR Code">
        </div>
    <?php endif; ?>

    <a href="edit_profile.php" class="edit-link"><i class="fas fa-edit"></i> Edit Profile</a>
    <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

</body>
</html>
