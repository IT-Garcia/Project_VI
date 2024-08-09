<?php
    require_once 'includes/config_session.inc.php';
    require_once 'includes/request_access_view.inc.php';
?>

<!DOCTYPE html>
<html lang="en">

    <?php
        include 'head_forms.php';
    ?>
    <title>Request Access</title>

<body>
    
    <?php
      include 'navmenu.php';
    ?>

    <section>
        <form class="inputform" action="includes/request_access_handler.inc.php" method="post">
            <h1>Request Access</h1>
            <div class="request_access">
                <!-- First name -->
                <label for="f_name"><strong>First Name</strong></label>
                <input type="text" placeholder="Enter First Name" name="f_name" required>
                <!-- Last name -->
                <label for="l_name"><strong>Last Name</strong></label>
                <input type="text" placeholder="Enter Last Name" name="l_name" required>
                <!-- Username -->
                <label for="user_name"><strong>Username</strong></label>
                <input type="text" placeholder="Enter Usernane" name="user_name" required>
                <!-- Email -->
                <label for="email"><strong>Email</strong></label>
                <input type="text" placeholder="Enter Email" name="email" required>
                <!-- Password -->
                <label for="passwd"><strong>Password</strong></label>
                <input type="password" placeholder="Password" name="passwd" required>
                <!-- Birthday -->
                <label for="birthday"><strong>Birth date</strong></label>
                <input type="date" name="birth_date" required>
                
                <button id="sub_bttn" type="submit">Submit</button>
            </div>
        </form>
    </section>

    <?php
        check_signup_errors();
    ?>

    <?php
        include 'footer.php';
    ?>
</body>
</html>