<?php
global $pdo;
include '../template/header.php';
require '../src/dbconnect.php';
require '../classes/Customer.php';
require '../classes/Payment.php';
require '../classes/Products.php';
require '../classes/Order.php';
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header("Location: ../public/login.php");
    exit;
}

$products = new Products($pdo);
$payment = new Payment($pdo);
$order = new Order($pdo);
$customer = new Customer($pdo);
$orderID = new Customer($pdo);
$userData = $customer->getUserDataById($userId);
if (!$userData) {
    echo "User not found.";
    exit;
}

$cards = $payment->getAllCards($userId);
$cartItems = [];
$totalAmount = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $products->getProductById($productId);
        if ($product) {
            $cartItems[$productId] = [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $quantity,
                'subtotal' => $product->getPrice() * $quantity
            ];
            $totalAmount += $cartItems[$productId]['subtotal'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    $paymentId = $_POST['payment_method'];
    $orderCreated = $order->createOrder($userId, $totalAmount, $paymentId);
    if ($orderCreated) {
        header("Location: ../public/ordersummary.php");
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
<div class="cart-details">
    <h2>Your Order</h2>
    <ul>
        <?php foreach ($cartItems as $item): ?>
            <li><?= htmlspecialchars($item['name']) ?> - $<?= number_format($item['price'], 2) ?>
                 <?= $item['quantity'] ?> = $<?= number_format($item['subtotal'], 2) ?></li>
        <?php endforeach; ?>
        <li><strong>Total: $<?= number_format($totalAmount, 2) ?></strong></li>
    </ul>
</div>

<div class="payment-info">
    <h2>Payment Methods</h2>
    <form action="checkout.php" method="post">
        <?php if (!empty($cards)): ?>
            <div class="cards-container">
                <?php foreach ($cards as $card): ?>
                    <div class="card">
                        <input type="radio" id="card_<?= $card['idPayment'] ?>" name="payment_method" value="<?= $card['idPayment'] ?>" required>
                        <label for="card_<?= $card['idPayment'] ?>"><?= htmlspecialchars($card['paymentName'] ?? 'Unknown Payment Method') ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No payment method added. <a href="../public/payment.php" class="add-btn">Add Payment Method</a></p>
        <?php endif; ?>
        <button type="submit" class="add-btn">Complete Checkout</button>
    </form>
</div>
<?php if (isset($message)) echo "<p>$message</p>"; ?>
<?php include '../template/footer.php'; ?>
</body>
</html>