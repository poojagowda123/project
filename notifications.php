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
<html>
<head>
    <title>Notifications - GigCircle</title>
    <style>
       body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg,#090979,#020024,#00D4FF);
            color: #333;
        }
        .notification {
            background: #fff;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 5px solid #007bff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        h2 {
            margin-bottom: 30px;
            color: white;
        }
          .view-btn {
            background: #005eff;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .view-btn:hover {
            background: #0041b8;
        }
    </style>
</head>
<body>
    <h2>Your Task Notifications</h2>

    <?php
    $query = "
        SELECT a.*, t.title AS task_title, u.username AS applicant 
        FROM applications a 
        JOIN tasks t ON a.task_id = t.id 
        JOIN users u ON a.applied_by = u.id 
        WHERE t.posted_by = ? 
        ORDER BY a.applied_at DESC
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
        <div class="notification">
            <strong><?= htmlspecialchars($row['applicant']) ?></strong> applied for your task: 
            <em><?= htmlspecialchars($row['task_title']) ?></em> on 
            <small><?= htmlspecialchars($row['applied_at']) ?></small>.

               <a class="view-btn" href="mytask.php?app_id=<?= $row['task_id'] ?>">View</a>
        </div>
    <?php
        endwhile;
    else:
        echo "<p>No new notifications.</p>";
    endif;
    ?>
</body>
</html>
