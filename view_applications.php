<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Applications - GigCircle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

       
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

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }

        h2 {
            font-size: 28px;
            color:rgb(255, 255, 255);
            margin-bottom: 30px;
            text-align: center;
        }

        .task-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
            border-left: 6px solid rgba(16, 0, 109, 1);
            transition: transform 0.2s;
        }

        .task-card:hover {
            transform: scale(1.01);
        }

        .task-card h3 {
            font-size: 20px;
            color: #222;
            margin-bottom: 10px;
        }

        .task-card p {
            font-size: 14px;
            color: #555;
            margin: 6px 0;
        }

        .task-card strong {
            color: #222;
        }

        button {
            background-color: #f27059;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #d85742;
        }

        .download-link {
            color:rgb(0, 0, 0);
            text-decoration: none;
            font-weight: 500;
        }

        .download-link:hover {
            text-decoration: underline;
        }

        .status {
            font-weight: bold;
            margin-top: 10px;
        }

        .status.success {
            color: green;
        }

        .status.pending {
            color: red;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color:rgba(255, 255, 255, 1);
            font-weight: 500;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-clipboard-check"></i> My Applications</h2>

    <?php
    $stmt = $conn->prepare("
        SELECT t.title, t.description, t.status AS task_status, a.status AS app_status, t.id AS task_id
        FROM applications a
        JOIN tasks t ON a.task_id = t.id
        WHERE a.applied_by = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
            $task_id = $row['task_id'];

            // Check submission
            $check_sub = $conn->prepare("SELECT * FROM submissions WHERE task_id = ? AND submitted_by = ?");
            $check_sub->bind_param("ii", $task_id, $user_id);
            $check_sub->execute();
            $submission_result = $check_sub->get_result();
            $is_uploaded = $submission_result->num_rows > 0;
            $submitted_file = $is_uploaded ? $submission_result->fetch_assoc()['file_path'] : null;
            $check_sub->close();

            // Check payment
            $pay_stmt = $conn->prepare("SELECT status FROM payments WHERE task_id = ? AND paid_to = ?");
            $pay_stmt->bind_param("ii", $task_id, $user_id);
            $pay_stmt->execute();
            $pay_stmt->bind_result($payment_status);
            $is_paid = $pay_stmt->fetch();
            $pay_stmt->close();
    ?>
        <div class="task-card">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
            <p><strong>Task Status:</strong> <?= ucfirst($row['task_status']) ?></p>
            <p><strong>Your Application:</strong> <?= ucfirst($row['app_status']) ?></p>

            <?php if ($row['app_status'] === 'approved'): ?>
                <?php if ($is_uploaded): ?>
                    <p class="status success">✅ Work Already Uploaded</p>
                    <a href="<?= $submitted_file ?>" class="download-link" download><i class="fas fa-download"></i> Download Submitted File</a>
                <?php else: ?>
                    <form action="upload_submission.php" method="GET">
                        <input type="hidden" name="task_id" value="<?= $task_id ?>">
                        <button type="submit"><i class="fas fa-upload"></i> Upload Work</button>
                    </form>
                <?php endif; ?>

                <!-- Payment Status -->
                <?php if ($is_paid): ?>
                    <p class="status success">💰 Payment Status: <?= strtoupper($payment_status) ?></p>
                <?php else: ?>
                    <p class="status pending">❌ Payment Not Done Yet</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endwhile; else: ?>
        <p style="text-align:center;">You have not applied for any tasks yet.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

</body>
</html>
