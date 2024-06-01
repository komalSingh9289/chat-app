<?php
session_start();
require 'connection.php';

$currentUserId = $_SESSION['user_id'];

// Log POST and FILES data for debugging
file_put_contents('debug.log', print_r($_POST, true), FILE_APPEND);
file_put_contents('debug.log', print_r($_FILES, true), FILE_APPEND);

$receiverId = $_POST['receiver_id'];
$message = $_POST['message'];
$file_path = null;

if (!$receiverId && !$message && !$file_path) {
    echo json_encode(['status' => 'error', 'message' => 'Message not sent']);
    exit();
}

if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $target_dir = "attachFiles/";
    $file_name = basename($_FILES["file"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $file_path = $target_file;
    } else {
        die("File upload failed");
    }
}

$stmt = $pdo->prepare("INSERT INTO msg (sender_id, recipient_id, content, file) VALUES (?, ?, ?, ?)");
$stmt->execute([$currentUserId, $receiverId, $message, $file_path]);
echo json_encode(['status' => 'success', 'message' => 'Message sent successfully']);
?>
