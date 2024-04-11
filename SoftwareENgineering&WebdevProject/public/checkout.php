<?php
// Include necessary files
include '../template/header.php';
include '../src/dborder.php';
include '../classes/Order.php';
include '../classes/Products.php';

// Function to calculate total amount based on cart contents
function calculateTotalAmount($pdo) {
    $totalAmount = 0;

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $productObj = new Products($pdo);

        foreach ($_SESSION['cart'] as $productId) {
            $product = $productObj->getProductById($productId);
            if ($product) {
                $totalAmount += $product->getPrice();
            }
        }
    }

    return $totalAmount;
}

// Main processing when the checkout form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    $idCustomer = $_SESSION['user_id'];
    $idAdmin = 1; // Example admin ID
    $orderDate = date('Y-m-d H:i:s');
    $pdo = getConnection(); // Get database connection

    // Calculate total amount
    $totalAmount = calculateTotalAmount($pdo);

    $idPayment = 1; // Example payment ID

    $order = new Order($idCustomer, $idAdmin, $orderDate, $totalAmount, $idPayment);

    try {
        $orderSaved = $order->saveOrderToDatabase($pdo);

        if ($orderSaved) {
            $orderId = $pdo->lastInsertId(); // Get auto-generated order ID
            unset($_SESSION['cart']); // Clear the cart after placing the order
            echo '<div class="success-message">Order placed successfully!</div>';
            echo '<p>Order ID: ' . $orderId . '</p>';
        } else {
            echo '<div class="error-message">Failed to place order. Please try again.</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="error-message">Error saving order: ' . $e->getMessage() . '</div>';
    }
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
                $pdo = getConnection(); // Get database connection
                $productObj = new Products($pdo);

                foreach ($_SESSION['cart'] as $productId) {
                    $product = $productObj->getProductById($productId);
                    if ($product) {
                        echo '<li>' . $product->getName() . ' - €' . $product->getPrice() . '</li>';
                    }
                }
                ?>
            </ul>
            <p>Total Amount: €<?php echo calculateTotalAmount($pdo); ?></p>
            <form method="post" id="checkoutForm" onsubmit="submitCheckout(event)">
                <button type="submit" name="checkout" class="checkout-btn">Place Order</button>
            </form>
        </div>
    <?php else : ?>
        <p>Your cart is empty. Please add items to your cart before proceeding to checkout.</p>
    <?php endif; ?>
</main>

<script>
    function submitCheckout(event) {
        event.preventDefault(); // Prevent default form submission

        // Create AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../src/process_order.php', true); // Adjust URL to your processing script

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 400) {
                // Success response received
                var response = xhr.responseText;

                // Display the response (order ID)
                document.getElementById('checkoutResponse').innerHTML = response;
            } else {
                // Error handling
                console.error('Request failed with status:', xhr.status);
            }
        };

        // Send the form data
        xhr.send(new FormData(event.target));
    }
</script>

<?php
include '../template/footer.php';
?>
