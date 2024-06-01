<?php
session_start();
require 'connection.php';

$currentUserId = $_SESSION['user_id'];
$searchQuery = isset($_GET['searchuser']) ? $_GET['searchuser'] : '';

if ($searchQuery) {
    $stmt = $pdo->prepare('SELECT id, name, profile, active FROM users WHERE name LIKE ? AND id != ?');
    $stmt->execute(['%' . $searchQuery . '%', $currentUserId]);
} else {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id != ?');
    $stmt->execute([$currentUserId]);
}

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($users) {
    foreach ($users as $user) {
        echo '
        <li class="user list-group-item" data-id="'.$user['id'].'" data-name="'.$user['name'].'" data-profile="'.$user['profile'].'" data-active="'. ($user['active'] ? 'active' : '') . '">
            <div id="user-list">
                <div class="user-profile">
                    <div class="user-avatar">
                        <img class="userProfile" src="'. $user['profile'].'" alt="'. htmlspecialchars($user['name']).'">
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-center">
                        <h4>'. htmlspecialchars($user['name']).'</h4>
                        <small class="mb-1 ml-1 activeStatus" >
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
?>
