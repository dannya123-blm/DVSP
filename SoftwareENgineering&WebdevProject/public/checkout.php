<?php

include '../template/header.php';
require '../src/dbconnect.php';
require '../classes/Customer.php';
require '../classes/Payment.php';
require '../classes/Order.php';
require '../classes/Products.php';
require '../classes/Delivery.php';

global $pdo;
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header("Location: ../public/login.php"); // Redirect if not logged in
    exit;
}

$customer = new Customer($pdo);
$userData = $customer->getUserDataById($userId);
$payment = new Payment($pdo);
$cards = $payment->getAllCards($userId);

$productObj = new Products($pdo);
$cartItems = $productObj->getCartItems(); // Presuming this method gets cart items with quantity and price
$totalAmount = $_SESSION['totalAmount'] ?? 0; // Retrieve the total amount from session

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    session_regenerate_id(); // Security measure to prevent session fixation
    $paymentId = $_POST['payment_method'];
    $order = new Order($pdo);
    $newOrderId = $order->createOrder($userId, $totalAmount, $paymentId);

    if ($newOrderId) {
        $delivery = new Delivery($pdo);
        $delivery->createDelivery($newOrderId, $userData['Address']); // Assuming address from user data

        unset($_SESSION['cart']); // Clear the cart after the order is successfully placed
        unset($_SESSION['totalAmount']); // Clear the total amount from session
        header("Location: ../public/ordersummary.php?orderId=" . $newOrderId);
        exit;
    } else {
        $message = "Failed to create order. Please try again.";
    }
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
            </li>
        <?php endforeach; ?>
        <li class="cart-total">
            Cart Total: €<?= htmlspecialchars($totalAmount) ?>
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
