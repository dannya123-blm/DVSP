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

$orderDetails = $order->getOrderDetails($orderId);
$deliveryDetails = $delivery->getDeliveryDetailsByOrderID($orderId);

if (!$orderDetails) {
    echo "Order not found.";
    exit;
}

$totalAmount = isset($orderDetails['TotalAmount']) ? "€" . number_format($orderDetails['TotalAmount'], 2) : "Amount not available";
$purchaseDate = isset($orderDetails['purchaseDate']) ? date('d M Y', strtotime($orderDetails['purchaseDate'])) : "Purchase date not available"; // Format the fetched purchase date

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
    <p><strong>Day of Purchase Date:</strong> <?= htmlspecialchars($purchaseDate) ?></p> <!-- Changed label to "Day of Purchase Date" -->
</div>
<?php include '../template/footer.php'; ?>
</body>
</html>
