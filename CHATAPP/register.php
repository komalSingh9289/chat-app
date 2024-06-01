<?php
session_start();
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $profile_image = null;

    // Handle file upload
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile']['tmp_name'];
        $fileName = $_FILES['profile']['name'];
        $fileSize = $_FILES['profile']['size'];
        $fileType = $_FILES['profile']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = 'uploads/';
            $dest_path = $uploadFileDir . $fileName;
            if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                echo 'error|There was some error moving the file to upload directory.';
                exit();
            }
            $profile_image = $dest_path;
        } else {
            echo 'error|Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
            exit();
        }
    }

    // Check if email already exists
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo 'error|Email already taken.';
        exit();
    }

    // Hash the password and insert the user into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password, profile) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, $hashed_password, $profile_image]);
        echo 'success|Registration successful! You can now <a href="login.php">login</a>.';
       
    } catch (PDOException $e) {
        echo 'error|'.$e->getMessage();
    }
   
}
?>
