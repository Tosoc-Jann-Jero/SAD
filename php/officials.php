<?php
session_start();
require "config.php";

$isLoggedIn = isset($_SESSION['username']);

// If logged in, fetch user info
if ($isLoggedIn) {
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->execute(['username' => $_SESSION['username']]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Officials | Community E-Reporting System</title>

  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../css/officials_style.css">
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
        <li><a href="../php/reports.php">Reports</a></li>
        <li><a href="officials.php" class="active">Officials</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">FAQ</a></li>

        <?php if ($isLoggedIn): ?>
          <li class="profile-menu">
            <i class="fas fa-user-circle profile-icon" id="openProfileModal"></i>
            <ul class="dropdown">
              <li><a href="#" id="openProfileModalLink">Profile</a></li>
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
    <section class="officials-section">
      <h2>Barangay Officials</h2>

      <!-- Captain -->
      <div class="captain-card">
        <img src="../images/Hon.Bes.png" alt="Hon. Leo Bes">
        <h3>Hon. Leo Bes</h3>
        <p>Barangay Captain</p>
      </div>

      <!-- Kagawads -->
      <div class="kagawads-grid">
        <div class="official-card">
          <img src="../images/Kag.Advincula.png" alt="Kag. Joel Advincula">
          <h3>Kag. Joel Advincula</h3>
          <p>Kagawad</p>
        </div>
        <div class="official-card">
          <img src="../images/Kag.Agagon.png" alt="Kag. Jamila Agagon">
          <h3>Kag. Jamila Agagon</h3>
          <p>Kagawad</p>
        </div>
        <div class="official-card">
          <img src="../images/Kag.Cardinal.png" alt="Kag. Tess Cardinal">
          <h3>Kag. Tess Cardinal</h3>
          <p>Kagawad</p>
        </div>
        <div class="official-card">
          <img src="../images/Kag.Jarabata.png" alt="Kag. Danny Jarabata">
          <h3>Kag. Danny Jarabata</h3>
          <p>Kagawad</p>
        </div>
        <div class="official-card">
          <img src="../images/Kag.Neri.png" alt="Kag. Rhod Neri">
          <h3>Kag. Rhod Neri</h3>
          <p>Kagawad</p>
        </div>
      </div>

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

        <form method="POST" action="update_profile.php">
          <label>Full Name</label>
          <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']); ?>" required>

          <label>Email</label>
          <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

          <label>New Password</label>
          <input type="password" name="password" placeholder="Leave blank">

          <label>Confirm Password</label>
          <input type="password" name="confirm_password" placeholder="Leave blank">

          <button class="btn" type="submit">Save Changes</button>
        </form>
      </div>
    </div>
  <?php endif; ?>

  <script src="../js/modal.js"></script>

</body>

</html>