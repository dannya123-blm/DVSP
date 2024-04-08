<?php
// Start the session
session_start();

// Check if product ID is provided
if(isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    // Add the product to the cart session variable
    $_SESSION['cart'][$productId] = 1;

    // Return success response
    echo "Product added to cart successfully.";
} else {
    // Return error response
    echo "Error: Product ID not provided.";
}
?>
