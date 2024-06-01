<?php
session_start();
require 'connection.php';

if (isset($_SESSION['user_id'])) {
    // Update active status
    $stmt = $pdo->prepare('UPDATE users SET active = 0 WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);

    // Destroy session
    session_unset();
    session_destroy();
}

header('Location: login.php');
exit();
?>
