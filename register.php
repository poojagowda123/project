<?php
include 'db.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $check->bind_param("ss", $email, $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "⚠️ Username or email already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            $message = "✅ Registration successful! <a href='login.php'>Login here</a>";
        } else {
            $message = "❌ Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - GigCircle</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

         body {
            font-family: 'Poppins', sans-serif;
            background: #020024;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            scroll-behavior: smooth;
        }

       .form-box {
            position: relative;
            width: 100%;
            max-width: 420px;
            padding: 40px 30px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            box-shadow: 0 8px 25px rgba(8, 24, 175, 0.3);
            z-index: 2;
        }

 .form-box h2 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
            color: #ffffff;
        }

        .form-box form input {
            width: 380px;
            padding: 12px 16px;
            margin: 10px 0;
            border: 1px solid white;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            font-size: 1rem;
            outline: none;
        }


        .form-box form button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #00D4FF;
            color: #000;
            font-weight: bold;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        .form-box form button[type="submit"]:hover {
            background-color: #00bde6;
        }
        .form-box p {
            margin-top: 15px;
            font-size: 0.95rem;
            text-align: center;
            color: #eee;
        }

        .form-box p a {
            color: #ffffff;
            font-weight: 600;
            text-decoration: none;
        }

        .form-box p a:hover {
            text-decoration: underline;
        }

        .form-box p:first-of-type {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>✨ Create Your Account</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Register</button>
        </form>
        <p><?= $message ?></p>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
