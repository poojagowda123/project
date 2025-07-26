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
  <title>My Posted Tasks - GigCircle</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
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
      color: #ffffffff;
      margin-bottom: 30px;
    }
    .task-card {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .task-card h3 {
      margin-bottom: 10px;
      color: #222;
    }
    .task-card p {
      margin: 5px 0;
      font-size: 14px;
    }
    form {
      display: inline;
    }
    button {
      background: #020024;
      color: #fff;
      border: none;
      padding: 6px 12px;
      border-radius: 5px;
      cursor: pointer;
      margin: 5px 0;
    }
    button:hover {
      background: #494597ff;
    }
    .download-link {
      color: #8a097fff;
      text-decoration: none;
    }
    .download-link:hover {
      text-decoration: underline;
    }
    .status-paid {
      color: green;
      margin-top: 10px;
    }
    .back-link {
      display: block;
      text-align: center;
      margin-top: 30px;
      color: #ffffffff;
      text-decoration: none;
    }
    .back-link:hover {
      text-decoration: underline;
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      padding-top: 100px;
      left: 0; top: 0; width: 100%; height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.7);
    }
    .modal-content {
      margin: auto;
      display: block;
      max-width: 300px;
    }
    .close {
      position: absolute;
      top: 60px;
      right: 50px;
      color: #fff;
      font-size: 40px;
      font-weight: bold;
      cursor: pointer;
    }
  </style>
