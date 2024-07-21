<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['uname'];
        $password = $_POST['pswd'];

        // Read the existing data from the JSON file
        $dataFile = '../json/authorizedUsers.json';
        if (file_exists($dataFile)) {
            $jsonData = file_get_contents($dataFile);
            $userCredentials = json_decode($jsonData, true);
        } else {
            $userCredentials = [];
        }

        // Check if the username and password match any existing user credentials
        $userFound = false;
        foreach ($userCredentials as $credential) {
            if ($credential['uname'] === $username && password_verify($password, $credential['password'])) {
                $userFound = true;
                $_SESSION['uname'] = $username; // Store username in session
                break;
            }
        }

        if ($userFound) {
            echo '<h1>Login Successful!!</h1>';
            echo '<h2>You will be redirected in 5 seconds...</h2>';
            header('Refresh: 5; http://localhost/soft_eng_midterm/php/members.php');
            exit;
        } else {
            echo '<h1>Invalid username or password!</h1>';
            echo '<h2>You will be redirected to the login page in 5 seconds...</h2>';
            header('Refresh: 5; ../login.html');
            exit;
        }
    } else {
        echo 'Invalid request method!';
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
        <h1>Login Successful</h1>
        <h2>Submitted credentials:</h2>
        <ul>
        <?php
        echo "<b>User Name: </b>" . $user_name . "<br>";
        echo "<b>Password: </b>" . $passwd . "<br>";
        ?>
        </ul>

        <button type="submit" onclick="window.location.href='../elevator.html'">Go to Elevator Terminal</button>
    </div>

    <footer>
        Brandon Hauck | Isai Torres Garcia &copy; <span id="current_year"></span> 
    </footer>
</body>
</html>