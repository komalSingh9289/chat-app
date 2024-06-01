<?php
include("partials/head.php");
?>

        <div class=" row p-5 mt-3" id="signup-area">
            <div class="col-lg-12" >
    <div id="alert-area">
    
    </div>
    
    <form id="signupForm" method="post">
      
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
  </div>
  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password" placeholder=" enter a Password" required>
  </div>
  <div class="form-group">
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="profile" name="profile" accept="image/*" >
    <label class="custom-file-label" for="profile">Choose Profile Image</label>
  </div>
  </div>
  
  <button type="submit" class="btn col-lg-12">Sign Up</button>
</form>
</div>
</div>



<?php
include("partials/footer.php");
?>


