<?php
require '../template/header.php';
require '../src/dbconnect.php';
require '../classes/Customer.php';
require '../classes/Payment.php';
require '../classes/Products.php';

$customer = new Customer($pdo);
$payment = new Payment($pdo);
$products = new Products($pdo);

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: ../public/login.php");
    exit;
}

try {
    $userData = $customer->getUserDataById($userId);
    if (!$userData) {
        throw new Exception("User not found.");
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
    exit;
}

// Fetch all cards using the Payment class method
$cards = $payment->getAllCards($userId);
?>
<link rel="stylesheet" href="../css/checkout.css">
<div class="user-details">
    <h2>User Information</h2>
    <p><strong>Username:</strong> <?= htmlspecialchars($userData['Username'] ?? 'N/A') ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($userData['Email'] ?? 'N/A') ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($userData['Address'] ?? 'N/A') ?></p>
    <a href="../public/dashboard.php" class="edit-btn">Edit User Info</a>
</div>

<div class="payment-info">
    <h2>Payment Methods</h2>
    <form action="processpayment.php" method="post">
        <?php if (!empty($cards)): ?>
            <div class="cards-container">
                <?php foreach ($cards as $card): ?>
                    <div class="card">
                        <input type="radio" id="card_<?= htmlspecialchars($card['PaymentId'] ?? '') ?>" name="payment_method" value="<?= htmlspecialchars($card['PaymentId'] ?? '') ?>">
                        <label for="card_<?= htmlspecialchars($card['PaymentId'] ?? '') ?>">
                            <p><?= htmlspecialchars($card['PaymentName'] ?? 'N/A'); ?></p>
                            <p>Number: <?= isset($card['PaymentNumber']) ? htmlspecialchars(substr($card['PaymentNumber'], -4)) : 'N/A' ?> (last 4 digits)</p>
                            <p>Expiry: <?= isset($card['PaymentExpiryDate']) ? htmlspecialchars(date("m/Y", strtotime($card['PaymentExpiryDate']))) : 'N/A'; ?></p>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No payment method added.</p>
        <?php endif; ?>
        <button type="submit" class="add-btn">Use Selected Payment Method</button>
        <a href="../public/payment.php" class="add-btn">Add Payment Method</a>
        <a href="../public/paymentedit.php" class="add-btn">Edit Payment Method</a>
    </form>
</div>

<?php include '../template/footer.php'; ?>
