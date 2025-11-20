<?php
session_start();
require 'config.php';

$message = ''; // store error/success messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation patterns
    $usernamePattern = '/^[a-zA-Z0-9_]{4,20}$/';
    $emailPattern = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';

    // Error array
    $errors = [];

    // Required fields
    if (empty($fullname)) $errors[] = "Full Name is required.";
    if (empty($email)) $errors[] = "Email is required.";
    if (empty($username)) $errors[] = "Username is required.";
    if (empty($password)) $errors[] = "Password is required.";
    if (empty($confirm_password)) $errors[] = "Confirm Password is required.";

    // Field-specific validation
    if (!preg_match($usernamePattern, $username)) {
        $errors[] = "Username must be 4-20 characters and contain only letters, numbers, or underscores.";
    }

    if (!preg_match($emailPattern, $email)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if username or email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
    $stmt->execute(['username' => $username, 'email' => $email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = "Username or email already exists.";
    }

    if (empty($errors)) {
        // Hash password and insert
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, username, password) VALUES (:fullname, :email, :username, :password)");
        $stmt->execute([
            'fullname' => $fullname,
            'email' => $email,
            'username' => $username,
            'password' => $hashedPassword
        ]);

        $message = "<span class='success-message'>Registration successful! <a href='login.php'>Login here</a></span>";
    } else {
        // Combine all errors
        $message = "<span class='error-message'>" . implode("<br>", $errors) . "</span>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Community E-Reporting System</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../css/register_style.css">
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
                <li><a href="register.php" class="active">Register</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="form-section">
            <div class="form-box">
                <h2>Create an Account</h2>
                <?php if ($message) echo "<p>$message</p>"; ?>
                <form id="registerForm" method="POST">
                    <label>Full Name</label>
                    <input type="text" name="fullname" id="fullname" required>
                    <span class="error-message" id="fullnameError"></span>

                    <label>Email</label>
                    <input type="email" name="email" id="email" required>
                    <span class="error-message" id="emailError"></span>

                    <label>Username</label>
                    <input type="text" name="username" id="username" required>
                    <span class="error-message" id="usernameError"></span>

                    <label>Password</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" required>
                        <i class="toggle-password" id="togglePassword"></i> <!-- standard icon -->
                    </div>
                    <span class="error-message" id="passwordError"></span>

                    <label>Confirm Password</label>
                    <div class="password-container">
                        <input type="password" name="confirm_password" id="confirm_password" required>
                        <i class="toggle-password" id="toggleConfirmPassword"></i> <!-- standard icon -->
                    </div>
                    <span class="error-message" id="confirmPasswordError"></span>

                    <button type="submit" class="btn">Register</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        © 2025 Community E-Reporting System | Barangay West Rembo, Taguig City
    </footer>


    <script src="../js/register_script.js">
    </script>


</body>

</html>