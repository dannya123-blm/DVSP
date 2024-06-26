<?php
// This code is based on from SymfonyCasts & Wschools:
global $pdo;
include '../template/header.php';
include '../src/dbconnect.php';
require '../classes/User.php';

$errorMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $email = htmlspecialchars($_POST['email']);
    $mobileNumber = htmlspecialchars($_POST['mobileNumber']);
    $address = htmlspecialchars($_POST['address']);

    try {
        $customer = new User($pdo);
        $customer->registerUser($username, $password, $email, $mobileNumber, $address);
        header("Location: ../public/login.php");
        exit;
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>
<div class="form-container">
    <h2>User Registration</h2>
    <?php if (!empty($errorMsg)) : ?>
        <p style="color: red;"><?php echo $errorMsg; ?></p>
    <?php endif; ?>
    <form action="register.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="mobileNumber">Mobile Number:</label>
        <input type="text" id="mobileNumber" name="mobileNumber" required><br><br>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>
        <input type="submit" value="Register" class="submit-button">
    </form>
    <p style="text-align: center;">Already have an account? <a href="../public/login.php">Log in here</a></p>
</div>
</body>
</html>

<?php
include "../template/footer.php";
?>
