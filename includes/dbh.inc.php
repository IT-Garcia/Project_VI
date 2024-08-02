<?php

$host = 'localhost';
$dbname = 'elevatorTest';
$dbusername = "softenguser";
$dbpassword = "%yTxaZnkAgO8C0am";

try{
    
    // pdo = PHP data Object
    // $pdo = NEW PDO($dsn, $dbusername, $dbpassword );

    $pdo = NEW PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connection Successful!";
} catch (PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}