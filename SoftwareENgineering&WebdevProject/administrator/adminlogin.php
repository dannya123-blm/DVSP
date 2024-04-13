<?php
require_once '../src/dbconnect.php';
require_once '../template/header.php';
require_once '../classes/Admin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['admin_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $admin = new Admin();
        $authenticatedAdmin = $admin->authenticate($username, $password, $admin_id);

        if ($authenticatedAdmin) {
            session_start();
            $_SESSION['admin_id'] = $authenticatedAdmin['idAdmin'];
            $_SESSION['username'] = $authenticatedAdmin['Username'];
            $_SESSION['user_role'] = $authenticatedAdmin['Role'];

            if ($_SESSION['user_role'] == 'admin') {
                header("Location: ../public/index.php");
                exit();
            } else {
                header("Location: ../administrator/adminscrud.php");
                exit();
            }
        } else {
            echo "<p>Invalid username, password, or admin ID.</p>";
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
<div class="login-container">
    <form class="login-form" method="post" action="adminlogin.php">
        <h2 class="login-heading">Login</h2>
        <label for="admin_id" class="login-label">Admin ID:</label>
        <input type="text" id="admin_id" name="admin_id" class="login-input" required>
        <label for="username" class="login-label">Username:</label>
        <input type="text" id="username" name="username" class="login-input" required>
        <label for="password" class="login-label">Password:</label>
        <input type="password" id="password" name="password" class="login-input" required>
        <input type="submit" value="Login" class="login-submit">
    </form>
</div>
</body>
</html>
