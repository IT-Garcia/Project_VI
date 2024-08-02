<?php
    require_once 'includes/config_session.inc.php';
    require_once 'includes/login_view.inc.php';
?>

<!DOCTYPE html>
<html lang="en">

  <?php
    include 'head_forms.php';
  ?>
  <title>Login</title>

<body>

  <?php
    include 'navmenu.php';
  ?>

  <section>
    <form id="login-form" action="includes/login.inc.php" method="post">
        <h1>Elevator Login</h1>
        <div class="formcontainer">
        <hr/>
        <div id="input_fields">
          <label for="user_name"><strong>Username</strong></label>
          <input type="text" placeholder="Enter Username" id="user_name" name="user_name" required>
          <label for="pwd"><strong>Password</strong></label>
          <input type="password" placeholder="Enter Password" id="pwd" name="pwd" required>
        </div>

        <button type="submit">Login</button>
        <button type="button" onclick="window.location.href='request_access.php'"> Request Access </button>
        
        <div id="form_footer">
          <div>
            <input type="checkbox" id="remember" checked="checked" name="remember">
            <label for="remember">Remember Me</label>
          </div>
          <span><a id="forgot_bttn" href="#"> Forgot password?</a></span>
        </div>
    </form>

    <div id="error-message"></div>

    <?php
      check_login_errors();
    ?>
  
  </section>

  <?php
    include 'footer.php';
  ?>
  
</body>
</html>