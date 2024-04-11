<?php
include '../src/dbconnect.php'; // Include your database connection file
include '../template/header.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $admin_id = $_POST['admin_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the credentials using prepared statements with PDO
    $sql = "SELECT * FROM admin WHERE idAdmin = ? AND Username = ? AND Password = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$admin_id, $username, $password]);
    $result = $stmt->fetch();

    if ($result) {
        // Credentials are correct, start the session and store admin_id
        session_start();
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['user_role'] = 'admin'; // Set user role as admin
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
