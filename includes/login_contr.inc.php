<?php

declare(strict_types=1);

function is_input_empty(string $user_name, string $pwd){
    if (empty($user_name) ||  empty($pwd)) {
        return true;
    }
     else {
        return false;
    }
}

function is_username_wrong(bool|array $result){
    if (!$result) {
        return true;
    } else {
        return false;
    } 
}

function is_password_wrong(string $pwd, string $hashed_pwd){
    if (!password_verify($pwd, $hashed_pwd)) {
        return true;
    } else {
        return false;
    } 
}

