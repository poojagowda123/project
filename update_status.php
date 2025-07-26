<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['app_id'], $_POST['action'])) {
    $app_id = $_POST['app_id'];
    $action = $_POST['action'];
    $valid_status = ['approve' => 'approved', 'reject' => 'rejected'];

    if (array_key_exists($action, $valid_status)) {
        $status = $valid_status[$action];
        $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $app_id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: mytask.php"); // or wherever this code is used
exit();
?>
