<?php
session_start();
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['name'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE name = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['profile'] = $user['profile'];
          // Update active status
          $stmt = $pdo->prepare('UPDATE users SET active = 1 WHERE id = ?');
          $stmt->execute([$user['id']]);

        echo 'success|Login successful. Redirecting to chat...';
    } else {
        echo 'error|Invalid username or password';
    }
}
?>
