<?php
include '../template/header.php';
include '../src/dbconnect.php';
include '../classes/Order.php';
include '../classes/Products.php';

// Check if the checkout form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    // Get order details from the session/cart
    $idCustomer = $_SESSION['user_id']; // Assuming you have a way to retrieve the customer ID
    $idAdmin = 1; // Assuming admin ID (you may adjust this accordingly)
    $orderDate = date('Y-m-d H:i:s'); // Current date and time
    $totalAmount = calculateTotalAmount(); // Function to calculate total amount from cart

    // Create a new Order object with required arguments
    $order = new Order($idCustomer, $idAdmin, $orderDate, $totalAmount);

    if ($orderSaved) {
        // Order saved successfully
        unset($_SESSION['cart']); // Clear the cart after placing the order
        echo '<div class="success-message">Order placed successfully!</div>';
    } else {
        // Error saving order
        echo '<div class="error-message">Failed to place order. Please try again.</div>';
    }
}

// Function to calculate total amount from cart
function calculateTotalAmount() {
    global $pdo;
    $totalAmount = 0;

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Initialize Products class with database connection
        $productObj = new Products($pdo); // Assuming getConnection() gets the PDO connection

        foreach ($_SESSION['cart'] as $productId) {
            $product = $productObj->getProductById($productId);
            if ($product) {
                $totalAmount += $product->getPrice();
            }
        }
    }

    return $totalAmount;
}
?>

<link rel="stylesheet" href="../css/checkout.css">

<main class="container">
    <h2>Checkout</h2>
    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) : ?>
        <div class="order-summary">
            <h3>Order Summary</h3>
            <ul>
                <?php
                $productObj = new Products(getConnection());
                foreach ($_SESSION['cart'] as $productId) {
                    $product = $productObj->getProductById($productId);
                    if ($product) {
                        echo '<li>' . $product->getName() . ' - €' . $product->getPrice() . '</li>';
                    }
                }
                ?>
            </ul>
            <p>Total Amount: €<?php echo calculateTotalAmount(); ?></p>
            <form method="post">
                <button type="submit" name="checkout" class="checkout-btn">Place Order</button>
            </form>
        </div>
    <?php else : ?>
        <p>Your cart is empty. Please add items to your cart before proceeding to checkout.</p>
    <?php endif; ?>
</main>

<?php
require_once '../template/footer.php';
?>
