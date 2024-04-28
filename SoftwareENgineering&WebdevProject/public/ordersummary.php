<?php
include '../template/header.php';
require '../src/dbconnect.php';
require '../classes/Order.php';
require '../classes/Delivery.php';

global $pdo;
$order = new Order($pdo);
$delivery = new Delivery($pdo);

$orderId = isset($_GET['orderId']) ? filter_var($_GET['orderId'], FILTER_SANITIZE_NUMBER_INT) : null;

if (!$orderId) {
    echo "No order ID provided.";
    exit;
}

$orderDetails = $order->getOrderDetails($orderId);
$deliveryDetails = $delivery->getDeliveryDetailsByOrderID($orderId);

if (!$orderDetails) {
    echo "Order not found.";
    exit;
}

$totalAmount = isset($orderDetails['TotalAmount']) ? "€" . number_format($orderDetails['TotalAmount'], 2) : "Amount not available";
$deliveryDate = isset($deliveryDetails['DeliveryDate']) ? date('d M Y', strtotime($deliveryDetails['DeliveryDate'])) : "Delivery date not available";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Summary</title>
    <link rel="stylesheet" href="../css/checkout.css">
</head>
<body>
<div class="container">
    <h1>Order Summary</h1>
    <p><strong>Order ID:</strong> <?= htmlspecialchars($orderId) ?></p>
    <p><strong>Total Amount:</strong> <?= htmlspecialchars($totalAmount) ?></p>
    <p><strong>Delivery Date:</strong> <?= htmlspecialchars($deliveryDate) ?></p>
</div>
<?php include '../template/footer.php'; ?>
</body>
</html>
