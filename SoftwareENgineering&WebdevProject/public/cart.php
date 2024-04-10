<?php
session_start(); // Start session to access session variables

// Check if the product is added to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    // Store the product ID in the session
    $_SESSION['cart'][] = $_POST['product_id'];
}

// Clear the cart if the "Clear Basket" button is pressed
if (isset($_POST['clear_basket'])) {
    unset($_SESSION['cart']); // Clear the cart session variable
}

require '../template/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DVS Expansion HomePage</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/home.js"></script>
</head>
<body>
<main>
    <section class="categories left-to-right-categories">
        <!-- Category HTML here -->
    </section>
    <br>
    <br>
    <!-- Cart Display -->
    <div id="cart-items">
        <h2>Cart Items</h2>
        <?php
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            // Initialize Products class with database connection
            global $pdo;
            require_once '../src/dbconnect.php';
            require_once '../classes/Products.php';
            $productObj = new Products($pdo);

            // Associative array to store cart items and their quantities
            $cartItems = [];

            // Loop through each product ID in the cart
            foreach ($_SESSION['cart'] as $productId) {
                // If the product is already in the cart, increment its quantity
                if (isset($cartItems[$productId])) {
                    $cartItems[$productId]['quantity']++;
                } else {
                    // Fetch product details from the database
                    $product = $productObj->getProductById($productId);
                    if ($product) {
                        // Add product details to the cart items array
                        $cartItems[$productId] = [
                            'product' => $product,
                            'quantity' => 1
                        ];
                    }
                }
            }

            // Display cart items and their quantities
            foreach ($cartItems as $productId => $cartItem) {
                echo '<li>' . $cartItem['product']->getName() . ' - Quantity: ' . $cartItem['quantity'] . ' - €' . ($cartItem['product']->getPrice() * $cartItem['quantity']) . '</li>';
            }

            // Calculate subtotal
            $subtotal = array_reduce($cartItems, function ($carry, $item) {
                return $carry + ($item['product']->getPrice() * $item['quantity']);
            }, 0);

            // Display subtotal
            echo '<p>Subtotal: €' . $subtotal . '</p>';

            // Display "Clear Basket" button
            echo '<form action="" method="post">';
            echo '<button type="submit" name="clear_basket">Clear Basket</button>';
            echo '</form>';

            // Display "Purchase" button
            echo '<button type="button" onclick="purchase()">Purchase</button>';
        } else {
            echo '<p>Your cart is empty</p>';
        }
        ?>
    </div>
</main>

<script>
    function purchase() {
        // Perform purchase actions, such as redirecting to a checkout page
        // For demonstration, let's redirect to a checkout.php page
        window.location.href = "checkout.php";
    }
</script>

</body>
</html>
<?php
require '../template/footer.php';
?>
