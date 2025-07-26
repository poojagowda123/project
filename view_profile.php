<?php
include 'db.php';

if (!isset($_GET['user_id'])) {
    echo "User not specified.";
    exit;
}

$user_id = intval($_GET['user_id']);

$stmt = $conn->prepare("SELECT username, description, profile_picture, qr_code FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $description, $profile_picture, $qr_code);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($username) ?>'s Profile - GigCircle</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg,#020024,#00D4FF);
            padding: 40px;
            text-align: center;
        }

        .profile-box {
            background: white;
            padding: 30px;
            max-width: 400px;
            margin: 0 auto;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .qr-code img {
            max-width: 200px;
            margin-top: 10px;
        }

        h2 {
            margin-bottom: 10px;
        }

        p {
            color: #555;
        }
    </style>
</head>
<body>
    <div class="profile-box">
        <?php if ($profile_picture): ?>
            <img src="<?= htmlspecialchars($profile_picture) ?>" alt="Profile Picture">
        <?php endif; ?>

        <h2><?= htmlspecialchars($username) ?></h2>

        <p><?= nl2br(htmlspecialchars($description ?: "No description provided.")) ?></p>

        <?php if ($qr_code): ?>
            <div class="qr-code">
                <p><strong>UPI QR Code:</strong></p>
                <img src="<?= htmlspecialchars($qr_code) ?>" alt="QR Code">
            </div>
        <?php endif; ?>
        <a href="mytask.php">Back to my posted tasks</a>
    </div>
</body>
</html>
