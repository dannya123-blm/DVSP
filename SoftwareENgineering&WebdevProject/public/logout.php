<?php
session_start();
$_SESSION = []; // Clear all session variables
session_destroy(); // Destroy the session
header("Location: ../public/index.php");
exit();

