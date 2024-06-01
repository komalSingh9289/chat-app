<?php
session_start();
require 'connection.php';

if (isset($_GET['searchuser'])) {
    $searchQuery = $_GET['searchuser'];
    $currentUserId = $_SESSION['user_id'];

    $stmt = $pdo->prepare('SELECT id, name, profile, active FROM users WHERE name LIKE ? AND id != ?');
    $stmt->execute(['%' . $searchQuery . '%', $currentUserId]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($users) {
        foreach ($users as $user) {
            echo '
            <li class="user list-group-item" data-id="'.$user['id'].'" data-name="'.$user['name'].'" data-profile="'.$user['profile'].'" data-active="'. ($user['active'] ? 'active' : '') . '">
                <div id="user-list">
                    <div class="user-profile">
                        <div class="user-avatar">
                            <img src="'. $user['profile'].'" alt="'. htmlspecialchars($user['name']).'">
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <h4>'. htmlspecialchars($user['name']).'</h4>
                            <small class="mt-1 ml-1">
                                '. ($user['active'] ? '<p class="active-status">(active)</p>' : '') . '
                            </small>
                        </div>
                    </div>
                </div>
            </li>
            ';
        }
    } else {
        echo '<li class="list-group-item">No users found.</li>';
    }
}
?>
