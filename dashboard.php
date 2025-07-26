<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - GigCircle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg,#090979,#020024,#00D4FF);
            color: #333;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

         .sidebar {
                width: 200px;
                background: rgba(255, 255, 255, 0.01);
                background-size: 300% 300%;
                padding: 30px 20px;
                display: flex;
                flex-direction: column;
            }


        .sidebar .logo {
            font-size: 24px;
            font-weight: 600;
            color:rgb(255, 255, 255);
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar .nav-links {
            list-style: none;
            padding: 0;
        }

        .sidebar .nav-links li {
            margin: 15px 0;
        }

        .sidebar .nav-links li a {
            text-decoration: none;
            color: white;
            font-weight: 500;
            font-size: 15px;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .sidebar .nav-links li a i {
            margin-right: 10px;
            font-size: 16px;
            color:rgb(255, 255, 255);
        }

        .sidebar .nav-links li a:hover {
            color:rgb(83, 83, 83);
        }

        .main-content {
            flex: 1;
            padding: 40px;
            color:white;
        }

        .welcome {
            font-size: 1.7rem;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .trending-section {
            margin-top: 30px;
        }

        .trending-section h3 {
            font-size: 22px;
            color: white;
            margin-bottom: 20px;
        }

        .task-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.05);
        }

        .task-card h4 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #333;
        }

        .task-card p {
            margin: 5px 0;
            color: #555;
            font-size: 14px;
        }

        .task-card small {
            color: #888;
        }

        .view-more {
            display: inline-block;
            margin-top: 10px;
            color:rgb(255, 255, 255);
            font-weight: 500;
            text-decoration: none;
        }

        .view-more:hover {
            text-decoration: underline;
        }
        .trending-section .task-card {
    opacity: 0;
    transform: translateX(100px);
    transition: all 0.8s ease-in-out;
}

.trending-section .task-card.show {
    opacity: 1;
    transform: translateX(0);
}

    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar">
        <div class="logo">GigCircle</div>
        <ul class="nav-links">
            <li><a href="post_task.php"><i class="fas fa-plus-circle"></i> Post a New Task</a></li>
            <li><a href="view_tasks.php"><i class="fas fa-briefcase"></i> View Tasks</a></li>
            <li><a href="my_submissions.php"><i class="fas fa-upload"></i> My Submissions</a></li>
            <li><a href="mytask.php"><i class="fas fa-list"></i> My Posted Tasks</a></li>
            <li><a href="view_applications.php"><i class="fas fa-envelope-open-text"></i> Task Applications</a></li>
            <li><a href="notifications.php"> <i class="fas fa-bell"></i>View Notifications</a></li>

            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome">
                <span id="typewriter-text"></span>
        </div>


        <div class="trending-section">
            <h3>Trending Tasks</h3>

            <?php
            $query = "SELECT * FROM tasks WHERE status = 'open' AND posted_by != ? ORDER BY id DESC LIMIT 3";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
                while ($task = $result->fetch_assoc()):
            ?>
                <div class="task-card">
                    <h4><?= htmlspecialchars($task['title']) ?></h4>
                    <p><?= htmlspecialchars(substr($task['description'], 0, 80)) ?>...</p>
                    <p><strong>Budget:</strong> ₹<?= $task['budget'] ?></p>
                    <small>Status: <?= htmlspecialchars($task['status']) ?></small>
                </div>
            <?php endwhile; ?>
                <a href="view_tasks.php" class="view-more">→ View All Tasks</a>
            <?php else: ?>
                <p>No trending tasks at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    const username = "<?= htmlspecialchars($username) ?>";
    const text = `Welcome, ${username}`;
    let index = 0;

    function typeWriter() {
        if (index < text.length) {
            document.getElementById("typewriter-text").innerHTML += text.charAt(index);
            index++;
            setTimeout(typeWriter, 100);
        }
    }

    document.addEventListener("DOMContentLoaded", typeWriter);
     const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
            }
        });
    }, {
        threshold: 0.3
    });

    document.querySelectorAll('.task-card').forEach(card => {
        observer.observe(card);
    });
</script>

</body>
</html>
