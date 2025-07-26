<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<style>
    .navbar {
       background: rgba(86, 104, 209, 0.01);
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
      
        font-family: 'Poppins', sans-serif;
    }

    .navbar a {
        color: #ffffffff;
        text-decoration: none;
        margin-left: 20px;
        font-weight: 500;
        transition: color 0.2s ease-in-out;
    }

    .navbar a:hover {
        color: #003cffff;
    }

    .navbar .logo {
        font-size: 22px;
        font-weight: 600;
        color: #333;
    }

    .navbar .menu {
        display: flex;
        align-items: center;
    }
</style>

<div class="navbar">
<!-- <div class="logo"> <a href="in    dex.php" style="color:#f27059; text-decoration: none;">GigCircle</a></div> -->
    <div class="menu">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</div>
