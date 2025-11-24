<?php
session_start();
require_once "config.php";

// Check if logged in
$isLoggedIn = isset($_SESSION['username']);

if ($isLoggedIn) {

  // Fetch full user details (not just ID)
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
  $stmt->execute(['username' => $_SESSION['username']]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Extract user ID for reports
  $user_id = $user['id'];

  // Fetch user reports
  $stmtReports = $pdo->prepare("SELECT * FROM reports WHERE user_id = :user_id ORDER BY id DESC");
  $stmtReports->execute(['user_id' => $user_id]);
  $reports = $stmtReports->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports | Community E-Reporting System</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../css/profile_modal.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
  <header id="header">
    <nav>
      <div class="logo">
        <img src="../images/logo.png" alt="Logo">
        <h1>Community E-Reporting</h1>
      </div>
      <ul>
        <?php if ($isLoggedIn): ?>
          <li><a href="user_homepage.php">Home</a></li>
        <?php else: ?>
          <li><a href="../html/index.html">Home</a></li>
        <?php endif; ?>
        <li><a href="reports.php" class="active">Reports</a></li>
        <li><a href="officials.php">Officials</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">FAQ</a></li>

        <?php if ($isLoggedIn): ?>
          <!-- Profile Dropdown -->
          <li class="profile-menu">
            <i class="fas fa-user-circle profile-icon"></i>
            <ul class="dropdown">
              <li><a href="#" id="openProfileModal">Profile</a></li>
              <li><a href="../php/logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li><a href="../php/login.php">Login</a></li>
        <?php endif; ?>
      </ul>

    </nav>
  </header>

  <main>

    <!-- SUBMIT FORM -->
    <section class="form-section">
      <div class="form-box">
        <h2>Submit a New Report</h2>

        <?php if (!$isLoggedIn): ?>
          <p class="warning">You must <a href="../php/login.php">log in</a> before submitting a report.</p>
        <?php endif; ?>

        <form action="../php/submit_report.php" method="POST" enctype="multipart/form-data">
          <label>Category</label>
          <select name="category" <?= $isLoggedIn ? '' : 'disabled' ?> required>
            <option value="">Select category</option>
            <option value="noise">Noise Complaint</option>
            <option value="garbage">Garbage Issue</option>
            <option value="streetlight">Street Light Problem</option>
            <option value="safety">Safety Concern</option>
            <option value="others">Others</option>
          </select>

          <label>Description</label>
          <textarea name="description" rows="5" placeholder="Describe the issue..." <?= $isLoggedIn ? '' : 'disabled' ?> required></textarea>

          <label>Location</label>
          <input type="text" name="location" placeholder="Enter exact location" <?= $isLoggedIn ? '' : 'disabled' ?> required>

          <label>Upload Image (optional)</label>
          <input type="file" name="image" accept="image/*" <?= $isLoggedIn ? '' : 'disabled' ?>>

          <button type="submit" class="btn" <?= $isLoggedIn ? '' : 'disabled' ?>>Submit Report</button>
        </form>
      </div>
    </section>

    <!-- REPORT LIST (ONLY IF LOGGED IN) -->
    <?php if ($isLoggedIn): ?>
      <section class="report-section">
        <h2>Your Submitted Reports</h2>
        <table class="report-table">
          <thead>
            <tr>
              <th>Report ID</th>
              <th>Category</th>
              <th>Description</th>
              <th>Status</th>
              <th>Date Submitted</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($reports)): ?>
              <?php foreach ($reports as $row): ?>
                <tr>
                  <td>#<?= htmlspecialchars($row['id']); ?></td>
                  <td><?= htmlspecialchars(ucfirst($row['category'])); ?></td>
                  <td><?= htmlspecialchars($row['description']); ?></td>
                  <td><span class="status <?= strtolower($row['status']); ?>"><?= htmlspecialchars($row['status']); ?></span></td>
                  <td><?= date('M d, Y', strtotime($row['created_at'])); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5">No reports submitted yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </section>
    <?php endif; ?>

  </main>

  <footer>
    © 2025 Community E-Reporting System | Barangay West Rembo, Taguig City
  </footer>
  <?php if ($isLoggedIn): ?>
    <div id="profileModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Profile</h2>

        <form method="POST" action="update_profile.php">
          <label>Full Name</label>
          <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']); ?>" required>

          <label>Email</label>
          <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

          <label>New Password</label>
          <input type="password" name="password" placeholder="Leave blank">

          <label>Confirm Password</label>
          <input type="password" name="confirm_password" placeholder="Leave blank">

          <button type="submit" class="btn">Save Changes</button>
        </form>
      </div>
    </div>
  <?php endif; ?>


  <script src="../js/modal.js"></script>

  </script>

</body>

</html>