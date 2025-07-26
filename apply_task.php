<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id'])) {
    $task_id = intval($_POST['task_id']);

    // Check if already applied
    $check = $conn->prepare("SELECT id FROM applications WHERE task_id = ? AND applied_by = ?");
    $check->bind_param("ii", $task_id, $user_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "⚠️ You have already applied for this task.<br>";
    } else {
        // Apply
        $stmt = $conn->prepare("INSERT INTO applications (task_id, applied_by) VALUES (?, ?)");
        $stmt->bind_param("ii", $task_id, $user_id);

        if ($stmt->execute()) {
            echo "✅ Application submitted successfully.<br>";
        } else {
            echo "❌ Failed to apply. Try again.<br>";
        }
    }
} else {
    echo "❌ Invalid request.<br>";
}
?>

<a href="view_tasks.php">🔙 Back to Task List</a>
