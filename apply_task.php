<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id'])) {
    $task_id = intval($_POST['task_id']);

    // Check if already applied
    $check = $conn->prepare("SELECT id FROM applications WHERE task_id = ? AND applied_by = ?");
    $check->bind_param("ii", $task_id, $user_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "<div class='alert warning'>⚠️ You have already applied for this task.</div>";
    } else {
        // Apply
        $stmt = $conn->prepare("INSERT INTO applications (task_id, applied_by) VALUES (?, ?)");
        $stmt->bind_param("ii", $task_id, $user_id);

        if ($stmt->execute()) {
            $message = "<div class='alert success'>✅ Application submitted successfully.</div>";
        } else {
            $message = "<div class='alert error'>❌ Failed to apply. Try again.</div>";
        }
    }
} else {
    $message = "<div class='alert error'>❌ Invalid request.</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply to Task</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom right, #001f3f, #00bfff); /* Dark blue to light blue */
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .alert {
            background-color: #fff;
            color: #333;
            padding: 15px 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            width: 90%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .alert.success {
            border-left: 6px solid #28a745;
        }

        .alert.warning {
            border-left: 6px solid #ffc107;
        }

        .alert.error {
            border-left: 6px solid #dc3545;
        }

        .back-link {
            font-size: 16px;
            color: #fff;
            text-decoration: underline;
            margin-top: 15px;
        }

        .back-link:hover {
            color: #dcdcdc;
        }
    </style>
</head>
<body>

<?= $message ?>

<a href="dashboard.php" class="back-link">🔙 Back to Dashboard</a>

</body>
</html>
//pooja 2nd 