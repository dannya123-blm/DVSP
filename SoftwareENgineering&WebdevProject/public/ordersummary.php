<?php
global $pdo;
include '../template/header.php';
include '../src/dbconnect.php';
require '../classes/Order.php';

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit;
}

// Create an Order object
$order = new Order($pdo);

// Get the orderId from the GET parameter or redirect if not set
$orderId = isset($_GET['orderId']) ? $_GET['orderId'] : null;
if (!$orderId) {
    echo "No order ID provided.";
    exit;
}

// Fetch order details from the database
$orderDetails = $order->getOrderDetails($orderId);
if (!$orderDetails) {
    echo "Order not found.";
    exit;
}

// Calculate the purchase date
$purchaseDate = date('d M Y', strtotime($orderDetails['orderDate']));
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
    <p><strong>Purchase Date:</strong> <?= $purchaseDate ?></p>
    <!-- Button to check delivery status -->
    <button onclick="window.location.href='deliveryStatus.php?orderId=<?= htmlspecialchars($orderId) ?>';">Check Delivery</button>
</div>
<?php include '../template/footer.php'; ?>
</body>
</html>
