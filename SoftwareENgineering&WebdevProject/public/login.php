<?php
include '../template/header.php';
require_once '../src/dbconnect.php'; // Include your database connection file
require_once '../classes/User.php'; // Assuming this is your User class
require_once '../classes/Customer.php'; // Assuming this is your Customer class

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the credentials against the database
    $sql = "SELECT * FROM customer WHERE Username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['Password'])) {
            // Password is correct, create session and redirect
            $_SESSION['user_id'] = $user['idCustomer']; // Store user ID in session
            $_SESSION['username'] = $user['Username'];
            $_SESSION['email'] = $user['Email'];
            // Add other relevant session data as needed

            // Redirect to dashboard or any other page after login
            header("Location: index.php");
            exit();
        } else {
            $loginError = "Invalid username or password.";
        }
    } else {
        $loginError = "Invalid username or password.";
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
