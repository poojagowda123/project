
<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $budget = floatval($_POST['budget']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO tasks (title, description, budget, posted_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $title, $description, $budget, $user_id);

    if ($stmt->execute()) {
        $message = "✅ Task posted successfully!";
    } else {
        $message = "❌ Failed to post task.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Task - GigCircle</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins';
            background: linear-gradient(135deg,#020024,#00D4FF);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .form-box {
             background: rgba(255, 255, 255, 0.05);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        .form-box h2 {
            margin-bottom: 25px;
            font-size: 26px;
            color:rgba(255, 255, 255, 1);
            text-align: center;
        }
        .form-box form input,
.form-box form textarea {
    width: 90%;
    padding: 12px 15px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 1rem;
    background-color: #ffffff;
    color: #000000;
    transition: border-color 0.3s ease;
}

.form-box form input::placeholder,
.form-box form textarea::placeholder {
    color: #888;
}

.form-box form input:focus,
.form-box form textarea:focus {
    outline: none;
    border-color: #00D4FF;
}

       

        .form-box form textarea {
            height: 100px;
        }

        .form-box form button {
            background-color:rgba(28, 152, 173, 1);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s ease;
            margin-top: 10px;
        }

        .form-box form button:hover {
            background-color:#020024;
        }

        .form-box p {
            margin-top: 15px;
            text-align: center;
            color: #444;
        }

        .form-box a {
            display: block;
            text-align: center; 
            margin-top: 10px;
            color:rgba(255, 255, 255, 1);
            text-decoration: none;
            font-weight: 500;
        }

        .form-box a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Post a New Task</h2>
        <form method="POST">
            <input type="text" name="title" placeholder="Task Title" required><br>
            <textarea name="description" placeholder="Task Description" required></textarea><br>
            <input type="number" name="budget" step="0.01" placeholder="Budget (₹)" required><br>
            <button type="submit">Post Task</button>
        </form>
        <p><?= $message ?></p>
        <a href="dashboard.php">🔙 Back to Dashboard</a>
    </div>
</body>
</html>
