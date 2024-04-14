<?php
global $pdo;
include '../template/header.php';
include '../src/dbconnect.php';
require '../classes/OrderSummary.php';

if (isset($_GET['orderId'])) {
    $orderId = $_GET['orderId'];
    $orderSummary = new OrderSummary();
    if ($orderSummary->loadFromDatabase($pdo, $orderId)) {
        // Display order summary details
        echo "<h1>Order Summary</h1>";
        echo "<p>Order Summary ID: " . htmlspecialchars($orderSummary->getOrderSummaryID()) . "</p>";
        echo "<p>Order ID: " . htmlspecialchars($orderSummary->getOrderID()) . "</p>";
        echo "<p>Subtotal: $" . htmlspecialchars($orderSummary->getSubtotal()) . "</p>";
    } else {
        echo "Order summary not found.";
    }
} else {
    echo "No order ID provided.";
}
?>
