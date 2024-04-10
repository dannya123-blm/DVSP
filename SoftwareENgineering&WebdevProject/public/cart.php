<?php

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
    <script src="../js/cart.js"></script>
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

            // Array to store cart items and subtotal
            $cartItems = [];
            $subtotal = 0;

            // Loop through each product ID in the cart
            foreach ($_SESSION['cart'] as $productId) {
                // Fetch product details from the database
                $product = $productObj->getProductById($productId);
                if ($product) {
                    // Add product details to the cart items array
                    $cartItems[] = $product;
                    $subtotal += $product->getPrice();

                    // Display the product in the cart
                    echo '<li>' . $product->getName() . ' - €' . $product->getPrice() . '</li>';
                }
            }

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
