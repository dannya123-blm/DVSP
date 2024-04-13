<?php
global $pdo;
include '../src/dbconnect.php';
include '../template/header.php';
include '../classes/Payment.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment = new Payment($pdo);

    $customerID = $_SESSION['user_id'];
    $paymentDate = $_POST['payment_date'];
    $paymentMethod = $_POST['payment_method'];
    $paymentName = $_POST['payment_name'];
    $paymentNumber = $_POST['payment_number'];
    $paymentCCV = $_POST['payment_ccv'];
    $paymentExpiryDate = $_POST['payment_expiry_date'] . '-01';

    if ($paymentMethod !== "Mastercard" && $paymentMethod !== "Visa") {
        echo "Error: Only Mastercard and Visa are allowed as payment methods.";
        exit;
    }

    $payment->setPaymentDetails($customerID, $paymentDate, $paymentMethod, $paymentName, $paymentNumber, $paymentExpiryDate);
    $result = $payment->processPayment($paymentCCV);

    if (is_numeric($result)) {
        echo "Card successfully added!";
    } else {
        echo $result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="../css/payment.css">
</head>
<body>
<div class="payment-container">
    <h2>Enter Payment Details</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Payment Date: <input type="date" name="payment_date" required><br><br>
        Payment Method:
        <select name="payment_method" required>
            <option value="Mastercard">Mastercard</option>
            <option value="Visa">Visa</option>
        </select><br><br>
        Payment Name: <input type="text" name="payment_name" required><br><br>
        Payment Number: <input type="text" name="payment_number" required><br><br>
        Payment CCV: <input type="password" name="payment_ccv" required><br><br>
        Payment Expiry Date: <input type="month" name="payment_expiry_date" required><br><br>
        <input type="submit" value="Save Payment">
    </form>
</div>
</body>
</html>

<?php include '../template/footer.php'; ?>
