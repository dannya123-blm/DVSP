<?php
// Include header and necessary files
include '../template/header.php';
require_once '../src/dbconnect.php'; // Assuming this includes database connection
require_once '../classes/User.php';
require_once '../classes/Customer.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security
    $email = htmlspecialchars($_POST['email']); // Sanitize email input
    $mobileNumber = htmlspecialchars($_POST['mobileNumber']); // Sanitize mobile number input
    $address = htmlspecialchars($_POST['address']); // Sanitize address input

    // Prepare SQL statement to insert user data into database
    $sql = "INSERT INTO customer (username, password, email, mobileNumber, address) VALUES (?, ?, ?, ?, ?)";

    try {
        // Create a PDO statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $mobileNumber);
        $stmt->bindParam(5, $address);

        // Execute the statement
        $stmt->execute();

        // Redirect to login page after successful registration
        header("Location: ../public/login.php");
        exit;
    } catch (PDOException $e) {
        // Handle database error
        die("Error: " . $e->getMessage());
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

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>

        <input type="submit" value="Register">
    </form>

    <p style="text-align: center;">Already have an account? <a href="../public/login.php">Log in here</a></p>
</div>
</body>
</html>

<?php
// Include footer
require "../template/footer.php";
?>
