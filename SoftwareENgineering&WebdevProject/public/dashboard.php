<?php
// Include header and database connection
include '../template/header.php';
include '../src/dbconnect.php';
require '../classes/Customer.php'; // Include Customer class file

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    try {
        $userId = $_SESSION['user_id']; // Assuming the logged-in user's ID is stored in $_SESSION['user_id']

        // Create a new Customer object with PDO instance
        $customer = new Customer($pdo); // Pass $pdo object to the constructor

        // Fetch user data from the database using Customer class method
        $userData = $customer->getUserDataById($userId);

        // Initialize error message variable
        $errorMsg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submissions for updating user details
            if (isset($_POST['update_username'])) {
                // Process username update
                $newUsername = $_POST['new_username'];

                // Check if the new username already exists
                if ($customer->usernameExists($newUsername)) {
                    $errorMsg = "Username already exists. Please choose a different username.";
                } else {
                    // Update the username
                    $customer->updateUsername($userId, $newUsername);
                    // Refresh the page after updating
                    header("Location: dashboard.php");
                    exit();
                }
            }

            // Handle email update
            if (isset($_POST['update_email'])) {
                $newEmail = $_POST['new_email'];
                if ($customer->emailExists($newEmail)) {
                    $errorMsg = "Email already exists. Please choose a different email.";
                } else {
                    $customer->updateEmail($userId, $newEmail);
                    header("Location: dashboard.php");
                    exit();
                }
            }

            // Handle mobile number update
            if (isset($_POST['update_mobile'])) {
                $newMobile = $_POST['new_mobile'];
                if (preg_match('/^[0-9]{10,20}$/', $newMobile)) {
                    $customer->updateMobileNumber($userId, $newMobile);
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $errorMsg = "Invalid mobile number format. Please enter a valid mobile number (10-20 digits).";
                }
            }

            // Handle address update
            if (isset($_POST['update_address'])) {
                $newAddress = $_POST['new_address'];
                $customer->updateAddress($userId, $newAddress);
                header("Location: dashboard.php");
                exit();
            }
        }

        // Display dashboard UI with user details and update forms
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>User Dashboard</title>
            <link rel="stylesheet" href="../css/dashboard.css">
            <style>
                /* Style for error messages */
                .error-message {
                    color: red;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
        <div class="container">
            <div class="user-details">
                <h3>User Information</h3>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($userData['Username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['Email']); ?></p>
                <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($userData['MobileNumber']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($userData['Address']); ?></p>
            </div>

            <div class="update-form">
                <h3>Update Details</h3>
                <?php
                // Display error message if set
                if (!empty($errorMsg)) {
                    echo "<p class='error-message'>" . $errorMsg . "</p>";
                }
                ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <label for="new_username">New Username:</label>
                    <input type="text" id="new_username" name="new_username" required>
                    <button type="submit" name="update_username">Update Username</button>
                </form>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <label for="new_email">New Email:</label>
                    <input type="email" id="new_email" name="new_email" required>
                    <button type="submit" name="update_email">Update Email</button>
                </form>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <label for="new_mobile">New Mobile Number (10 digits):</label>
                    <input type="text" id="new_mobile" name="new_mobile" maxlength="20" pattern="[0-9]{10,20}" title="Please enter a valid mobile number (10-20 digits)" required>
                    <button type="submit" name="update_mobile">Update Mobile Number</button>
                </form>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <label for="new_address">New Address:</label>
                    <input type="text" id="new_address" name="new_address" required>
                    <button type="submit" name="update_address">Update Address</button>
                </form>
            </div>
            <form method="get" action="../public/passwordchanger.php">
                <button type="submit">Change Password</button>
            </form>
        </div>
        </body>
        </html>
        <?php
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    echo "User not logged in"; // Handle case where user is not logged in
}

// Include footer
include '../template/footer.php';
?>
