<?php

global $pdo;
require_once '../src/dbconnect.php';
require_once '../classes/Customer.php';
require_once '../template/header.php';

$loginError = ''; // Initialize login error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 'username' and 'password' keys exist in $_POST array
    if (isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            $customer = new Customer($pdo);
            $authenticatedUser = $customer->authenticateUser($username, $password);

            if ($authenticatedUser) {
                // Check if 2FA is enabled
                if (isset($_POST['enable_2fa']) && $_POST['enable_2fa'] == 'on') {
                    // Generate a random code for 2FA
                    $secret_code = mt_rand(100000, 999999);

                    // Send 2FA code via email
                    $to = $authenticatedUser['Email'];
                    $subject = 'Your Two-Factor Authentication Code';
                    $message = 'Your two-factor authentication code is: ' . $secret_code;
                    $headers = 'From: your@example.com' . "\r\n" .
                        'Reply-To: your@example.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

                    // Send email
                    if (mail($to, $subject, $message, $headers)) {
                        echo "Email sent successfully!";
                    } else {
                        echo "Email sending failed!";
                    }

                    // Store the secret code in session
                    $_SESSION['2fa_secret_code'] = $secret_code;

                    // Redirect to 2FA verification form
                    header("Location: login.php?step=2fa_verification");
                    exit();
                }

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

                // Redirect to index.php
                header("Location: index.php");
                exit();
            } else {
                $loginError = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    } else {
        $loginError = "Please provide both username and password.";
    }
}

// 2FA verification step
if (isset($_GET['step']) && $_GET['step'] == '2fa_verification') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $entered_code = $_POST['2fa_code'];
        $stored_code = isset($_SESSION['2fa_secret_code']) ? $_SESSION['2fa_secret_code'] : '';

        if ($entered_code == $stored_code) {
            // 2FA code is correct
            unset($_SESSION['2fa_secret_code']); // Clear the 2FA secret code from session
            // Continue with login process
            // Set session variables, set cookies, and redirect to index.php
            $_SESSION['user_id'] = $authenticatedUser['idCustomer'];
            $_SESSION['username'] = $authenticatedUser['Username'];
            $_SESSION['email'] = $authenticatedUser['Email'];
            header("Location: index.php");
            exit();
        } else {
            $loginError = "Invalid 2FA code.";
        }
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
    <?php if (!isset($_GET['step']) || $_GET['step'] != '2fa_verification') : ?>
        <form action="login.php" method="POST">
            <h2 class="login-heading">User Login</h2>
            <?php if ($loginError) : ?>
                <p class="error-message"><?php echo $loginError; ?></p>
            <?php endif; ?>
            <label class="login-label" for="username">Username:</label>
            <input type="text" id="username" name="username" class="login-input" required><br><br>

            <label class="login-label" for="password">Password:</label>
            <input type="password" id="password" name="password" class="login-input" required><br><br>

            <input type="checkbox" id="enable_2fa" name="enable_2fa">
            <label for="enable_2fa">Enable Two-Factor Authentication</label><br><br>

            <input type="submit" value="Login" class="login-submit">
            <p class="login-text" style="text-align: center;">Don't have an account? <a href="../public/register.php">Register here</a></p>
        </form>
    <?php else : ?>
        <!-- 2FA verification form -->
        <form action="login.php?step=2fa_verification" method="POST">
            <h2 class="login-heading">Enter 2FA Code</h2>
            <?php if ($loginError) : ?>
                <p class="error-message"><?php echo $loginError; ?></p>
            <?php endif; ?>
            <label class="login-label" for="2fa_code">Enter 2FA Code:</label>
            <input type="text" id="2fa_code" name="2fa_code" class="login-input" required><br><br>

            <input type="submit" value="Verify" class="login-submit">
        </form>
    <?php endif; ?>
</div>

</body>
</html>

<?php
require_once '../template/footer.php';
?>