</head>
<body>
<div class="container">
  <h2><i class="fas fa-tasks"></i> My Posted Tasks</h2>

  <?php
  $stmt = $conn->prepare("SELECT * FROM tasks WHERE posted_by = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $tasks = $stmt->get_result();

  // QR cache to avoid duplicate queries
  $qrCache = [];

  if ($tasks->num_rows > 0):
    while ($task = $tasks->fetch_assoc()):
  ?>
    <div class="task-card">
      <h3><?= htmlspecialchars($task['title']) ?></h3>

<?php
// Count applications for this task
$appCountStmt = $conn->prepare("SELECT COUNT(*) AS total FROM applications WHERE task_id = ?");
$appCountStmt->bind_param("i", $task['id']);
$appCountStmt->execute();
$appCountResult = $appCountStmt->get_result()->fetch_assoc();
$appCount = $appCountResult['total'] ?? 0;
$appCountStmt->close();
?>

<form action="delete_task.php" method="POST" style="float: right;" onsubmit="return confirm('Are you sure you want to delete this task?');">
  <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
  <button type="submit"
    <?= $appCount > 0 ? 'disabled title="Cannot delete - applications exist"' : '' ?>
    style="background: <?= $appCount > 0 ? '#ccc' : '#d11a2a' ?>; cursor: <?= $appCount > 0 ? 'not-allowed' : 'pointer' ?>;">
    <i class="fas fa-trash"></i> Delete
  </button>
</form>

      <p><?= nl2br(htmlspecialchars($task['description'])) ?></p>
      <p><strong>Budget:</strong> ₹<?= $task['budget'] ?></p>
      <p><strong>Status:</strong> <?= htmlspecialchars($task['status']) ?></p>

      <h4>Applications Received:</h4>
      <?php
      $appStmt = $conn->prepare("
        SELECT a.id AS app_id, a.status, u.username, u.id AS user_id
        FROM applications a
        JOIN users u ON a.applied_by = u.id
        WHERE a.task_id = ?
      ");
      $appStmt->bind_param("i", $task['id']);
      $appStmt->execute();
      $applications = $appStmt->get_result();

      if ($applications->num_rows > 0):
        while ($app = $applications->fetch_assoc()):
        //   echo "<p><strong>" . htmlspecialchars($app['username']) . "</strong> - Status: " . htmlspecialchars($app['status']) . "</p>";
        echo "<p>
  <strong>" . htmlspecialchars($app['username']) . "</strong> - Status: " . htmlspecialchars($app['status']) . "
  &nbsp;|&nbsp;
  <a class='download-link' href='view_profile.php?user_id=" . $app['user_id'] . "' target='_blank'>👤 View Profile</a>
</p>";

          if ($app['status'] === 'pending') {
      ?>
        <form action="update_status.php" method="POST">
          <input type="hidden" name="app_id" value="<?= $app['app_id'] ?>">
          <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
          <button type="submit" name="action" value="approve">✅ Approve</button>
          <button type="submit" name="action" value="reject" style="background:gray;">❌ Reject</button>
        </form>
      <?php
          }
        endwhile;
      else:
        echo "<p>No applications yet.</p>";
      endif;

      // Check submission
      $subStmt = $conn->prepare("SELECT * FROM submissions WHERE task_id = ?");
      $subStmt->bind_param("i", $task['id']);
      $subStmt->execute();
      $subs = $subStmt->get_result();

      if ($subs->num_rows > 0):
        while ($sub = $subs->fetch_assoc()):
          $freelancerId = $sub['submitted_by'];

          // Cache QR code
          if (!isset($qrCache[$freelancerId])) {
            $qrStmt = $conn->prepare("SELECT qr_code FROM users WHERE id = ?");
            $qrStmt->bind_param("i", $freelancerId);
            $qrStmt->execute();
            $qrResult = $qrStmt->get_result()->fetch_assoc();
            $qrCache[$freelancerId] = $qrResult['qr_code'] ?? '';
            $qrStmt->close();
          }
          $qrPath = $qrCache[$freelancerId];
      ?>
        <h4>Submission Received:</h4>
        <p><strong>From User ID:</strong> <?= $freelancerId ?></p>
        <p><strong>Notes:</strong> <?= nl2br(htmlspecialchars($sub['notes'])) ?></p>
        <a href="<?= htmlspecialchars($sub['file_path']) ?>" class="download-link" download>Download File</a><br>

        <?php if ($qrPath): ?>
          <button onclick="showQR('<?= htmlspecialchars($qrPath) ?>')"> Show QR</button>
        <?php else: ?>
          <p>No QR uploaded.</p>
        <?php endif; ?>

        <?php
          // Check payment
          $payStmt = $conn->prepare("SELECT * FROM payments WHERE task_id = ? AND paid_to = ?");
          $payStmt->bind_param("ii", $task['id'], $freelancerId);
          $payStmt->execute();
          $paymentDone = $payStmt->get_result()->num_rows > 0;

          if (!$paymentDone):
        ?>
          <form action="make_payment.php" method="POST">
            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
            <input type="hidden" name="paid_to" value="<?= $freelancerId ?>">
            <input type="hidden" name="amount" value="<?= $task['budget'] ?>">
            <button type="submit"><i class="fas fa-check"></i> Confirm Payment Done</button>
          </form>
        <?php else: ?>
          <p class="status-paid"><i class="fas fa-check-circle"></i> Payment Completed</p>
        <?php endif; ?>

      <?php endwhile; endif; ?>
    </div>
  <?php endwhile; else: ?>
    <p style="text-align:center;">You haven't posted any tasks yet.</p>
  <?php endif; ?>

  <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

<!-- QR Modal -->
<div id="qrModal" class="modal">
  <span class="close" onclick="closeQR()">&times;</span>
  <img class="modal-content" id="qrImage">
</div>

<script>
function showQR(src) {
  document.getElementById('qrImage').src = src;
  document.getElementById('qrModal').style.display = "block";
}
function closeQR() {
  document.getElementById('qrModal').style.display = "none";
}



document.addEventListener("DOMContentLoaded", function () {
    if (window.location.hash) {
        const el = document.querySelector(window.location.hash);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
            el.style.border = '2px solid #00D4FF';  // highlight it briefly
            setTimeout(() => el.style.border = '', 3000);
        }
    }
});


</script>

</body>
</html>