<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "root";
$database = "dvsdb";


// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Include class files
require_once 'classes/User.php';
require_once 'classes/Admin.php';
session_start(); // Start the session

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create an instance of Admin class
    $admin = new \classes\Admin();

    // Populate Admin object with form data
    $admin->setUserID($_POST['admin_id']);
    $admin->setUsername($_POST['username']);
    $admin->setPassword($_POST['password']);

    // Get values from Admin object
    $admin_id = $admin->getUserID();
    $admin_username = $admin->getUsername();
    $admin_password = $admin->getPassword();

    // Validate the credentials using prepared statements
    $sql = "SELECT * FROM admin WHERE idAdmin = ? AND Username = ? AND Password = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $admin_id, $admin_username, $admin_password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        // Credentials are correct, store admin_id in session
        $_SESSION['admin_id'] = $admin_id;
        // Redirect to the protected page
        header("Location: productconnections.php");
        exit();
    } else {
        // Credentials are incorrect, show error message
        echo "Invalid credentials. Please try again.";
    }
}
?>
