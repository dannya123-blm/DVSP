<?php
global $pdo;
include '../template/header.php';
include '../src/dbconnect.php';
require '../classes/Customer.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $customer = new Customer($pdo, $_SESSION['user_id']);
    $userData = $customer->getUserDataById($userId);
    $errorMsg = '';

    // Check if 2FA is enabled for the user
    $twoFactorEnabled = false; // Default value
    try {
        $twoFactorEnabled = $customer->isTwoFactorEnabled($userId);
    } catch (Exception $e) {
        // Handle any exceptions here
        // For now, you can leave it empty or add a log message
    }

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['enable_2fa'])) {
            try {
                // Generate and send 2FA confirmation code via email
                $confirmationCode = mt_rand(100000, 999999);
                // Send email code to $userData['Email'] here
                // For simplicity, let's assume it's sent successfully

                // Store confirmation code in session for verification
                $_SESSION['2fa_confirmation_code'] = $confirmationCode;
                // Redirect to confirmation page
                header("Location: confirm_2fa.php");
                exit;
            } catch (Exception $e) {
                $errorMsg = "Error enabling Two-Factor Authentication: " . $e->getMessage();
            }
        }

        if (isset($_POST['disable_2fa'])) {
            try {
                // Generate and send 2FA confirmation code via email
                $confirmationCode = mt_rand(100000, 999999);
                // Send email code to $userData['Email'] here
                // For simplicity, let's assume it's sent successfully

                // Store confirmation code in session for verification
                $_SESSION['2fa_confirmation_code'] = $confirmationCode;
                // Redirect to confirmation page
                header("Location: confirm_2fa.php");
                exit;
            } catch (Exception $e) {
                $errorMsg = "Error disabling Two-Factor Authentication: " . $e->getMessage();
            }
        }
    }
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
        <div class="dashboard-details">
            <h3>User Information</h3>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($userData['Username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['Email']); ?></p>
            <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($userData['MobileNumber']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($userData['Address']); ?></p>
        </div>

        <div class="dashboard-update">
            <h3>Update Details</h3>
            <?php if (!empty($errorMsg)) { echo "<p class='dashboard-error'>" . htmlspecialchars($errorMsg) . "</p>"; } ?>
            <form class="dashboard-form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <label for="new_username" class="dashboard-label">New Username:</label>
                <input type="text" id="new_username" name="new_username" class="dashboard-input" required>
                <button type="submit" name="update_username" class="dashboard-button">Update Username</button>
            </form>

            <form class="dashboard-form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <label for="new_email" class="dashboard-label">New Email:</label>
                <input type="email" id="new_email" name="new_email" class="dashboard-input" required>
                <button type="submit" name="update_email" class="dashboard-button">Update Email</button>
            </form>

            <form class="dashboard-form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <label for="new_mobile" class="dashboard-label">New Mobile Number (10-20 digits):</label>
                <input type="text" id="new_mobile" name="new_mobile" class="dashboard-input" maxlength="20" pattern="[0-9]{10,20}" title="Please enter a valid mobile number (10-20 digits)" required>
                <button type="submit" name="update_mobile" class="dashboard-button">Update Mobile Number</button>
            </form>

            <form class="dashboard-form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <label for="new_address" class="dashboard-label">New Address:</label>
                <input type="text" id="new_address" name="new_address" class="dashboard-input" required>
                <button type="submit" name="update_address" class="dashboard-button">Update Address</button>
            </form>

            <div class="activate-2fa">
                <?php if ($twoFactorEnabled) : ?>
                    <form class="dashboard-form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <button type="submit" name="disable_2fa" class="dashboard-button">Disable 2FA</button>
                    </form>
                <?php else: ?>
                    <form class="dashboard-form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <button type="submit" name="enable_2fa" class="dashboard-button">Enable 2FA</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </body>
    </html>
    <?php
} else {
    echo "User not logged in";
}
include '../template/footer.php';
?>
