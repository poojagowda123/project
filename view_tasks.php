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
    <title>View Tasks - GigCircle</title>
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
            text-align: center;
            font-size: 28px;
            margin-bottom: 30px;
            color: white;
        }
      .task-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
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
            margin-bottom: 10px;
            font-size: 20px;
            color: #222;
        }

        .task-card p {
            font-size: 14px;
            color: #555;
        }

        .task-card small {
            color: #777;
        }

        form {
            margin-top: 10px;
        }

        .apply-btn, .applied-btn {
            padding: 8px 16px;
            font-size: 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .apply-btn {
            background-color:rgba(5, 15, 156, 1);
            color: white;
        }

        .apply-btn:hover {
            background-color:rgba(19, 74, 224, 1);
        }s

        .applied-btn {
            background-color:rgba(7, 133, 7, 1);
            color: white;
            cursor: default;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            text-align: center;
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
    <h2>Available Tasks</h2>
    
   <?php
$query = "SELECT * FROM tasks WHERE status = 'open' AND posted_by != ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0): ?>
    <div class="task-grid">
        <?php
        while ($task = $result->fetch_assoc()):
            $task_id = $task['id'];

            // Check if user already applied
            $check = $conn->prepare("SELECT id FROM applications WHERE task_id = ? AND applied_by = ?");
            $check->bind_param("ii", $task_id, $user_id);
            $check->execute();
            $check->store_result();
            $already_applied = $check->num_rows > 0;
        ?>
            <div class="task-card">
    <h3><?= htmlspecialchars($task['title']) ?></h3>

    <p><strong>Description:</strong></p>
    <p><?= nl2br(htmlspecialchars($task['description'])) ?></p>

    <p><strong>Budget:</strong> ₹<?= $task['budget'] ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($task['status']) ?></p>

    <?php if ($already_applied): ?>
        <button class="applied-btn" disabled>
            <i class="fas fa-check-circle"></i> Applied
        </button>
    <?php else: ?>
        <form action="apply_task.php" method="POST">
            <input type="hidden" name="task_id" value="<?= $task_id ?>">
            <button type="submit" class="apply-btn">
                <i class="fas fa-paper-plane"></i> Apply
            </button>
        </form>
    <?php endif; ?>
</div>

        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>No tasks available at the moment.</p>
<?php endif; ?>


    <div style="text-align:center;">
        <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

</body>
</html>
