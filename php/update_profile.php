<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($password) && $password === $confirm_password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET fullname=:fullname, email=:email, password=:password WHERE username=:username");
        $stmt->execute([
            'fullname' => $fullname,
            'email' => $email,
            'password' => $hashed,
            'username' => $username
        ]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET fullname=:fullname, email=:email WHERE username=:username");
        $stmt->execute([
            'fullname' => $fullname,
            'email' => $email,
            'username' => $username
        ]);
    }

    $_SESSION['fullname'] = $fullname;
    header("Location: user_homepage.php");
    exit();
}
