<?php
include '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../classes/User.php';
require_once '../classes/Customer.php';
require_once '../classes/admin.php';

// Check if the user is already logged in, redirect if necessary
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate login credentials and process login
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Example login validation
    $user = User::findByUsername($username);
    if ($user && password_verify($password, $user->getPassword())) {
        $_SESSION['user_id'] = $user->getId();
        header("Location: ../public/index.php");
        exit;
    } else {
        // Invalid login
        echo "Invalid username or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

<div class="login-container">
    <form action="login.php" method="POST">
        <h2>User Login</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
        <p style="text-align: center;">Don't have an account? <a href="../public/register.php">Register here</a></p>
    </form>
</div>

<?php
require "../template/footer.php";
?>
</body>
</html>
