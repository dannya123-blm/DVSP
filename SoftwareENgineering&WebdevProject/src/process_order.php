<?php
session_start(); // Start or resume the session
include '../src/dborder.php'; // Include database connection
include '../classes/Order.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    $idCustomer = $_SESSION['user_id'];
    $idAdmin = 1; // Example admin ID
    $orderDate = date('Y-m-d H:i:s');
    $totalAmount = calculateTotalAmount(); // Implement this function to calculate total amount
    $idPayment = 1; // Example payment ID

    $order = new Order($idCustomer, $idAdmin, $orderDate, $totalAmount, $idPayment);

    $pdo = getConnection(); // Assuming getConnection() function is defined in dbconnect.php

    try {
        $orderSaved = $order->saveOrderToDatabase($pdo);

        if ($orderSaved) {
            // Retrieve the auto-generated order ID
            $orderId = $pdo->lastInsertId();

            unset($_SESSION['cart']); // Clear the cart after placing the order
            echo '<div class="success-message">Order placed successfully!</div>';
            echo '<p>Order ID: ' . $orderId . '</p>';
        } else {
            echo '<div class="error-message">Failed to place order. Please try again.</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="error-message">Error saving order: ' . $e->getMessage() . '</div>';
    }
}

function calculateTotalAmount() {
    global $pdo;
    $totalAmount = 0;

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Implement your logic to calculate total amount from cart
        // Use the PDO connection to fetch product prices, etc.
        // Replace this with your own calculation logic
    }

    return $totalAmount;
}
?>
