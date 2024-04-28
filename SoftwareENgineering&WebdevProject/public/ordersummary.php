<?php
include '../template/header.php';
require '../src/dbconnect.php';
require '../classes/Order.php';
require '../classes/Delivery.php';

global $pdo;
$order = new Order($pdo);
$delivery = new Delivery($pdo);
$orderId = $_GET['orderId'] ?? null;

if (!$orderId) {
    echo "No order ID provided.";
    exit;
}

$deliveryDetails = $delivery->getDeliveryDetailsByOrderID($orderId);
if (!$deliveryDetails) {
    echo "Delivery details not found.";
    exit;
}

$orderDetails = $order->getOrderDetails($orderId);
if (!$orderDetails) {
    echo "Order not found.";
    exit;
}

$totalAmount = isset($orderDetails['TotalAmount']) ? "€" . number_format($orderDetails['TotalAmount'], 2) : "Amount not available";
$deliveryDate = isset($deliveryDetails['DeliveryDate']) ? date('d M Y', strtotime($deliveryDetails['DeliveryDate'])) : "Delivery date not available"; // Fixed line here
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
    <p><strong>Delivery Date:</strong> <?= htmlspecialchars($deliveryDate) ?></p> <!-- Corrected variable name -->
</div>
<?php include '../template/footer.php'; ?>
</body>
</html>
