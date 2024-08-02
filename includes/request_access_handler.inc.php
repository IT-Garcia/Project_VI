<?php

// Check if the user is trying to access this file through the request access form
// This is to stop users from accessing this form directly and break our database

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = $_POST["f_name"];
    $last_name = $_POST["l_name"];
    $user_name = $_POST["user_name"];
    $email = $_POST["email"];
    $pwd = $_POST["passwd"];
    $birth_date = $_POST["birth_date"];
    $access_type = "user_access";

    try {
        require_once "dbh.inc.php";
        //Order: Model, View and Contr
        //No need for view here
        require_once "request_access_model.inc.php";
        require_once "request_access_contr.inc.php";

        //Error Handlers
        $errors = [];

        if (is_input_empty($name, $last_name, $user_name, $email, $pwd, $birth_date)) {
            $errors["empty_imput"] = "Fill in all fields!";
        }
        if (is_email_invalid($email)){
            $errors["invalid_email"] = "Invalid email used!";
        }
        if (is_username_taken($pdo, $user_name)){
            $errors["username_taken"] = "Username has already been taken!";
        }
        if(is_email_taken($pdo, $email)){
            $errors["email_taken"] = "Email is already registered!";
        }

        require_once 'config_session.inc.php';

        if ($errors) {
            $_SESSION["errors_signup"] = $errors;

            header("Location: ../request_access.php");
            die();
        }
        
        // Create an array that stores the info that the user has already typed in
        // and returns it if the user clicks submit before filling all the fields
        // $signupData = [
        //     "first_name" = $name,
        //     "last_name" => $last_name,
        //     "user_name" => $user_name, 
        //     "email" => $email, 
        //     "birth_date" => $birth_date
        // ];

        
        //Create a user if there are no errors
        create_user($pdo,  $name, $last_name, $user_name, $email, $pwd, $birth_date, $access_type);
       
        // Create a PHP Query
        // $query = "INSERT INTO users (name, last_name, user_name, email, pwd, birth_date, access_type) VALUES (?, ?, ?, ?, ?, ?, ?);";

        // $stmt = $pdo->prepare($query);
        // $stmt->execute([$name, $last_name, $user_name,$email, $pwd, $birth_date, $access_type]);
        
        
        header("Location: ../login.php?signup=success");

        //Close the connection
        $pdo=NULL;
        $stmt=NULL;
        die();


    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

else {

    //Send the user back to the request access page if trying to access this page innapropiately
    header("Location: ../request_access.php");
    die(); 

}