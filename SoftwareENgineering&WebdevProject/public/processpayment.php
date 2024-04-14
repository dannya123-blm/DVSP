<?php
include'../template/header.php';
require '../src/dbconnect.php';  // Ensures the PDO object for database connection is available
require '../classes/Order.php'; // Includes the updated Order class

// Ensure the user is logged in and a payment method is selected
if (!isset($_SESSION['user_id']) || empty($_POST['payment_method'])) {
    $_SESSION['error'] = 'No payment method selected or user not logged in.';
    header("Location: ../public/checkout.php");
    exit;
}

$userId = $_SESSION['user_id'];
$paymentId = $_POST['payment_method'];
$totalAmount = $_SESSION['total_amount']; // Assuming this is set somewhere in your session or calculated here

// Create an instance of the Order class
$order = new Order($pdo, $userId, date('Y-m-d H:i:s'), $totalAmount, $paymentId);

// Try to save the order to the database
if ($order->saveOrderToDatabase()) {
    // Assuming you have payment processing logic here or a method to finalize the payment
    // For example, interfacing with a payment gateway API (not implemented here)

    // After successful payment processing
    $_SESSION['message'] = "Payment successful! Order ID: " . $order->getIdOrder();
    header("Location: ../public/success_page.php"); // Redirect to a success page
} else {
    $_SESSION['error'] = "Failed to process payment. Please try again.";
    header("Location: ../public/checkout.php");
}
include'../template/footer.php';
exit;

