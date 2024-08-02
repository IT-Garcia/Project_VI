<?php

declare(strict_types=1);


function get_user(object $pdo, string $user_name){
    $query = "SELECT * FROM users where user_name = :user_name;";
    //Separate the data from the query to prevent sql injection
    $stmt = $pdo->prepare($query);
    //Bind the data
    $stmt->bindParam(":user_name", $user_name);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}