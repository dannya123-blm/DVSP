<?php
// This code is based on the assignment PHP : Sessions, by Robert Smith;

/**
 * Configuration for database connection
 *
 */
$host = "localhost";
$username = "root";
$password = 'Yxng$alem951';
$dbname = "dvsdb"; // Define your database name here
$dsn = "mysql:host=$host;dbname=$dbname";
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);