<?php
session_start();

// User must be logged in to submit a report
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please login first");
    exit;
}

require_once "config.php"; // your PDO connection

// Validate required fields
if (
    !isset($_POST['category']) ||
    !isset($_POST['description']) ||
    !isset($_POST['location'])
) {
    header("Location: ../pages/reports.php?error=Missing required fields");
    exit;
}

$user_id     = $_SESSION['user_id'];
$category    = trim($_POST['category']);
$description = trim($_POST['description']);
$location    = trim($_POST['location']);
$status      = "Pending";  // default status

// ------------------------------
// IMAGE UPLOAD HANDLING
// ------------------------------
$imagePath = NULL;

if (!empty($_FILES['image']['name'])) {

    $targetDir = "../uploads/reports/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $fileName;

    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $validTypes = ["jpg", "jpeg", "png", "gif"];

    // Validate file type
    if (!in_array($fileType, $validTypes)) {
        header("Location: ../pages/reports.php?error=Invalid image format");
        exit;
    }

    // Validate size (5MB max)
    if ($_FILES["image"]["size"] > 5000000) {
        header("Location: ../pages/reports.php?error=Image too large (max 5MB)");
        exit;
    }

    // Upload the file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $imagePath = $fileName;
    } else {
        header("Location: ../pages/reports.php?error=Image upload failed");
        exit;
    }
}

// ------------------------------
// INSERT INTO DATABASE
// ------------------------------
try {

    $sql = "INSERT INTO reports (user_id, category, description, location, image, status, created_at)
            VALUES (:user_id, :category, :description, :location, :image, :status, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id'     => $user_id,
        ':category'    => $category,
        ':description' => $description,
        ':location'    => $location,
        ':image'       => $imagePath,
        ':status'      => $status
    ]);

    header("Location: reports.php?success=Report submitted successfully");

} catch (Exception $e) {

    header("Location: reports.php?error=Database error: " . $e->getMessage());
}
?>
