<?php
global $pdo;
include '../src/dbconnect.php'; // Include the file containing database connection
include '../template/header.php';
include '../classes/Payment.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Create Payment instance
$payment = new Payment($pdo);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentId = $_POST['payment_id']; // Get payment ID from form

    // Retrieve payment details by ID
    $paymentInfo = $payment->getPaymentInfo($paymentId);

    // Check if payment exists and belongs to the logged-in user
    if ($paymentInfo && $paymentInfo['idCustomer'] == $_SESSION['user_id']) {
        // Update payment details if form is submitted
        $paymentName = $_POST['payment_name'];
        $paymentNumber = $_POST['payment_number'];
        $paymentCCV = $_POST['payment_ccv'];

        // Update payment details through Payment class method
        $payment->updatePayment($paymentId, $paymentName, $paymentNumber, $paymentCCV);

        // Redirect back to paymentedit.php after update
        header("Location: paymentedit.php");
        exit;
    } else {
        // Redirect to paymentedit.php if payment ID is invalid or doesn't belong to the user
        header("Location: paymentedit.php");
        exit;
    }
}

// Check if payment ID is provided and valid
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $paymentId = $_GET['id'];

    // Retrieve payment details by ID
    $paymentInfo = $payment->getPaymentInfo($paymentId);

    // Check if payment exists and belongs to the logged-in user
    if (!$paymentInfo || $paymentInfo['idCustomer'] != $_SESSION['user_id']) {
        // Redirect to paymentedit.php if payment ID is invalid or doesn't belong to the user
        header("Location: paymentedit.php");
        exit;
    }
}
?>

<!-- HTML code for the form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
    <link rel="stylesheet" href="../css/paymentedit.css">
</head>
<body>
<div class="payment-edit-container">
    <h2>Edit Payment</h2>

    <form id="paymentForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="payment_id" value="<?php echo $paymentId; ?>"> <!-- Add hidden field for payment ID -->
        <div class="form-group">
            <label for="payment_name">Payment Name:</label>
            <input type="text" id="payment_name" name="payment_name" value="<?php echo isset($paymentInfo['PaymentName']) ? $paymentInfo['PaymentName'] : ''; ?>" pattern="[A-Za-z ]+" title="Please enter a valid name (letters and spaces only)" required>
        </div>
        <div class="form-group">
            <label for="payment_number">Payment Number:</label>
            <input type="text" id="payment_number" name="payment_number" value="<?php echo isset($paymentInfo['PaymentNumber']) ? $paymentInfo['PaymentNumber'] : ''; ?>" pattern="[0-9]+" title="Please enter a valid number (digits only)" required>
        </div>
        <div class="form-group">
            <label for="payment_ccv">Payment CCV:</label>
            <input type="password" id="payment_ccv" name="payment_ccv" value="<?php echo isset($paymentInfo['PaymentCCV']) ? $paymentInfo['PaymentCCV'] : ''; ?>" pattern="[0-9]{3,4}" title="Please enter a valid CCV (3 or 4 digits)" required>
        </div>
        <button type="submit">Update Payment</button>
    </form>
</div>
</body>
</html>

<?php include '../template/footer.php'; ?>
