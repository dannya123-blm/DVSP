<?php
// Include header and database connection
include '../template/header.php';
include '../src/dbconnect.php';
require '../classes/Customer.php';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    try {
        $userId = $_SESSION['user_id'];

        // Create a new Customer object
        $customer = new Customer();

        // Fetch user data from the database
        $userData = Customer::getUserDataById($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['change_password'])) {
                $oldPassword = $_POST['old_password'];
                $newPassword = $_POST['new_password'];

                // Verify old password before changing
                if ($customer->verifyPassword($userId, $oldPassword)) {
                    // Password verification successful, check new password strength
                    if (validatePasswordStrength($newPassword)) {
                        // Update the password
                        $customer->updatePassword($userId, $newPassword);
                        echo "Password updated successfully.";
                    } else {
                        echo "New password must be at least 8 characters long and contain at least one uppercase letter.";
                    }
                } else {
                    echo "Incorrect old password. Please try again.";
                }
            }
        }

        // Display password change form
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Password Change</title>
            <link rel="stylesheet" href="../css/passwordchanger.css">
            <script>
                // Client-side password strength validation
                function validatePassword() {
                    var newPassword = document.getElementById('new_password').value;
                    if (newPassword.length < 8 || !/[A-Z]/.test(newPassword)) {
                        alert('New password must be at least 8 characters long and contain at least one uppercase letter.');
                        return false; // Prevent form submission
                    }
                    return true; // Allow form submission
                }
            </script>
        </head>
        <body>
        <h2>Password Change</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" onsubmit="return validatePassword()">
            <label for="old_password">Current Password:</label>
            <input type="password" id="old_password" name="old_password" required><br><br>
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required><br><br>
            <button type="submit" name="change_password">Change Password</button>
        </form>
        </body>
        </html>
        <?php
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    echo "User not logged in";
}

// Function to validate password strength
function validatePasswordStrength($password) {
    return strlen($password) >= 8 && preg_match('/[A-Z]/', $password);
}
?>
