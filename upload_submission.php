<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$task_id = $_GET['task_id'] ?? null;

if (!$task_id) {
    die("Invalid task.");
}

// Check if this user is approved for this task
$stmt = $conn->prepare("SELECT status FROM applications WHERE task_id = ? AND applied_by = ?");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$stmt->bind_result($app_status);
$stmt->fetch();
$stmt->close();

if ($app_status !== 'approved') {
    die("❌ You are not approved to upload work for this task.");
}

// If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $upload_dir = 'uploads/';
    $filename = basename($_FILES['file']['name']);
    $target_path = $upload_dir . time() . "_" . $filename;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
        $notes = $_POST['notes'] ?? '';
        $stmt = $conn->prepare("INSERT INTO submissions (task_id, submitted_by, file_path, notes) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $task_id, $user_id, $target_path, $notes);
        if ($stmt->execute()) {
            $message = "✅ File uploaded successfully!";
        } else {
            $message = "❌ Failed to save submission in database.";
        }
    } else {
        $message = "❌ File upload failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Submission - GigCircle</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>

          * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

       
            body {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg,#020024,#00D4FF);
    min-height: 100vh;
    overflow-y: auto;
}
.task-list-box h2
{
    color:white;
}
     
        </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="task-list-box">
    <h2> Upload Your Work</h2>

    <?php if (!empty($message)): ?>
        <p style="color: green;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label  style="color: white;">Upload File (PDF, DOCX, ZIP):</label><br>
        <input type="file" name="file" required><br><br>

        <label style="color:white;">Notes (optional):</label><br>
        <textarea name="notes" rows="4" cols="50"></textarea><br><br>

        <button type="submit" style="background-color:#020024;color:white;">Submit Work</button>
    </form>

    <a href="dashboard.php" style="color:white;"> Back to Dashboard</a>
</div>
</body>
</html>




