<?php
global $pdo;
require '../src/dbconnect.php';
require '../template/header.php';
require '../classes/Payment.php';

// Redirect if user not logged in
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$customerID = $_SESSION['user_id'];
$payment = new Payment($pdo);
$cards = $payment->getAllCards($customerID);

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET['id']) && !empty($_GET['action']) && $_GET['action'] == 'delete') {
    $paymentId = $_GET['id'];
    $payment->deletePayment($paymentId);
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
                <!-- Display the payment name and the last four digits of the payment number -->
                <p><?php echo htmlspecialchars($card['paymentName'] ?? 'Name'); ?></p>
                <p>Card ending in: <?php echo htmlspecialchars($card['lastFourDigits'] ?? 'XXXX'); ?></p>
                <p>Expiry: <?php
                    // Check if PaymentExpiryDate is not null
                    $expiryDate = $card['PaymentExpiryDate'] ?? 'today';
                    echo htmlspecialchars(date("m/Y", strtotime($expiryDate)));
                    ?></p>
                <a href="paymenteditor.php?id=<?php echo $card['idPayment']; ?>">Edit</a>
                <a href="paymentedit.php?id=<?php echo $card['idPayment']; ?>&action=delete">Delete</a>

            </div>
        <?php endforeach; ?>
    </div>
    <a href="../public/payment.php">Add New Card</a>
</div>

<?php include '../template/footer.php'; ?>
</body>
</html>
