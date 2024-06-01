<?php
include("partials/head.php");
?>

<div class=" row p-5 mt-3" id="signup-area">
  <div class="col-lg-12" >
    <div id="alert-area">
    
    </div>
    
    <form id="loginForm" method="post">
      
  <div class="form-group">
    <label for="name">username</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
  </div>
  
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password" placeholder=" enter a Password" required>
  </div>
  
  <button type="submit" class="btn col-lg-12">Login</button>
</form>
<h5 class="mt-2">Don't have an Account? Register here- <a href="singup.php">Sign Up</a></h5>
</div>
</div>



<?php
include("partials/footer.php");
?>


