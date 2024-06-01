<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js" ></script>
<script>
    $(document).ready(function () {

        $('#msgcontent').emojioneArea({
                pickerPosition:'top'
            })
       

        //attach file to send
        $('#attachFileBtn').on('click', function () {
            $('#file').click();
        });

        //show the file
        $('#file').on('change', function () {
            var fileName = $(this).val().split('\\').pop();
            $('#attachfileName').html(fileName + '<i class="fa-solid fa-trash ml-2 removeFile"></i>');
        });

        // remove attach file
        $(document).on('click', '.removeFile', function () {
            $('#chatFile').val(''); // Clear the file input field
            $('#attachfileName').html(''); // Clear the file name display
        });

       

        $(document).on('click', '.downloadImg', function () {
             
    // Apply changes to the image and download link within the same container
    $(this).siblings(".imgDownload").css("filter", "none");
            localStorage.setItem('imageDownloaded', 'true'); 
            console.log(localStorage.getItem('imageDownloaded'));
            $(this).hide(); // Hide download icon
        });

        $(document).on('click', '.imgDownload', function () {
            var imageUrl = $(this).attr('src');
        $("#modalImage").attr('src', imageUrl);
       
        });



        //user registration

        $('#signupForm').on('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: 'register.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    var alertArea = $('#alert-area');
                    var responseParts = response.split('|');
                    var status = responseParts[0];
                    var message = responseParts[1];

                    alertArea.html('<div class="alert alert-' + (status === 'success' ? 'success' : 'warning') + ' text-center" role="alert">' + message + '</div>');

                    if (status === 'success') {
                        $('#signupForm')[0].reset();
                    }
                },
                error: function (error) {
                    console.log(error);
                }

            });
        });

        //user login

        $('#loginForm').on('submit', function (event) {
            event.preventDefault();


            var formData = new FormData(this);

            $.ajax({
                url: 'login-handler.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    var alertArea = $('#alert-area');
                    var responseParts = response.split('|');
                    var status = responseParts[0];
                    var message = responseParts[1];

                    alertArea.html('<div class="alert alert-' + (status === 'success' ? 'success' : 'warning') + ' text-center" role="alert">' + message + '</div>');

                    if (status === 'success') {
                        setTimeout(function () {
                            window.location.href = 'chatroom.php';
                        }, 2000); // Redirect after 2 seconds
                    }
                },
                error: function (error) {
                    console.log(error);
                }

            });


        });

        let receiverId = null;

        function loadUsers(query = '') {
        $.ajax({
            url: 'userlist.php',
            method: 'GET',
            data: { searchuser: query },
            success: function(data) {
                $('#userlist').html(data); // Populate the user list
            }
        });
    }


        loadUsers();

        //select friend

        $(document).on('click', '.user', function () {
            receiverId = $(this).data('id');

            receiverName = $(this).data('name');
            receiverProfile = $(this).data('profile');
            receiverStatus = $(this).data('active');
            $('#selecteduser').html(receiverName);
            $('#selecteduserProfile').attr('src', receiverProfile);
            $('#receiver_id').val(receiverId);



            loadMessages();

            $.ajax({
                url: 'getUserStatus.php',
                type: 'GET',
                data: { id: receiverId },
                success: function (response) {
                    if (response == 1) {
                        $('#selecteduserstatus').html('(active)');
                    } else {
                        $('#selecteduserstatus').html('');
                    }
                },
                error: function () {
                    $('#selecteduserstatus').html('');
                }
            });

        });

        //send msg

        $('#sendMsgForm').submit(function (e) {
            e.preventDefault(); // Prevent default form submission
            let receiver_id = $('#receiver_id').val();
            let message = $('#msgcontent').val();
            let filepath = $('#file')[0];

            // Create a FormData object
            let formData = new FormData();
            formData.append('receiver_id', receiver_id);
            formData.append('message', message);

            if (filepath.files.length > 0) {
                formData.append('file', filepath.files[0]);
            }

            $.ajax({
                url: 'sendmsg.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response); 

                    $('#msgcontent').val(''); 

                    $('#file').val(''); 

                    loadMessages();
                },
                error: function (xhr, status, error) {
                    console.log('Error: ' + error); // Log any error for debugging
                }
            });
        });


        //load msg
        function loadMessages() {
            if (receiverId) {
                $.ajax({
                    url: 'loadMsg.php',
                    method: 'GET',
                    data: { receiver_id: receiverId },
                    success: function (data) {
                        $('#chatBox').html(data);
                    }
                });
            }
        }

        $(document).on('click', '.delete-msg', function () {
    var msgId = $(this).data('id');

    if (confirm('Are you sure you want to delete this message?')) {
        $.ajax({
            url: 'deletemsg.php',
            method: 'POST',
            data: { id: msgId },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Message deleted successfully');
                    loadMessages(); // Refresh the messages
                } else {
                    alert('Failed to delete the message');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error: ' + error); // Log any error for debugging
            }
        });
    }
});

    //search 
    $('#searchFriend').on('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting the traditional way

        var query = $('#searchuser').val();
        loadUsers(query);
    });

    // Handle search input changes
    $('#searchuser').on('input', function() {
        var query = $(this).val();
        loadUsers(query);
    });

        setInterval(loadMessages, 5000);
        setInterval(loadUsers, 5000);



    // update profile
    $('#updatepForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission
                
                var formData = new FormData(this); // Create a FormData object with the form data

                $.ajax({
                    url: 'updateProfile.php', // The URL of the PHP file that handles the form submission
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert('profile change successfully...');
                        setTimeout(function () {
                            window.location.href = 'chatroom.php';
                        }, 1000);
                        
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors
                        alert('An error occurred: ' + error);
                    }
                });
            });

    });


</script>


</body>

</html>