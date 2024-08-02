<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["user_name"];
    $pwd = $_POST["pwd"];

    try {
        require_once('dbh.inc.php');
        require_once('login_model.inc.php');
        require_once('login_contr.inc.php');
       
        //Error Handlers
        $errors = [];

        if (is_input_empty($username, $pwd)) {
            $errors["empty_imput"] = "Fill in all fields!";
        }

        $result = get_user($pdo, $username);

        //Check if the username exists
        if (is_username_wrong($result)) {
            $errors["login_incorrect"] = "Incorrect username!";
        }

        //Check if the user exists and if the password is correct
        if (!is_username_wrong($result) &&  is_password_wrong($pwd, $result["pwd"])) {
            $errors["login_incorrect"] = "Incorrect password!";
        }

        require_once 'config_session.inc.php';

        if ($errors) {
            $_SESSION["errors_login"] = $errors;

            header("Location: ../login.php");
            die();
        }

        //Create a user session 
        $newSessionId = session_create_id();
        $sessionId = $newSessionId . "-" . $result["id"];
        session_id($sessionId);

        $_SESSION["user_id"] = $result["id"];
        $_SESSION["user_username"] = htmlspecialchars($result["user_name"]);
        $_SESSION["last_regeneration"] = time();
        $_SESSION["loggedin"] = true;

        header("Location: ../elevator.php?login=success");
        $pdo = NULL;
        $statement = NULL;

        die();

    } catch (PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }
}
else{
    header("Location: ../login.php");
    die();
}