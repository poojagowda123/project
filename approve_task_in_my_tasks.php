<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'], $_POST['task_id'])) {
    $app_id = intval($_POST['application_id']);
    $task_id = intval($_POST['task_id']);

    // 1. Get applied user id (approved)
    $getApplicant = $conn->prepare("SELECT applied_by FROM applications WHERE id = ?");
    $getApplicant->bind_param("i", $app_id);
    $getApplicant->execute();
    $getApplicant->bind_result($approved_user_id);
    $getApplicant->fetch();
    $getApplicant->close();

    // 2. Approve selected application
    $approve = $conn->prepare("UPDATE applications SET status = 'approved' WHERE id = ?");
    $approve->bind_param("i", $app_id);
    $approve->execute();

    // 3. Reject others for the same task
    $reject = $conn->prepare("UPDATE applications SET status = 'rejected' WHERE id != ? AND task_id = ?");
    $reject->bind_param("ii", $app_id, $task_id);
    $reject->execute();

    // 4. Close the task
    $close = $conn->prepare("UPDATE tasks SET status = 'closed' WHERE id = ?");
    $close->bind_param("i", $task_id);
    $close->execute();

    // 5. Optional: Store who got approved (you can add a `selected_freelancer_id` column in `tasks`)
    $setFreelancer = $conn->prepare("UPDATE tasks SET selected_freelancer_id = ? WHERE id = ?");
    $setFreelancer->bind_param("ii", $approved_user_id, $task_id);
    $setFreelancer->execute();
}

header("Location: my_task.php");
exit();

