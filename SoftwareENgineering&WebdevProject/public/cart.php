<?php
// Include necessary files and configurations
global $pdo;
require '../template/header.php';
include '../src/dbconnect.php';
require_once '../classes/Products.php';

// Initialize Products class with database connection
try {
    $productObj = new Products($pdo); // Assuming $pdo is your PDO instance
} catch (PDOException $e) {
    // Handle any potential PDO connection errors
    echo "Database connection failed: " . $e->getMessage();
    exit(); // Terminate script execution
}

// Function to remove a product from the cart and increment stock quantity
function removeFromCart($productId, $pdo) {
    // Check if the product ID exists in the cart session
    if (isset($_SESSION['cart']) && in_array($productId, $_SESSION['cart'])) {
        // Find the index of the product in the cart session
        $index = array_search($productId, $_SESSION['cart']);
        // Remove the product from the cart session
        unset($_SESSION['cart'][$index]);
        // Reindex the cart session array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        // Increment the stock quantity in the database
        $updateStockStmt = $pdo->prepare("UPDATE products SET StockQuantity = StockQuantity + 1 WHERE idProducts = :product_id");
        $updateStockStmt->bindParam(':product_id', $productId);
        $updateStockStmt->execute();
    }
}

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
        // Add form with hidden input field for product ID to submit
        echo '<form method="post">';
        echo '<input type="hidden" name="remove_product_id" value="' . $productId . '">';
        echo '<button type="submit" name="remove_from_cart" class="remove-btn">Remove</button>';
        echo '</form>';
        echo '</li>';
    }
    // Display subtotal
    echo '<li class="subtotal">Subtotal: €' . $subtotal . '</li>';
    echo '</ul>';
    echo '</div>';

    // Checkout button
    echo '<form action="checkout.php" method="post">';
    echo '<button type="submit" name="checkout" class="purchase-btn animated">Checkout</button>';
    echo '</form>';
} else {
    // Display message indicating that the cart is empty
    echo '<div class="empty-cart-message">';
    echo '<p>Your cart is currently empty.</p>';
    echo '</div>';
}

// Check if the "Remove" button is clicked
if (isset($_POST['remove_from_cart']) && isset($_POST['remove_product_id'])) {
    // Call the removeFromCart function to remove the product from the cart
    removeFromCart($_POST['remove_product_id'], $pdo);
    // Optionally, you can redirect the user to the cart page or refresh the current page
    // header('Location: cart.php');
    // exit;
}
?>

<?php
require '../template/footer.php';
?>
</body>
</html>
