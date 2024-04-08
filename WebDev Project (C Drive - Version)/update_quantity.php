<?php
// Start the session
session_start();

// Check if product ID and quantity are provided
if(isset($_GET['product_id']) && isset($_GET['quantity'])) {
    $productId = $_GET['product_id'];
    $quantity = $_GET['quantity'];

    // Update the quantity in the cart session variable
    $_SESSION['cart'][$productId] = $quantity;

    // Return success response
    echo "Quantity updated successfully.";
} else {
    // Return error response
    echo "Error: Product ID or quantity not provided.";
}
?>
