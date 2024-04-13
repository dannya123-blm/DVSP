<?php
// Include header and database connection
include '../template/header.php';
include '../src/dbconnect.php';
require '../classes/Customer.php'; // Include Customer class file

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    try {
        $userId = $_SESSION['user_id']; // Assuming the logged-in user's ID is stored in $_SESSION['user_id']

        // Create a new Customer object
        $customer = new Customer();

        // Fetch user data from the database using Customer class method
        $userData = $customer->getUserDataById($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submissions for updating user details
            if (isset($_POST['update_username'])) {
                // Process username update
                $newUsername = $_POST['new_username'];
                $customer->updateUsername($userId, $newUsername);
                // Refresh the page after updating
                header("Location: dashboard.php");
                exit();
            }

            if (isset($_POST['update_email'])) {
                // Process email update
                $newEmail = $_POST['new_email'];
                $customer->updateEmail($userId, $newEmail);
                // Refresh the page after updating
                header("Location: dashboard.php");
                exit();
            }

            if (isset($_POST['update_mobile'])) {
                // Process mobile number update
                $newMobile = $_POST['new_mobile'];

                // Validate mobile number format (10 digits)
                if (preg_match('/^[0-9]{10}$/', $newMobile)) {
                    $customer->updateMobileNumber($userId, $newMobile);
                    // Refresh the page after updating
                    header("Location: dashboard.php");
                    exit();
                } else {
                    // Invalid mobile number format
                    header("Location: dashboard.php?error=InvalidMobileNumber");
                    exit();
                }
            }

            if (isset($_POST['update_address'])) {
                // Process address update
                $newAddress = $_POST['new_address'];
                $customer->updateAddress($userId, $newAddress);
                // Refresh the page after updating
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
                // Display error message if redirected with error parameter
                if (isset($_GET['error']) && $_GET['error'] === 'InvalidMobileNumber') {
                    echo "<p style='color: red;'>Invalid mobile number format. Please enter a 10-digit mobile number.</p>";
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
                    <input type="text" id="new_mobile" name="new_mobile" pattern="[0-9]{10}" title="Please enter a 10-digit mobile number" required>
                    <button type="submit" name="update_mobile">Update Mobile Number</button>
                </form>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <label for="new_address">New Address:</label>
                    <input type="text" id="new_address" name="new_address" required>
                    <button type="submit" name="update_address">Update Address</button>
                </form>
            </div>
            <form method="get" action="passwordchanger.php">
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
