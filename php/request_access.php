<?php
    if($submitted = !empty($_POST)){
        $user_name = htmlspecialchars($_POST['user_name']);
        $user_lastname = htmlspecialchars($_POST['user_lastname']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['passwd']);
        $birth_date = htmlspecialchars($_POST['birth_date']);
        $request_type = htmlspecialchars($_POST['request_type']);

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Saira:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/forms.css">
    <script src="../scripts/scripts.js"></script>
    <title>Request Form Handler Page</title>
</head>

<body>
    <nav>
        <ul>
        <li><button id="nav_button" onclick="window.location.href='../index.html';">
            <img src="../icons/lift.png" alt="Home"></button>
        </li>
        <li><a href="../about.html">About</a></li>
        <li><a href="https://github.com/users/IT-Garcia/projects/1" target="_blank">Project Plan & Status</a></li>
        <li><a href="../bhlogbook.html">Brandon's Logbook</a></li>
        <li><a href="../itglogbook.html">Isai's Logbook</a></li>
        </ul>
    </nav>

    <div class="text" >
        <h1>Form Submitted</h1>
        <h2>Submitted Information:</h2>
        <ul>
        <?php
        echo "<b>First Name: </b>" . $user_name . "<br>";
        echo "<b>Last Name: </b>" . $user_lastname . "<br>";
        echo "<b>Email: </b>" . $email . "<br>";
        echo "<b>Password: </b>" . $password . "<br>";
        echo "<b>Birth date: </b>" . $birth_date . "<br>";
        echo "<b>Request type: </b>" . $request_type . "<br>";
        ?>
        </ul>
    </div>

    <footer>
        Brandon Hauck | Isai Torres Garcia &copy; <span id="current_year"></span> 
    </footer>
</body>
</html>