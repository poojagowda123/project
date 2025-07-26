<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'], $_POST['action'])) {
    $app_id = intval($_POST['application_id']);
    $action = $_POST['action'] === 'approve' ? 'approved' : 'rejected';

    // Confirm user owns the task
    $check = $conn->prepare("SELECT t.posted_by FROM applications a JOIN tasks t ON a.task_id = t.id WHERE a.id = ?");
    $check->bind_param("i", $app_id);
    $check->execute();
    $check->bind_result($posted_by);
    $check->fetch();
    $check->close();

    if ($posted_by == $_SESSION['user_id']) {
        $update = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $update->bind_param("si", $action, $app_id);
        $update->execute();
    }
}

header("Location: view_applications.php");
exit();

