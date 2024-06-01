<?php
session_start();
require 'connection.php';

$receiver_id = $_GET['receiver_id'];
$sender_id = $_SESSION['user_id'];

// Fetch receiver's username
$stmt = $pdo->prepare('SELECT name FROM users WHERE id = ?');
$stmt->execute([$receiver_id]);
$receiver = $stmt->fetch(PDO::FETCH_ASSOC);

$receiver_name = $receiver['name'];

// Fetch messages
$stmt = $pdo->prepare('
    SELECT msg.*, u.name AS sender_name 
    FROM msg
    JOIN users u ON msg.sender_id = u.id
    WHERE (msg.sender_id = ? AND msg.recipient_id = ?) OR (msg.sender_id = ? AND msg.recipient_id = ?)
    ORDER BY msg.timestamp
');
$stmt->execute([$sender_id, $receiver_id, $receiver_id, $sender_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($messages) {
    foreach ($messages as $message) {
        $time = (new DateTime($message['timestamp']))->format('h:i A'); 

        $fileMarkup = '';
        if (!empty($message['file'])) {
            $filePath = htmlspecialchars($message['file']);
            $fileName = basename($filePath); // Get the file name for the download attribute
            $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION)); // Get the file extension

            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                // Display image files
                $fileMarkup = '
                    <div style="position:relative;">
                        <img class="imgDownload" src="' . $filePath . '" alt="Image" 
                        data-toggle="modal" data-target="#imageModal" style="max-width: 100%; margin-top: 10px; filter: blur(2px);">
                        
                        <a class="downloadImg nav-link" href="' . $filePath . '" download="' . $fileName . '" style="display: block; margin-top: 5px;">
                        <i class="fa fa-download text-success" style="font-size:2rem;" aria-hidden="true" ></i></a>
                    </div>
                ';
            }elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg'])) {
                // Display audio files
                $fileMarkup = '
                    <div style="margin-top: 10px;">
                        <audio controls style="width: 100%;">
                            <source src="' . $filePath . '" type="audio/' . $fileExtension . '">
                            Your browser does not support the audio element.
                        </audio>
                        
                    </div>
                ';
            }elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg'])) {
                // Display video files
                $fileMarkup = '
                    <div style="margin-top: 10px;">
                        <video controls style="width: 100%;">
                            <source src="' . $filePath . '" type="video/' . $fileExtension . '">
                            Your browser does not support the video element.
                        </video>
                        
                    </div>
                ';
            }else {
                // Display links to other types of files with an icon preview
                $fileIcon = '<i class="fa fa-file" aria-hidden="true"></i>';
                if (in_array($fileExtension, ['pdf'])) {
                    $fileIcon = '<i class="fa fa-file-pdf" aria-hidden="true"></i>';
                } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                    $fileIcon = '<i class="fa fa-file-word" aria-hidden="true"></i>';
                } elseif (in_array($fileExtension, ['xls', 'xlsx'])) {
                    $fileIcon = '<i class="fa fa-file-excel" aria-hidden="true"></i>';
                }

                $fileMarkup = '
                    <div style="margin-top: 10px;">
                        ' . $fileIcon . ' <a href="' . $filePath . '" download="' . $fileName . '">' . $fileName . '</a>
                    </div>
                ';
            }
        }


        if ($message['sender_id'] == $sender_id) {
            // Message sent by the logged-in user
            echo '
                <div class="chat-message sender-box">
                    <small>' . $time . '
                    <i class="fa fa-trash text-danger float-right mr-2  delete-msg" data-id="' . $message['id'] . '""aria-hidden="true"></i>
                   </small>
                    <div class="sender-message p-2">
                        <strong>Me:</strong>
                        ' . htmlspecialchars($message['content'])  . $fileMarkup . '
                    </div>
                </div>
            ';
          
        } else{
            echo '
                <div class="chat-message recipient-box mb-2">
                   <small>' . $time . '
                    <i class="fa fa-trash text-danger float-right mr-2  delete-msg" data-id="' . $message['id'] . '""aria-hidden="true"></i>
                   </small>
                    <div class="recipient-message p-2">
                        <strong>' . htmlspecialchars($receiver_name) . ':</strong>
                        ' . htmlspecialchars($message['content']) .  $fileMarkup . '
                    </div>
                </div>
            ';
        }
    }
}else {
    echo 'start messaging';
}




