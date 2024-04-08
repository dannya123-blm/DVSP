<?php
// Start the session
session_start();

// Check if product ID is provided
if(isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    // Remove the item from the cart session variable
    unset($_SESSION['cart'][$productId]);

    // Return success response
    echo "Item removed from cart successfully.";
} else {
    // Return error response
    echo "Error: Product ID not provided.";
}
?>
