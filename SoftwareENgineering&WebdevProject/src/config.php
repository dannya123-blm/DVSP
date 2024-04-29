<?php
// This code is based on the assignment PHP : Sessions, by Robert Smith;

/**
 * Configuration for database connection
 *
 */
$host = "localhost";
$username = "root";
$password = 'Jesuloba65';
$dbname = "dvsdb";
$dsn = "mysql:host=$host;dbname=$dbname";
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);