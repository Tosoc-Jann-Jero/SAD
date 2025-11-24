<?php
session_start();
require 'config.php';


// Only ADMIN can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";
$error = "";

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($fullname) || empty($username) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Check if username already exists
        $check = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $check->execute(['username' => $username]);

        if ($check->rowCount() > 0) {
            $error = "Username is already taken.";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert into DB with role=staff
            $stmt = $pdo->prepare("
                INSERT INTO users (fullname, username, password, role) 
                VALUES (:fullname, :username, :password, 'staff')
            ");

            $stmt->execute([
                'fullname' => $fullname,
                'username' => $username,
                'password' => $hashedPassword
            ]);

            $message = "Staff account created successfully!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Staff | Admin</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 40px;
        }

        .box {
            max-width: 400px;
            background: white;
            padding: 25px;
            margin: auto;
            box-shadow: 0px 0px 10px #aaa;
            border-radius: 6px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #2b6cb0;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .success {
            color: green;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="box">
        <h2>Create Staff Account</h2>

        <?php if ($message): ?>
            <p class="success"><?= $message ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Full Name</label>
            <input type="text" name="fullname" required>

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Create Staff</button>
        </form>
    </div>

</body>

</html>