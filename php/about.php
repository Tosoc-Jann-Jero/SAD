<?php
session_start();

// Check login
$isLoggedIn = isset($_SESSION['username']);

if ($isLoggedIn) {
  require_once "../php/config.php";

  // Fetch user details
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
  $stmt->execute(['username' => $_SESSION['username']]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About | Community E-Reporting System</title>

  <!-- MAIN STYLES -->
  <link rel="stylesheet" href="../style.css">

  <!-- PROFILE MODAL STYLES -->
  <link rel="stylesheet" href="../css/profile_modal.css">

  <!-- ICONS -->
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
        <li><a href="../php/reports.php">Reports</a></li>
        <li><a href="officials.php">Officials</a></li>
        <li><a href="about.php" class="active">About</a></li>
        <li><a href="contact.php">FAQ</a></li>

        <?php if ($isLoggedIn): ?>
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
    <section class="about-section">
      <h2>About the System</h2>

      <p>
        The Community E-Reporting and Monitoring System is designed to help residents
        of Barangay West Rembo report peace and order concerns easily through an online platform.
      </p>

      <p>
        The system provides transparency, faster communication, and accountability
        between barangay officials and residents.
      </p>

      <h3>Objectives</h3>
      <ul>
        <li>To simplify the process of reporting community issues.</li>
        <li>To improve the response time of barangay officials.</li>
        <li>To maintain records of reports for tracking and evaluation.</li>
        <li>To foster cooperation between the barangay and residents.</li>
      </ul>
    </section>
  </main>

  <footer>
    © 2025 Community E-Reporting System | Barangay West Rembo, Taguig City
  </footer>

  <!-- PROFILE MODAL -->
  <?php if ($isLoggedIn): ?>
    <div id="profileModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Profile</h2>

        <form method="POST" action="../php/update_profile.php">
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


</body>

</html>