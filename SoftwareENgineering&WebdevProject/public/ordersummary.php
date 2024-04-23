<?php
global $pdo;
session_start();
include '../src/dbconnect.php'; // Ensures $pdo is properly initialized.
require '../classes/Customer.php';
require '../classes/Payment.php';
require '../classes/Products.php';
require '../classes/Order.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit;
}

$products = new Products($pdo);
$payment = new Payment($pdo);
$order = new Order($pdo);
$customer = new Customer($pdo);
$userData = $customer->getUserDataById($_SESSION['user_id']);

if (!$userData) {
    echo "User not found.";
    exit;
}

$cards = $payment->getAllCards($_SESSION['user_id']);
$cartItems = [];
$totalAmount = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $products->getProductById($productId);
        if ($product) {
            $cartItems[$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'subtotal' => $product['price'] * $quantity
            ];
            $totalAmount += $cartItems[$productId]['subtotal'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    $paymentId = $_POST['payment_method'];
    $orderCreated = $order->createOrder($_SESSION['user_id'], $totalAmount, $paymentId);
    if ($orderCreated) {
        header("Location: ../public/ordersummary.php?orderId=" . $order->getIdOrder()); // Assuming getIdOrder returns the last order ID.
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
<h1>Order Summary</h1>
<p><strong>Order ID:</strong> <?= htmlspecialchars($orderId) ?></p>
<p><strong>Total Amount:</strong> $<?= htmlspecialchars($orderDetails['totalAmount']) ?></p>
<p><strong>Delivery Address:</strong> <?= htmlspecialchars($userDetails['Address']) ?></p>
<p><strong>Delivery Date:</strong> <?= $deliveryDate ?></p>
<?php include '../template/footer.php'; ?>
</body>
</html>
