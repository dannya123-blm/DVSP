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

// Define variables and initialize with empty values
$username = $password = $confirm_password = $email = $mobile_number = $address = "";
$username_err = $password_err = $confirm_password_err = $email_err = $mobile_number_err = $address_err = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and process registration form data
    // Process username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Process password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Process confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Process email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Process mobile number
    if (empty(trim($_POST["mobile_number"]))) {
        $mobile_number_err = "Please enter a mobile number.";
    } else {
        $mobile_number = trim($_POST["mobile_number"]);
    }

    // Process address
    if (empty(trim($_POST["address"]))) {
        $address_err = "Please enter an address.";
    } else {
        $address = trim($_POST["address"]);
    }

    // Check input errors before inserting into database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($mobile_number_err) && empty($address_err)) {
        // Generate a random user ID between 20 and 100
        $user_id = mt_rand(20, 100);

        // Prepare an insert statement
        $sql = "INSERT INTO customer (idCustomer, username, password, email, mobileNumber, Address) VALUES ('$user_id', '$username', '$password', '$email', '$mobile_number', '$address')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to login page
            header("location: login.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close connection
    $conn->close();
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
    <link rel="stylesheet" href="css/customerlogin.css">
    <title>Customer Registration</title>
</head>

<body>
<h2>Customer Registration</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div>
        <label>Username</label>
        <input type="text" name="username" value="<?php echo $username; ?>">
        <span><?php echo $username_err; ?></span>
    </div>
    <div>
        <label>Password</label>
        <input type="password" name="password" value="<?php echo $password; ?>">
        <span><?php echo $password_err; ?></span>
    </div>
    <div>
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
        <span><?php echo $confirm_password_err; ?></span>
    </div>
    <div>
        <label>Email</label>
        <input type="text" name="email" value="<?php echo $email; ?>">
        <span><?php echo $email_err; ?></span>
    </div>
    <div>
        <label>Mobile Number</label>
        <input type="text" name="mobile_number" value="<?php echo $mobile_number; ?>">
        <span><?php echo $mobile_number_err; ?></span>
    </div>
    <div>
        <label>Address</label>
        <input type="text" name="address" value="<?php echo $address; ?>">
        <span><?php echo $address_err; ?></span>
    </div>
    <div>
        <input type="submit" value="Register">
    </div>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</form>
</body>

</html>
<?php
// Include footer
require_once "footer.php";
?>