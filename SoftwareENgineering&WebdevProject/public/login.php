<?php
global $pdo;
require_once '../src/dbconnect.php';
require_once '../classes/Customer.php';
require_once '../template/header.php';

$loginError = ''; // Initialize login error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$username = $_POST['username'];
$password = $_POST['password'];

try {
// Create a Customer object with PDO and user ID
$customer = new Customer($pdo, null); // Pass null for user ID initially
$authenticatedUser = $customer->authenticateUser($username, $password);

if ($authenticatedUser) {
$customer->setUserId($authenticatedUser['idCustomer']); // Set userId after authentication

// Set session variables
$_SESSION['user_id'] = $authenticatedUser['idCustomer'];
$_SESSION['username'] = $authenticatedUser['Username'];
$_SESSION['email'] = $authenticatedUser['Email'];

// Set the 'user' cookie
if (setcookie('user', $username, time() + 3600, '/')) {
echo "Cookie 'user' set successfully.";
} else {
echo "Error setting cookie 'user'.";
}

// Redirect based on user role
if ($authenticatedUser['Role'] == 'admin') {
header("Location: ../administrator/adminsadminscrud.php");
} else {
header("Location: index.php");
}
exit();
} else {
$loginError = "Invalid username or password.";
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
        <title>User Login</title>
        <link rel="stylesheet" href="../css/login.css">
    </head>
    <body class="login-body">

    <div class="login-container">
        <form action="login.php" method="POST">
            <h2 class="login-heading">User Login</h2>
            <?php if ($loginError) : ?>
                <p class="error-message"><?php echo $loginError; ?></p>
            <?php endif; ?>
            <label class="login-label" for="username">Username:</label>
            <input type="text" id="username" name="username" class="login-input" required><br><br>

            <label class="login-label" for="password">Password:</label>
            <input type="password" id="password" name="password" class="login-input" required><br><br>

            <input type="submit" value="Login" class="login-submit">
            <p class="login-text" style="text-align: center;">Don't have an account? <a href="../public/register.php">Register here</a></p>
        </form>
    </div>

    </body>
    </html>
<?php
require_once '../template/footer.php';
?>