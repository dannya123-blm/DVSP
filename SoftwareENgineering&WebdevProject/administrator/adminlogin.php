<?php
require_once '../src/dbconnect.php'; // Include your database connection
require_once '../classes/Admin.php'; // Include Admin class definition
include '../template/header.php'; // Include header (if needed)

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $adminId = $_POST['admin_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Create an instance of Admin class
    $admin = new Admin();

    // Authenticate admin
    if ($admin->authenticate($adminId, $username, $password)) {
        // Credentials are correct, start the session and store admin_id
        session_start();
        $_SESSION['admin_id'] = $adminId;
        $_SESSION['user_role'] = $admin->getRole();
        header("Location: ../administrator/adminscrud.php"); // Redirect to admin page
        exit();
    } else {
        // Credentials are incorrect, show error message
        echo "Invalid credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css"> <!-- Link to CSS file -->
</head>
<body>
<div class="login-container">
    <form class="login-form" method="post" action="adminlogin.php"> <!-- Ensure correct action -->
        <h2>Login</h2>
        <label for="admin_id">Admin ID:</label>
        <input type="text" id="admin_id" name="admin_id" required>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Login">
    </form>
</div>
</body>
</html>
