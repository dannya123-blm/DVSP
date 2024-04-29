<?php
global $pdo;
include '../template/header.php';
include '../src/dbconnect.php';
require '../classes/Customer.php';
if (!isset($_SESSION['2fa_confirmation_code'])) {
    header("Location: dashboard.php");
    exit;
}

$errorMsg = '';
$successMsg = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['confirm_code']) && $_POST['confirm_code'] == $_SESSION['2fa_confirmation_code']) {

        unset($_SESSION['2fa_confirmation_code']);
        $successMsg = "Two-Factor Authentication action confirmed successfully.";
        header("Location: dashboard.php");
        exit; // Stop further execution
    } else {
        // If the codes don't match, show an error message
        $errorMsg = "Invalid confirmation code. Please try again.";
    }
}

// Function to generate a random confirmation code
function generateConfirmationCode($length = 6) {
    return substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)))),1,$length);
}

// Generate a random confirmation code
$confirmationCode = generateConfirmationCode();

// Store the confirmation code in the session
$_SESSION['2fa_confirmation_code'] = $confirmationCode;

// Get the user's email from the session or wherever it's stored
$userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'your_email@example.com';

$subject = 'Two-Factor Authentication Confirmation Code';
$message = 'Your Two-Factor Authentication confirmation code is: ' . $confirmationCode;
$headers = 'From: your_email@example.com' . "\r\n" .
    'Reply-To: your_email@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

// Send the email
mail($userEmail, $subject, $message, $headers);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Confirmation</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<div class="container">
    <h2>Confirm Two-Factor Authentication Action</h2>
    <p>An email with the confirmation code has been sent to your email address. Please check your inbox.</p>
    <?php if (!empty($errorMsg)) : ?>
        <p class="dashboard-error"><?php echo $errorMsg; ?></p>
    <?php endif; ?>
    <?php if (!empty($successMsg)) : ?>
        <p class="dashboard-success"><?php echo $successMsg; ?></p>
    <?php endif; ?>
    <form class="dashboard-form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="confirm_code" class="dashboard-label">Enter Confirmation Code:</label>
        <input type="text" id="confirm_code" name="confirm_code" class="dashboard-input" required>
        <button type="submit" class="dashboard-button">Confirm</button>
    </form>
</div>
</body>
</html>