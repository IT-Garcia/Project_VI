<?php

//Pi Credentials - Local
// $host = '192.168.1.200';
// $dbname = 'elevator';
// $dbusername = "itgtest";
// $dbpassword = "QNgIFKQKNqsaLLV2";

//Pi Credentials - Remote
// $host = '10.121.111.67:50050';
// $dbname = 'elevator';
// $dbusername = "itgtest";
// $dbpassword = "QNgIFKQKNqsaLLV2";

// Localhost Credentials
$host = '127.0.0.1';
$dbname = 'elevator';
$dbusername = "softenguser";
$dbpassword = "%yTxaZnkAgO8C0am";

try{
    
    // pdo = PHP data Object
    // $pdo = NEW PDO($dsn, $dbusername, $dbpassword );

    $pdo = NEW PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Connection Successful!";
} catch (PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}