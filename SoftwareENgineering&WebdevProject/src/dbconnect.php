<?php
global $dsn, $username, $password, $options;
require 'config.php';
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>