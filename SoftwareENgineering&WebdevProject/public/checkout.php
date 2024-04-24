<?php
// Ensure session is started at the top if you are using session variables
include '../template/header.php';
require '../src/dbconnect.php';
require '../classes/Customer.php';
require '../classes/Payment.php';
require '../classes/Order.php';
require '../classes/Products.php';

global $pdo;
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header("Location: ../public/login.php");
    exit;
}

// Instantiate the Order object here after including its class file
$order = new Order($pdo); // Make sure $pdo is correctly initialized and connected

$customer = new Customer($pdo);
$userData = $customer->getUserDataById($userId);
if (!$userData) {
    echo "User not found.";
    exit;
}

$payment = new Payment($pdo);
$cards = $payment->getAllCards($userId);

// Ensure that the cart total is retrieved before the POST request processing
$TotalAmount = $_SESSION['cart'] ?? 0; // Fetch the total amount from the session

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    $paymentId = $_POST['payment_method'];
    $newOrderId = $order->createOrder($userId, $TotalAmount, $paymentId);
    if ($newOrderId) {
        header("Location: ../public/ordersummary.php?orderId=" . $newOrderId);
        exit;
    } else {
        $message = "Failed to create order. Please try again.";
    }
}
// Correct the initial assignment of $totalAmount
// This should be a sum of the product prices times their quantity, not the cart array itself.
// ... Other initializations ...

$productObj = new Products($pdo);
$cartItems = $productObj->getCartItems();

$TotalAmount = 0;
foreach ($cartItems as $item) {
    $TotalAmount += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    $paymentId = $_POST['payment_method'];
    $newOrderId = $order->createOrder($userId, $TotalAmount, $paymentId);
}
$TotalAmount = 0;
foreach ($cartItems as $item) {
    $TotalAmount += $item['price'] * $item['quantity'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/checkout.css">
    <title>Checkout</title>
</head>
<body>
<div class="user-details">
    <h2>User Information</h2>
    <p><strong>Username:</strong> <?= htmlspecialchars($userData['Username'] ?? 'N/A') ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($userData['Email'] ?? 'N/A') ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($userData['Address'] ?? 'N/A') ?></p>
    <a href="../public/dashboard.php" class="edit-btn">Edit User Info</a>
</div>
<div class="cart-items">
    <h2>Cart Summary</h2>
    <ul>
        <?php foreach ($cartItems as $item): ?>
            <li>
                <span><?= htmlspecialchars($item['name']) ?></span>
                <span><?= htmlspecialchars($item['quantity']) ?> x €<?= htmlspecialchars($item['price']) ?></span>
                <span>Total: €<?= htmlspecialchars($item['quantity'] * $item['price']) ?></span>
            </li>
        <?php endforeach; ?>
        <li class="cart-total">
            Cart Total: €<?= htmlspecialchars($TotalAmount) ?>
        </li>
    </ul>
</div>

<div class="payment-info">
    <h2>Payment Methods</h2>
    <form action="checkout.php" method="post">
        <?php foreach ($cards as $card): ?>
            <div class="card">
                <input type="radio" id="card_<?= $card['idPayment'] ?>" name="payment_method" value="<?= $card['idPayment'] ?>" required>
                <label for="card_<?= $card['idPayment'] ?>"><?= htmlspecialchars($card['paymentName'] ?? 'Unknown Payment Method') ?></label>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="add-btn">Complete Checkout</button>
    </form>
</div>
<?php if (isset($message)) echo "<p>$message</p>"; ?>
<?php include '../template/footer.php'; ?>
</body>
</html>
