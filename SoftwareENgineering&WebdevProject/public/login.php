<?php
session_start(); // Start the session

include '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../classes/User.php';
require_once '../classes/Customer.php';
require_once '../classes/admin.php';

// Check if the user is already logged in, redirect if necessary
if (isset($_SESSION['user_id'])) {
    header("Location: ../public/index.php"); // Redirect to dashboard if already logged in
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate login credentials and process login
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Example login validation (assuming User class handles user authentication)
    $user = User::findByUsername($username);
    if ($user && password_verify($password, $user->getPassword())) {
        $_SESSION['user_id'] = $user->getId(); // Store user ID in session
        header("Location: ../public/index.php"); // Redirect after successful login
        exit;
    } else {
        // Invalid login
        $loginError = "Invalid username or password. Please try again.";
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
        <?php
        // Display login error message if login failed
        if (isset($loginError)) {
            echo '<p style="color: red;">' . $loginError . '</p>';
        }
        ?>
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
