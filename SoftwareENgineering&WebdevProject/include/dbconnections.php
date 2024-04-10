<?php
$host = 'localhost';
$dbname = 'dvsdb';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection established successfully";
} catch (PDOException $e) {
    // Output detailed error message for debugging
    echo "Connection failed: " . $e->getMessage();
    // You can log this error for further analysis if needed
}
>