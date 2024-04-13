<?php
// Include necessary files
include '../src/dbconnect.php';
include '../template/header.php';
include '../classes/Payment.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create a new Payment object
    $payment = new Payment();

    // Set customer ID from session
    $payment->setCustomerID($_SESSION['user_id']);

    // Set payment details from form inputs
    $payment->setPaymentDate($_POST['payment_date']);
    $payment->setPaymentMethod($_POST['payment_method']);
    $payment->setPaymentName($_POST['payment_name']);
    $payment->setPaymentNumber($_POST['payment_number']);
    $payment->setPaymentCCV(password_hash($_POST['payment_ccv'], PASSWORD_DEFAULT));
    $payment->setPaymentExpiryDate($_POST['payment_expiry_date']); // Setting the expiry date

    // Validate payment method (only Mastercard or Visa allowed)
    if ($payment->getPaymentMethod() !== "Mastercard" && $payment->getPaymentMethod() !== "Visa") {
        echo "Error: Only Mastercard and Visa are allowed as payment methods.";
        exit;
    }

    // Insert payment details into the database using PDO prepared statements
    $sql = "INSERT INTO payment (idCustomer, paymentDate, paymentMethod, paymentName, paymentNumber, paymentCCV, paymentExpiryDate) 
            VALUES (:idCustomer, :paymentDate, :paymentMethod, :paymentName, :paymentNumber, :paymentCCV, :paymentExpiryDate)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idCustomer', $customerID = $payment->getCustomerID());
    $stmt->bindParam(':paymentDate', $paymentDate = $payment->getPaymentDate());
    $stmt->bindParam(':paymentMethod', $paymentMethod = $payment->getPaymentMethod());
    $stmt->bindParam(':paymentName', $paymentName = $payment->getPaymentName());
    $stmt->bindParam(':paymentNumber', $paymentNumber = $payment->getPaymentNumber());
    $stmt->bindParam(':paymentCCV', $paymentCCV = $payment->getPaymentCCV());
    $stmt->bindParam(':paymentExpiryDate', $paymentExpiryDate = $payment->getPaymentExpiryDate());

    if ($stmt->execute()) {
        echo "Payment details saved successfully.";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }

    $stmt->closeCursor();
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
        Payment Date: <input type="date" name="payment_date"><br><br>
        Payment Method:
        <select name="payment_method">
            <option value="Mastercard">Mastercard</option>
            <option value="Visa">Visa</option>
        </select><br><br>
        Payment Name: <input type="text" name="payment_name"><br><br>
        Payment Number: <input type="text" name="payment_number"><br><br>
        Payment CCV: <input type="password" name="payment_ccv"><br><br>
        Payment Expiry Date: <input type="month" name="payment_expiry_date"><br><br> <!-- Expiry date input -->
        <input type="submit" value="Save Payment">
    </form>
</div>
</body>
</html>

<?php include '../template/footer.php'; ?>
