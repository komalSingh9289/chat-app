<?php
session_start();
require 'connection.php'; // Database connection file


    $userId = $_SESSION['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $profilePath = '';

    if (isset($_FILES['profile']) && $_FILES['profile']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $profilePath = $target_dir . basename($_FILES["profile"]["name"]);
        move_uploaded_file($_FILES["profile"]["tmp_name"], $profilePath);
    }

    try {
        // Update user profile in the database
        if ($profilePath) {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, profile = ? WHERE id = ?");
            $stmt->execute([$name, $email, $profilePath, $userId]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->execute([$name, $email, $userId]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }

?>
