<?php
global $pdo;
include '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../classes/Customer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {

        $customer = new Customer($pdo);

        $authenticatedUser = $customer->authenticateUser($username, $password);

        if ($authenticatedUser) {
            $_SESSION['user_id'] = $authenticatedUser['idCustomer'];
            $_SESSION['username'] = $authenticatedUser['Username'];
            $_SESSION['email'] = $authenticatedUser['Email'];

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
<body>

<div class="login-container">
    <form action="login.php" method="POST">
        <h2>User Login</h2>
        <?php
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
include "../template/footer.php";
?>
</body>
</html>
