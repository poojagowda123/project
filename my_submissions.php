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
    <title>My Submissions - GigCircle</title>
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
            padding: 40px 20px;
            margin: 0 auto;
         }

        h2 {
            font-size: 26px;
            margin-bottom: 30px;
            text-align: center;
            color: white;
        }

        .task-card {
            
            gap: 5px;
           display:block;
             width: calc(50% - 20px);
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.05);
             
        }
        @media (max-width: 768px) {
    .task-card {
        width: 100%;
    }
}
        .task-card h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        .task-card p {
            font-size: 14px;
            margin: 6px 0;
            color: #555;
        }

        .task-card a {
            color:rgb(0, 0, 0);
            font-weight: 500;
            text-decoration: none;
        }

        .task-card a:hover {
            text-decoration: underline;
        }

        .status-paid {
            color: green;
            font-weight: 600;
        }

        .status-unpaid {
            color: red;
            font-weight: 600;
        }

        .no-submissions {
            text-align: center;
            margin-top: 50px;
            font-size: 16px;
            color: #888;
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
    <h2>My Submitted Tasks</h2>

    <?php
    $stmt = $conn->prepare("
        SELECT s.*, t.title, t.budget
        FROM submissions s
        JOIN tasks t ON s.task_id = t.id
        WHERE s.submitted_by = ?
        ORDER BY s.submitted_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $submissions = $stmt->get_result();

    if ($submissions->num_rows > 0):
        while ($row = $submissions->fetch_assoc()):
    ?>
        <div class="task-card">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p><strong>Notes:</strong> <?= nl2br(htmlspecialchars($row['notes'])) ?></p>
            <p><strong>Submitted At:</strong> <?= htmlspecialchars($row['submitted_at']) ?></p>
            <p><strong>File:</strong> <a href="<?= htmlspecialchars($row['file_path']) ?>" download><i class="fas fa-download"></i> Download</a></p>

            <?php
            // Check payment status
            $checkPay = $conn->prepare("SELECT status FROM payments WHERE task_id = ? AND paid_to = ?");
            $checkPay->bind_param("ii", $row['task_id'], $user_id);
            $checkPay->execute();
            $checkPay->bind_result($payment_status);
            if ($checkPay->fetch()):
                echo "<p class='status-paid'>Payment: " . strtoupper($payment_status) . "</p>";
            else:
                echo "<p class='status-unpaid'>Payment: Not yet done</p>";
            endif;
            $checkPay->close();
            ?>
        </div>
    <?php endwhile; else: ?>
        <div class="no-submissions">You haven’t submitted any tasks yet.</div>
    <?php endif; ?>

    <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

</body>
</html>
