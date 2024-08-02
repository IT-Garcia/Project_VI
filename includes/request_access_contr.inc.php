<?php

//Allow the code to have type declarations
declare(strict_types=1);

function is_input_empty(string $name,string $last_name,string $user_name,string $email,string $pwd,string $birth_date){
    if (empty($name) || empty($last_name) || empty($user_name) || empty($email) || empty($pwd) || empty($birth_date)) {
        return true;
    }
     else {
        return false;
    }
}

function is_email_invalid($email){
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function is_username_taken(object $pdo, string $user_name){
    if (get_username($pdo, $user_name)) {
        return true;
    } else {
        return false;
    }
}


function is_email_taken(object $pdo, string $email){
    if (get_email($pdo, $email)) {
        return true;
    } else {
        return false;
    }
}

function create_user(object $pdo, string $name,string $last_name,string $user_name,string $email,string $pwd, $birth_date, $access_type){
    set_user($pdo,  $name, $last_name, $user_name, $email, $pwd, $birth_date, $access_type);
}