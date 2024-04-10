<?php

// Start session to access session variables
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page or a confirmation page
header("Location: ../public/index.php"); // Redirect to the login page
exit();

