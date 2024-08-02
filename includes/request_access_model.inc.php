<?php

//Allow the code to have type declarations
declare(strict_types=1);

function get_username(object $pdo, string $user_name){
    $query = "SELECT user_name FROM users where user_name = :user_name;";
    //Separate the data from the query to prevent sql injection
    $stmt = $pdo->prepare($query);
    //Bind the data
    $stmt->bindParam(":user_name", $user_name);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function get_email(object $pdo, string $email){
    $query = "SELECT user_name FROM users where email = :email;";
    //Separate the data from the query to prevent sql injection
    $stmt = $pdo->prepare($query);
    //Bind the data
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function set_user($pdo,  $name, $last_name, $user_name, $email, $pwd, $birth_date, $access_type){
    
    $query = "INSERT INTO users (name, last_name, user_name, email, pwd, birth_date, access_type) VALUES (:f_name,:last_name, :user_name, :email, :pwd, :birth_date, :access_type);";
    //Separate the data from the query to prevent sql injection
    $stmt = $pdo->prepare($query);
    
    $options = [
        'cost' => 12
    ];

    $hashedPassword = password_hash($pwd, PASSWORD_BCRYPT, $options);

    //Bind the data
    $stmt->bindParam(":f_name", $name);
    $stmt->bindParam(":last_name", $last_name);
    $stmt->bindParam(":user_name", $user_name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":pwd", $hashedPassword);
    $stmt->bindParam(":birth_date", $birth_date);
    $stmt->bindParam(":access_type", $access_type);
    $stmt->execute();
}