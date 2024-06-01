<?php
session_start();

if (!isset($_SESSION["username"])) {
    echo "
    <div class='alert alert-danger' role='alert'>
        You need to login fisrt before accessing this page.
        <strong>
        <h5>Login here- <a href='Login.php'>Login</a></h5>
        </strong>
    </div>
    ";
    exit();
}
$userId = $_SESSION['user_id'];

// Fetch current user data
include ("connection.php");

$stmt = $pdo->prepare("SELECT name, email, profile FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Error: User not found.";
    exit();
}
include ("partials/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <!-- Side Panel -->
        <div class="col-md-3 vh-100" id="side-panel">
            <div class="row p-3 mt-2">
                <div class="col-md-7">
                    <div class="d-flex">
                        <div class="user-avatar">
                            <img src="<?php echo $user['profile']; ?>" alt="User 1">
                        </div>
                        <h4 class="mt-3 mb-3 " id="username">
                            <?php echo $user['username']; ?>
                    </div>
                </div>

                <div class="col-md-5 mt-3">
                    <div class="dropdown">
                        <button class="btn mt-1 ml-5 dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="logout.php">
                                <i class="fa fa-sign-out"></i> Logout</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#profileModal">
                                <i class="fa fa-gear"></i>Setting</a>

                        </div>
                    </div>
                </div>

                </h4>
            </div>


            <!-- Search Box -->
            <form method="get" id="searchFriend">
                <div class="input-group mb-4">
                    <input id="searchuser" name="searchuser" class="form-control border-end-0 border rounded-pill"
                        type="text" placeholder="search" id="searchuser">
                    <span class="input-group-append ml-2">
                        <button class="btn btn-outline-secondary bg-white border-start-0 border rounded-pill ms-n3"
                            type="submit">
                            <i class="fa fa-search"></i>
                        </button>

                    </span>
                </div>
            </form>
            <!-- User List -->
            <div id="userlist">
                <ul class="list-group">
                    <!-- user list dynamically fetched -->
                    </li>



                    <!-- Add more users dynamically if needed -->
                </ul>
            </div>
        </div>
        <!-- Message Area -->
        <div class="col-md-9">
            <div class="card mt-3">
                <div class="card-header d-flex align-items-center">
                    <div class="user-avatar">
                        <img src="" alt="User 1" id="selecteduserProfile">
                    </div>
                    <div class="d-flex align-item-center justify-content-center">
                        <h4 id="selecteduser">Select someone to chat</h4>
                        <small>
                            <p class=" mt-1 ml-1" id="selecteduserstatus"></p>
                        </small>
                    </div>
                </div>
                <div class="card-body" id="message-area">
                    <!-- Messages will be displayed here -->
                    <div class="media mb-3">
                        <div class="media-body">
                            <div class="container" id="chatBox">
                                <h4>start messaging</h4>
                            </div>

                        </div>
                    </div>
                    <!-- Add more messages dynamically if needed -->
                </div>
                <div class="card-footer">
                    <!-- Message Input -->
                    <form method="post" id="sendMsgForm">

                        <div class="form-group d-flex align-items-center justify-content-center">

                            <input type="hidden" id="receiver_id" name="receiver_id" value="">
                            <textarea class=" form-control" name="message" id="msgcontent"
                                placeholder="Type something here..."></textarea>


                            <i class="fa-solid fa-link btn ml-2 border-start-0 border rounded-pill ms-n3"
                                id="attachFileBtn"></i>
                            <input type="file" name="file" id="file" style="display: none;">

                            <button type="submit" class="btn ml-2 border-start-0 border rounded-pill ms-n3">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                    <div displayfile>
                        <p id="attachfileName"></p>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="Image" style="max-width: 100%;">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Porfile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="updatepForm" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="profile">Profile Image</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="profile" name="profile" accept="image/*">
                    <label class="custom-file-label" for="profile">Choose Profile Image</label>
                </div>
                <?php if ($user['profile']): ?>
                    <div class="mt-3">
                        <img src="<?= htmlspecialchars($user['profile']) ?>" alt="Profile Image" style="max-width: 150px;">
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>

            </div>
        </div>
    </div>
</div>

<?php
include ("partials/footer.php");
?>