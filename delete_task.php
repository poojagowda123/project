<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];
    $user_id = $_SESSION['user_id'];

    // Step 1: Check if the task belongs to the logged-in user
    $task_check = $conn->prepare("SELECT id FROM tasks WHERE id = ? AND posted_by = ?");
    $task_check->bind_param("ii", $task_id, $user_id);
    $task_check->execute();
    $result = $task_check->get_result();

    if ($result->num_rows === 0) {
        // Task doesn't exist or doesn't belong to this user
        header("Location: mytask.php?error=unauthorized");
        exit();
    }

    // Step 2: Check if there are any applications for this task
    $app_check = $conn->prepare("SELECT COUNT(*) FROM applications WHERE task_id = ?");
    $app_check->bind_param("i", $task_id);
    $app_check->execute();
    $app_check->bind_result($app_count);
    $app_check->fetch();
    $app_check->close();

    if ($app_count > 0) {
        // Applications exist — do not allow deletion
        header("Location: mytasks.php?error=has_applications");
        exit();
    }

    // Step 3: No applications — delete the task
    $delete_query = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $delete_query->bind_param("i", $task_id);
    
    if ($delete_query->execute()) {
        header("Location: mytask.php?success=task_deleted");
    } else {
        header("Location: mytask.php?error=delete_failed");
    }

    $delete_query->close();
}
?>
