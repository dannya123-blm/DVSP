<?php
include '../template/header.php';
include '../src/dbconnect.php';
require '../classes/Customer.php';
require '../classes/Payment.php';
require '../classes/Products.php'; // Include the Products class file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

// Initialize Customer and Payment classes with database connection
try {
    $customer = new Customer($pdo);
    $payment = new Payment(); // Assuming you instantiate Payment class for current user

    $userId = $_SESSION['user_id'];
    $userData = $customer->getUserDataById($userId);

    if (!$userData) {
        echo "User not found.";
        exit();
    }

    // Display user information
    echo '<div class="user-details">';
    echo '<h2>User Information</h2>';
    echo '<p><strong>Username:</strong> ' . htmlspecialchars($userData['Username']) . '</p>';
    echo '<p><strong>Email:</strong> ' . htmlspecialchars($userData['Email']) . '</p>';
    echo '<p><strong>Address:</strong> ' . htmlspecialchars($userData['Address']) . '</p>';
    echo '<p><strong>Mobile Number:</strong> ' . htmlspecialchars($userData['MobileNumber']) . '</p>';
    echo '<a href="../public/dashboard.php" class="edit-btn">Edit User Info</a>';
    echo '</div>';

    // Display cart items
    echo '<div class="cart-container">';
    echo '<h2>Cart Items</h2>';
    echo '<ul class="cart-items">';
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $products = new Products($pdo); // Instantiate Products class with PDO
        foreach ($_SESSION['cart'] as $productId) {
            // Fetch product details from the database (use your implementation)
            $productDetails = $products->getProductById($productId); // Get product details
            if ($productDetails) {
                echo '<li class="cart-item">';
                echo '<p><strong>' . htmlspecialchars($productDetails->getName()) . '</strong></p>'; // Access properties using getters
                echo '<p>Price: €' . htmlspecialchars($productDetails->getPrice()) . '</p>'; // Access properties using getters
                echo '</li>';
            }
        }
    } else {
        echo '<p>Your cart is empty.</p>';
    }
    echo '</ul>';
    echo '</div>';

    // Display payment information
    echo '<div class="payment-info">';
    echo '<h2>Payment Details</h2>';
    if (isset($userData['PaymentMethod'])) {
        echo '<p><strong>Payment Method:</strong> ' . htmlspecialchars($userData['PaymentMethod']) . '</p>';
        echo '<p><strong>Card Number:</strong> ' . htmlspecialchars($userData['PaymentNumber']) . '</p>';
        echo '<p><strong>Expires:</strong> ' . htmlspecialchars($userData['PaymentExpire']) . '</p>';
        echo '<a href="../public/payment.php" class="edit-btn">Edit Payment Info</a>';
    } else {
        echo '<p>No payment method added.</p>';
        echo '<a href="../public/payment.php" class="add-btn">Add Payment Method</a>';
    }
    echo '</div>';

    // Process checkout
    if (isset($_POST['checkout'])) {
        // Perform payment processing (assuming you have methods in Customer class)
        $paymentMethod = $userData['PaymentMethod']; // Example: 'Credit Card'
        $payment->setPaymentMethod($paymentMethod);
        $payment->setPaymentName($userData['PaymentName']);
        $payment->setPaymentNumber($userData['PaymentNumber']);
        $payment->setPaymentCCV($userData['PaymentCCV']);

        // Add the payment details to the database (using Customer class methods)
        $customer->addPaymentDetails($payment);

        // Proceed to order summary page
        header("Location: order_summary.php");
        exit();
    }

    // Checkout button
    echo '<form action="checkout.php" method="post">';
    echo '<button type="submit" name="checkout" class="checkout-btn">Proceed to Checkout</button>';
    echo '</form>';

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

include '../template/footer.php';
?>
