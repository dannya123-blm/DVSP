<?php
include '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../classes/User.php';
require_once '../classes/Customer.php';
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
    <form action="../public/register.php" method="POST">
        <h2>User Registration</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="mobileNumber">Mobile Number:</label>
        <input type="text" id="mobileNumber" name="mobileNumber" required><br><br>

        <input type="submit" value="Register">
    </form>

    <p style="text-align: center;">Already have an account? <a href="../public/login.php">Log in here</a></p>
</div>
</body>
</html>
<?php
require "../template/footer.php";
?>
