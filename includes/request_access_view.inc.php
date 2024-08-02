<?php

//Allow the code to have type declarations
declare(strict_types=1);

function check_signup_errors(){
    if (isset($_SESSION['errors_signup'])) {
        $errors = $_SESSION['errors_signup'];

        echo "<br>";

        foreach ($errors as $error) {
            echo '<p class="form-error">' . $error . '</p>';
        }
        unset($_SESSION['errors_signup']);
    } else if (isset($_GET["signup"]) && $_GET["signup"] === "sucess"){
        echo '<br>';
        echo '<p class="form-success">Signup Success!</p>';
    }
}

