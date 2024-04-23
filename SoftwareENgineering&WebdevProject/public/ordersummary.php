<?php
include '../template/header.php';
include '../src/dbconnect.php';
require '../classes/Order.php';
require '../classes/Delivery.php';
require '../classes/OrderSummary.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit;
}

$order = new Order($pdo);

$orderId = isset($_GET['orderId']) ? $_GET['orderId'] : null;
if (!$orderId) {
    echo "No order ID provided.";
    exit;
}

$orderDetails = $order->getOrderDetails($orderId);
if (!$orderDetails) {
    echo "Order not found.";
    exit;
}

$deliveryDate = date('d M Y', strtotime($orderDetails['orderDate'] . ' + 2 weeks'));
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
    <p><strong>Total Amount:</strong> $<?= htmlspecialchars($orderDetails['totalAmount']) ?></p>
    <p><strong>Order Date:</strong> <?= date('d M Y', strtotime($orderDetails['orderDate'])) ?></p>
    <p><strong>Delivery Date:</strong> <?= $deliveryDate ?></p>
</div>
<?php include '../template/footer.php'; ?>
</body>
</html>
