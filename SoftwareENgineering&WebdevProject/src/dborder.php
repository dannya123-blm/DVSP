<?php
include '../src/dbconnect.php';
include '../src/config.php';


function getConnection() {
    $host = 'localhost';  // Database host (e.g., localhost)
    $dbname = 'dvsdb';  // Database name
    $username = 'root';  // Database username
    $password = 'Yxng$alem951';  // Database password

    // Establish a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set PDO attributes (optional)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
}
?>
