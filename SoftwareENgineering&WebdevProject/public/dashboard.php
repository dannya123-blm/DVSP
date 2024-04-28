<?php
global $pdo;
include '../template/header.php';
include '../src/dbconnect.php';
require '../classes/Customer.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $customer = new Customer($pdo);
    $userData = $customer->getUserDataById($userId);

    $errorMsg = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle username update
        if (isset($_POST['update_username'])) {
            $newUsername = $_POST['new_username'];
            if ($customer->usernameExists($newUsername)) {
                $errorMsg = "Username already exists. Please choose a different username.";
            } else {
                $customer->updateUsername($userId, $newUsername);
                header("Location: dashboard.php");
                exit;
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
                exit;
            }
        }

        // Handle mobile number update
        if (isset($_POST['update_mobile'])) {
            $newMobile = $_POST['new_mobile'];
            if (preg_match('/^[0-9]{10,20}$/', $newMobile)) {
                try {
                    $customer->updateMobileNumber($userId, $newMobile);
                    header("Location: dashboard.php");
                    exit;
                } catch (Exception $e) {
                    $errorMsg = "Error updating mobile number: " . $e->getMessage();
                }
            } else {
                $errorMsg = "Invalid mobile number format. Please enter a valid mobile number (10-20 digits).";
            }
        }

        // Handle address update
        if (isset($_POST['update_address'])) {
            $newAddress = $_POST['new_address'];
            $customer->updateAddress($userId, $newAddress);
            header("Location: dashboard.php");
            exit;
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
