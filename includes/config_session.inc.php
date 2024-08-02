<?php

// Change some ini settings to make sessions more secure
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

// Set the configurations of the cookie
session_set_cookie_params([
    'lifetime' => 1800,
    'domain' => 'localhost',
    'path' => '/',
    'secure' => true,
    'httponly'=> true

]);

// Regenerate the ID of the cookie every 30 minutes

session_start();

//Check if there is a user logged in into the website
if (isset($_SESSION["user_id"])) {
    if (!isset($_SESSION["last_regeneration"])) {
        regenerate_session_id_loggedin();
    }else {
        $interval = 60 * 30;
        if (time() - $_SESSION["last_regeneration"] >= $interval) {
            regenerate_session_id_loggedin();    
        }
    }
} else{
    //Check if the session variable exists.
    if (!isset($_SESSION["last_regeneration"])) {
        regenerate_session_id();
    } else {
        $interval = 60 * 30;
        if (time() - $_SESSION["last_regeneration"] >= $interval) {
            regenerate_session_id();    
        }
    }
}

function regenerate_session_id(){
    session_regenerate_id(true);
    $_SESSION["last_regeneration"] = time();
}

function regenerate_session_id_loggedin(){
    session_regenerate_id(true);

    $userId = $_SESSION["user_id"];
    $newSessionId = create_session_id();
    $sessionId = $newSessionId . "-" . $userId;
    session_id($sessionId);

    $_SESSION["last_regeneration"] = time();
}