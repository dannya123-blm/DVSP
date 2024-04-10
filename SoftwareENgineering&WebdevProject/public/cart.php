<?php
// Start the session
global $pdo;

// Include necessary files and configurations
require '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../classes/Products.php';

// Initialize Products class with database connection
$productObj = new Products($pdo);

// Check if the cart session variable is set and not empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Array to store cart items and their quantities
    $cartItems = [];
    // Total price of all items in the cart
    $subtotal = 0;

    // Loop through each product ID in the cart
    foreach ($_SESSION['cart'] as $productId) {
        // Fetch product details from the database
        $product = $productObj->getProductById($productId);
        if ($product) {
            // Check if the product is already in the cart
            if (array_key_exists($productId, $cartItems)) {
                // Increment the quantity if the product is already in the cart
                $cartItems[$productId]['quantity']++;
            } else {
                // Add the product to the cart with quantity 1
                $cartItems[$productId] = [
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'quantity' => 1
                ];
            }
            // Update the subtotal
            $subtotal += $product->getPrice();
        }
    }

    // Display cart items
    echo '<div id="cart-items">';
    echo '<h2>Cart Items</h2>';
    echo '<ul>';
    foreach ($cartItems as $productId => $item) {
        echo '<li>' . $item['name'] . ' - Quantity: ' . $item['quantity'] . ' - Total: €' . ($item['price'] * $item['quantity']) . '</li>';
    }
    echo '</ul>';
    // Display subtotal
    echo '<p>Subtotal: €' . $subtotal . '</p>';
    echo '</div>';

    // Display "Clear Basket" button
    echo '<form action="" method="post">';
    echo '<button type="submit" name="clear_basket">Clear Basket</button>';
    echo '</form>';

    // Display "Purchase" button
    echo '<button type="button" onclick="purchase()">Purchase</button>';
} else {
    // If the cart is empty, display a message
    echo '<p>Your cart is empty</p>';
}

require '../template/footer.php';

?>
