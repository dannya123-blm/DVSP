<?php
// Include header and necessary files
global $pdo;
include '../template/header.php';
include '../src/dbconnect.php'; // Assuming this includes database connection
include '../classes/User.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password']; // Store the plain password for hashing
    $email = htmlspecialchars($_POST['email']); // Sanitize email input
    $mobileNumber = htmlspecialchars($_POST['mobileNumber']); // Sanitize mobile number input
    $address = htmlspecialchars($_POST['address']); // Sanitize address input

    // Password validation criteria (at least 8 characters with at least one uppercase letter)
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password)) {
        // Display error message and prevent form submission
        echo "<script>alert('Password must be at least 8 characters long and contain at least one uppercase letter');</script>";
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement to insert user data into database
        $sql = "INSERT INTO customer (username, password, email, mobileNumber, address) VALUES (?, ?, ?, ?, ?)";

        try {
            // Create a PDO statement
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $hashedPassword);
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="../css/register.css">
    <script>
        // Client-side password validation
        function validatePassword() {
            var password = document.getElementById('password').value;
            if (password.length < 8 || !/[A-Z]/.test(password)) {
                alert('Password must be at least 8 characters long and contain at least one uppercase letter');
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
</head>
<body>
<div class="form-container">
    <form action="../public/register.php" method="POST" onsubmit="return validatePassword()">
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
