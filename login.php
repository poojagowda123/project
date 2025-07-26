<?php
include 'db.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "❌ Invalid password.";
        }
    } else {
        $message = "⚠️ Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - GigCircle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #090979 , #00D4FF );
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .form-box form input::placeholder {
            color: #ccc;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #ffffffff;
            font-size: 1rem;
            cursor: pointer;
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
            color: #ffbaba;
        }

        .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100vh;
    width: 100%;
    padding: 40px;
    box-sizing: border-box;
}

.left-image {
    flex: 1;
    text-align: center;
}

.left-image img {
    max-width: 700px;
    height: 500px;
    border-radius: 16px;
}

.form-box {
    flex: 1;
    max-width: 420px;
    margin-left: 20px;
}

    </style>
</head>
<body>
  <div class="container">
    <!-- <div class="left-image">
            <img src="assets/images/grad.png" alt="Login Illustration">
        </div> -->
    <div class="form-box">
        <h2>Welcome Back</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>

            <div class="password-wrapper">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <button type="button" class="toggle-password" onclick="togglePassword()">
                    <i id="eyeIcon" class="fa-solid fa-eye"></i>
                </button>
            </div>

            <button type="submit">Login</button>
        </form>
        <p><?= $message ?></p>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
  </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
