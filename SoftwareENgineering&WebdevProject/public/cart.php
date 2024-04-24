<?php
global $pdo;
require '../template/header.php';
include '../src/dbconnect.php';
require_once '../classes/Products.php';
$productObj = new Products($pdo);

if (isset($_POST['remove_from_cart']) && isset($_POST['remove_product_id'])) {
$productObj->removeFromCart($_POST['remove_product_id']);
// Refresh the page to reflect changes in the UI
header('Location: cart.php');
exit;
}
// Check if the cart session variable is set and not empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
$cartItems = [];
$subtotal = 0;

foreach ($_SESSION['cart'] as $productId) {
$product = $productObj->getProductById($productId);
if ($product) {
if (array_key_exists($productId, $cartItems)) {
$cartItems[$productId]['quantity']++;
} else {
$cartItems[$productId] = [
'name' => $product->getName(),
'price' => $product->getPrice(),
'quantity' => 1
];
}
$subtotal += $product->getPrice();
}
}
?>
    <link rel="stylesheet" href="../css/cart.css">
<?php
echo '<div class="product-container"><h2>Cart Items</h2><ul class="cart-items">';
        foreach ($cartItems as $productId => $item) {
        echo '<li class="cart-item"><div class="cart-item-details">';
                echo '<h3 class="cart-item-name">' . $item['name'] . '</h3>';
                echo '<p class="cart-item-price">Price: €' . $item['price'] . '</p>';
                echo '<p class="cart-item-quantity">Quantity: ' . $item['quantity'] . '</p>';
                echo '<p class="cart-item-total">Total: €' . ($item['price'] * $item['quantity']) . '</p>';
                echo '</div><form method="post"><input type="hidden" name="remove_product_id" value="' . $productId . '">';
                echo '<button type="submit" name="remove_from_cart" class="remove-btn">Remove</button>';
                echo '</form></li>';
        }
        echo '<li class="subtotal">Subtotal: €' . $subtotal . '</li></ul></div>';
echo '<form action="checkout.php" method="post"><button type="submit" name="checkout" class="purchase-btn animated">Checkout</button></form>';
} else {
echo '<div class="empty-cart-message"><p>Your cart is currently empty.</p></div>';
}
require '../template/footer.php';
?>
