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
    $totalAmount = 0;  // Renamed from $subtotal for consistency

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
            $totalAmount += $product->getPrice();
        }
    }
    $_SESSION['totalAmount'] = $totalAmount;  // Store the total amount in session for checkout
    ?>
    <link rel="stylesheet" href="../css/cart.css">
    <div class="product-container">
        <h2>Cart Items</h2>
        <ul class="cart-items">
            <?php foreach ($cartItems as $productId => $item): ?>
                <li class="cart-item">
                    <div class="cart-item-details">
                        <h3 class="cart-item-name"><?= htmlspecialchars($item['name']) ?></h3>
                        <p class="cart-item-price">Price: €<?= htmlspecialchars($item['price']) ?></p>
                        <p class="cart-item-quantity">Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                        <p class="cart-item-total">Total: €<?= htmlspecialchars($item['price'] * $item['quantity']) ?></p>
                    </div>
                    <form method="post">
                        <input type="hidden" name="remove_product_id" value="<?= $productId ?>">
                        <button type="submit" name="remove_from_cart" class="remove-btn">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
            <li class="subtotal">Subtotal: €<?= htmlspecialchars($totalAmount) ?></li>
        </ul>
    </div>
    <?php
    // Show checkout button only when there are items in the cart
    if (!empty($cartItems)) {
        ?>
        <form action="checkout.php" method="post">
            <button type="submit" name="checkout" class="purchase-btn animated">Checkout</button>
        </form>
        <?php
    }
} else {
    echo '<div class="empty-cart-message"><p>Your cart is currently empty.</p></div>';
}
require '../template/footer.php';
?>
