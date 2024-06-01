<?php
session_start();
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $msgId = $_POST['id'];
    $currentUserId = $_SESSION['user_id'];

    // Check if the current user is the sender or recipient of the message
    $stmt = $pdo->prepare('SELECT * FROM msg WHERE id = ? AND (sender_id = ? OR recipient_id = ?)');
    $stmt->execute([$msgId, $currentUserId, $currentUserId]);
    $message = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($message) {
        // Delete the message
        $stmt = $pdo->prepare('DELETE FROM msg WHERE id = ?');
        $stmt->execute([$msgId]);

        echo json_encode(['status' => 'success', 'message' => 'Message deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'You are not authorized to delete this message']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
