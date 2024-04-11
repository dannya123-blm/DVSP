<?php
function getConnection() {
    $host = 'localhost';
    $dbname = 'dvsdb';
    $username = 'root';
    $password = 'Yxng$alem951';

    // Establish PDO connection
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error reporting
        return $pdo;
    } catch (PDOException $e) {
        // Handle connection error
        die("Connection failed: " . $e->getMessage());
    }
}
?>
