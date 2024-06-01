<?php
require 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT active FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        echo  $user['active'];
    } else {
        echo  0;
    }
} else {
    echo 'something went wrong';
}
?>
