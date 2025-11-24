<?php
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)");
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'message' => $message
    ]);


    header("Location: ../php/contact.php?success=1");
    exit();
}
