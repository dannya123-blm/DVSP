<?php
global $pdo;
include '../template/header.php';
include '../src/dbconnect.php';
require '../classes/Order.php';
require '../classes/Products.php';

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

// Safely handle potentially null values for TotalAmount
$totalAmount = isset($orderDetails['TotalAmount']) ? "€" . number_format($orderDetails['TotalAmount'], 2) : "Amount not available";

// Automatically generate purchase date as current date and time
$purchaseDate = date('d M Y, H:i:s');

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
    <p><strong>Purchase Date:</strong> <?= htmlspecialchars($purchaseDate) ?></p>
    <button onclick="window.location.href='deliveryStatus.php?orderId=<?= htmlspecialchars($orderId) ?>';">Check Delivery Status</button>
</div>
<?php include '../template/footer.php'; ?>
</body>
</html>
