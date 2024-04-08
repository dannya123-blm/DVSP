<?php
// Start the session
session_start();

// Include header
require_once "header.php";

// Define the function to retrieve product details from the database
function getProductFromDatabase($productId) {
}
?>
<main class="container">
    <h1>Order Confirmation</h1>
    <p>Your order has been successfully placed!</p>
    <p>Delivery Date: <?php echo date('Y-m-d'); ?></p>
    <h2>Items Ordered</h2>
    <ul>
        <?php
        // Check if cart is not empty
        if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            // Loop through each item in the cart
            foreach($_SESSION['cart'] as $productId => $quantity) {
                // Retrieve product details from the database
                $productName = getProductFromDatabase($productId);
                echo "<li>$productName</li>";
            }
        } else {
            echo "<li>No items in the cart</li>";
        }
        ?>
    </ul>
    <a href="index.php" class="btn btn-primary">Back to Homepage</a>
</main>

<?php
// Include footer
require_once "footer.php";
?>
