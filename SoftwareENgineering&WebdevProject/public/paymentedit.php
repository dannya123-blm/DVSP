<?php

global $pdo;
include '../src/dbconnect.php';
include '../template/header.php';
include '../classes/Payment.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$customerID = $_SESSION['user_id'];

// Create Payment instance
$payment = new Payment($pdo);

// Retrieve all credit card information for the logged-in user
$cards = $payment->getAllCards($customerID);

// Delete payment if requested
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $paymentId = $_GET['id'];

    // Delete the payment using the Payment class method
    $payment->deletePayment($paymentId);

    // Redirect back to paymentedit.php after deletion
    header("Location: paymentedit.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Edit</title>
    <link rel="stylesheet" href="../css/paymentedit.css">
</head>
<body>
<div class="payment-edit-container">
    <h2>Your Credit Cards</h2>

    <div class="cards-container">
        <?php foreach ($cards as $card): ?>
            <div class="card">
                <p><?php echo $card['PaymentName']; ?></p>
                <p><?php echo $card['PaymentNumber']; ?></p>
                <a href="paymentediter.php">Edit</a>
                <a href="paymentedit.php?id=<?php echo $card['idPayment']; ?>&action=delete">Delete</a>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="../public/payment.php">Add New Card</a>
</div>

</body>
</html>

<?php include '../template/footer.php'; ?>
