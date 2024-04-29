<?php
global $pdo;
include '../src/dbconnect.php'; // Include the file containing database connection
include '../template/header.php';
include '../classes/Payment.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$payment = new Payment($pdo);
$paymentId = null;
$paymentInfo = null;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentId = $_POST['payment_id'];
    $paymentInfo = $payment->getPaymentInfo($paymentId);

    // Check if payment exists and belongs to the logged-in user
    if ($paymentInfo && $paymentInfo['idCustomer'] == $_SESSION['user_id']) {
        $paymentName = $_POST['payment_name'];
        $paymentNumber = $_POST['payment_number'];
        $paymentCCV = $_POST['payment_ccv'];
        $paymentExpiryDate = $_POST['payment_expiry_date'] . '-01';

        // Check if expiry date is in the future
        if (strtotime($paymentExpiryDate) < strtotime(date('Y-m'))) {
            echo "Error: Expiry date must be the current month or a future month.";
            exit;
        }

        $payment->updatePayment($paymentId, $paymentName, $paymentNumber, $paymentCCV, $paymentExpiryDate);

        header("Location: paymentedit.php");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $paymentId = $_GET['id'];
    $paymentInfo = $payment->getPaymentInfo($paymentId);

    if (!$paymentInfo || $paymentInfo['idCustomer'] != $_SESSION['user_id']) {
        header("Location: paymentedit.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
    <link rel="stylesheet" href="../css/paymenteditor.css">
</head>
<body>
<div class="payment-edit-container">
    <h2>Edit Payment</h2>

    <form id="paymentForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="payment_id" value="<?php echo htmlspecialchars($paymentId); ?>">
        <div class="form-group">
            <label for="payment_name">Payment Name:</label>
            <input type="text" id="payment_name" name="payment_name" value="<?php echo htmlspecialchars($paymentInfo['PaymentName'] ?? ''); ?>" pattern="[A-Za-z ]+" title="Please enter a valid name (letters and spaces only)" required>
        </div>
        <div class="form-group">
            <label for="payment_number">Payment Number:</label>
            <input type="text" id="payment_number" name="payment_number" value="<?php echo htmlspecialchars($paymentInfo['PaymentNumber'] ?? ''); ?>" pattern="[0-9]+" title="Please enter a valid number (digits only)" required>
        </div>
        <div class="form-group">
            <label for="payment_ccv">Payment CCV:</label>
            <input type="password" id="payment_ccv" name="payment_ccv" value="<?php echo htmlspecialchars($paymentInfo['PaymentCCV'] ?? ''); ?>" pattern="[0-9]{3,4}" title="Please enter a valid CCV (3 or 4 digits)" required>
        </div>
        <div class="form-group">
            <label for="payment_expiry_date">Expiry Date:</label>
            <input type="month" id="payment_expiry_date" name="payment_expiry_date" value="<?php echo isset($paymentInfo['PaymentExpiryDate']) ? date('Y-m', strtotime($paymentInfo['PaymentExpiryDate'])) : ''; ?>" min="<?php echo date('Y-m'); ?>" required>
        </div>
        <button type="submit">Update Payment</button>
    </form>
</div>

</body>
</html>

<?php include '../template/footer.php'; ?>
