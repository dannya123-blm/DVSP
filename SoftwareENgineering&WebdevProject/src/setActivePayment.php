<?php
require 'dbconnect.php';
require '../classes/Payment.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the payment ID is provided
    if (isset($_POST['payment_id'])) {
        // Sanitize and validate the payment ID
        $paymentId = filter_var($_POST['payment_id'], FILTER_VALIDATE_INT);
        if ($paymentId === false) {
            // Invalid payment ID, handle the error (redirect, display error message, etc.)
            // For example:
            header("Location: ../public/checkout.php?error=InvalidPaymentId");
            exit;
        }

        // Perform additional validation if necessary (e.g., check if the payment method belongs to the current user)
        // ...

        // Assuming you have a function to update the active payment method in your Payment class
        $payment = new Payment($pdo);
        // $payment->setActivePayment($userId, $paymentId);

        // Redirect back to the checkout page or any other appropriate page after setting the active payment method
        header("Location: ../public/checkout.php");
        exit;
    } else {
        // Payment ID not provided, handle the error (redirect, display error message, etc.)
        // For example:
        header("Location: ../public/checkout.php?error=MissingPaymentId");
        exit;
    }
} else {
    // If the form is not submitted via POST method, handle the error (redirect, display error message, etc.)
    // For example:
    header("Location: ../public/checkout.php?error=InvalidRequestMethod");
    exit;
}
?>
