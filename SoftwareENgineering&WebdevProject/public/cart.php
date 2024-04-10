<?php
// Start the session
global $pdo;

// Include necessary files and configurations
require '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../classes/Products.php';

// Initialize Products class with database connection
$productObj = new Products($pdo);

?>

<html>
<head>
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>

<?php
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
    echo '<div class="product-container">';
    echo '<h2>Cart Items</h2>';
    echo '<ul class="cart-items">';
    foreach ($cartItems as $productId => $item) {
        echo '<li class="cart-item">';
        echo '<div class="cart-item-details">';
        echo '<h3 class="cart-item-name">' . $item['name'] . '</h3>';
        echo '<p class="cart-item-price">Price: €' . $item['price'] . '</p>';
        echo '<p class="cart-item-quantity">Quantity: ' . $item['quantity'] . '</p>';
        echo '<p class="cart-item-total">Total: €' . ($item['price'] * $item['quantity']) . '</p>';
        echo '</div>';
        echo '<button class="remove-btn" data-product-id="' . $productId . '">Remove</button>';
        echo '</li>';
    }
    echo '</ul>';
    // Display subtotal
    echo '<p>Subtotal: €' . $subtotal . '</p>';
    echo '</div>';

    // Display "Purchase" button
    echo '<button type="button" onclick="purchase()">Purchase</button>';
} else {
    // If the cart is empty, display a message
    echo '<p>Your cart is empty</p>';
}
?>

</body>
</html>

<?php
require '../template/footer.php';
?>
