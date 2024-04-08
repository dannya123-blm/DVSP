<?php
// Include the necessary classes
require_once 'classes/User.php';
require_once 'classes/Customer.php';

// Include the database configuration file
require_once 'databaseconfig.php';

// Start the session to store user login status
session_start();

// Check if the user is already logged in, redirect to dashboard if true
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header("Location: dashboard.php");
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Login logic
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare and execute SQL statement to fetch user details from the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify if user exists and password matches
        if ($user && password_verify($password, $user['password'])) {
            // Create a new Customer object
            $customer = new classes\Customer();

            // Set customer details from the database
            $customer->setUserID($user['idUser']);
            $customer->setUsername($user['username']);
            $customer->setPassword($user['password']); // You may not need to set the password here
            $customer->setEmail($user['email']);
            $customer->setMobileNumber($user['mobileNumber']);

            // Store the customer object in session
            $_SESSION['customer'] = $customer;
            $_SESSION['loggedIn'] = true;

            // Redirect to dashboard
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid username or password";
        }
    } elseif (isset($_POST['register'])) {
        // Redirect to registration page
        header("Location: registration.php");
        exit;
    }
}
?>
<?php
require_once "header.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/logincus.css">
    <title>Customer Login</title>

</head>

<body>
<h2>Customer Login</h2>
<?php if (isset($error)) : ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username"><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" name="login" value="Login">
    <input type="submit" name="register" value="Register">
</form>
</body>

</html>
<?php
// Include footer
require_once "footer.php";
?>