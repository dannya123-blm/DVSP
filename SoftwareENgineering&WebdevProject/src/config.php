<?php
/**
 * Configuration for database connection
 *
 */
$host = "localhost";
$username = "root";
$password = 'Jesuloba65';
$dbname = "dvsdb2";
$dsn = "mysql:host=$host;dbname=$dbname";
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);