<?php
// Include header and database connection
global $pdo;
include '../template/header.php';
require_once '../src/dbconnect.php';

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
            // Check if the user is an admin
            if ($user['Role'] == 'admin') {
                // Admin login
                $_SESSION['user_id'] = $user['idCustomer'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['email'] = $user['Email'];
                $_SESSION['role'] = 'admin'; // Set user role to 'admin'

                // Redirect to admin dashboard or any other admin page
                header("Location: ../administrator/adminsadminscrud.php");
                exit();
            } else {
                // Regular user login
                $_SESSION['user_id'] = $user['idCustomer'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['email'] = $user['Email'];

                // Redirect to user dashboard or any other user page
                header("Location: index.php");
                exit();
            }
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
// Include footer
require "../template/footer.php";
?>
</body>
</html>
