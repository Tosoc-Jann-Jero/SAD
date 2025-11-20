<?php
session_start();
require 'config.php';

$message = ""; // show error on page

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['fullname'];
        header("Location: user_homepage.php");
        exit();
    } else {
        $message = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Community E-Reporting System</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <header id="header">
    <nav>
      <div class="logo">
        <img src="../images/logo.png" alt="Logo">
        <h1>Community E-Reporting</h1>
      </div>
      <ul>
        <li><a href="../html/index.html">Home</a></li>
        <li><a href="../html/reports.html">Reports</a></li>
        <li><a href="../html/submit.html">Submit</a></li>
        <li><a href="../html/officials.html">Officials</a></li>
        <li><a href="../html/about.html">About</a></li>
        <li><a href="../html/contact.html">Contact</a></li>
        <li><a href="login.php" class="active">Login</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section class="form-section">
      <div class="form-box">

        <h2>Login</h2>

        <?php if ($message): ?>
          <p style="color: red;"><?= $message ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
          <label>Username</label>
          <input type="text" name="username" placeholder="Enter your username" required>

          <label>Password</label>
          <input type="password" name="password" placeholder="Enter your password" required>

          <button type="submit" class="btn">Login</button>
          <p>Don't have an account? <a href="register.php">Register here</a></p>
        </form>

      </div>
    </section>
  </main>

  <footer>
    © 2025 Community E-Reporting System | Barangay West Rembo, Taguig City
  </footer>
</body>
</html>
