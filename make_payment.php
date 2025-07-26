<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Not logged in.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $paid_to = $_POST['paid_to'];
    $amount = $_POST['amount'];
    $paid_by = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO payments (task_id, paid_by, paid_to, amount, status) VALUES (?, ?, ?, ?, 'completed')");
    $stmt->bind_param("iiid", $task_id, $paid_by, $paid_to, $amount);
    if ($stmt->execute()) {
        echo "<script>alert('✅ Payment completed!'); window.location='my_task.php';</script>";
    } else {
        echo "❌ Payment failed.";
    }
}

