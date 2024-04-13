<?php
global $pdo;
include '../template/header.php';
require '../src/dbconnect.php';
require '../classes/Customer.php';
require '../classes/Payment.php';
require '../classes/Products.php';

$customer = new Customer($pdo);
$payment = new Payment($pdo);
$products = new Products($pdo);

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$userId) {
    header("Location: ../public/login.php");
    exit;
}

try {
    $userData = $customer->getUserDataById($userId);
    if (!$userData) {
        echo "User not found.";
        exit;
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
    exit;
}

// Display user information
echo '<div class="user-details">';
echo '<h2>User Information</h2>';
echo '<p><strong>Username:</strong> ' . htmlspecialchars($userData['Username']) . '</p>';
echo '<p><strong>Address:</strong> ' . htmlspecialchars($userData['Address']) . '</p>';
echo '<a href="../public/dashboard.php" class="edit-btn">Edit User Info</a>';
echo '</div>';

// Display cart items
echo '<div class="cart-container">';
echo '<h2>Cart Items</h2>';
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $productId) {
        $productDetails = $products->getProductById($productId);
        if ($productDetails) {
            echo '<p>' . htmlspecialchars($productDetails->getName()) . ' - $' . htmlspecialchars($productDetails->getPrice()) . '</p>';
        }
    }
} else {
    echo '<p>Your cart is empty.</p>';
}

echo '</div>';

// Payment details logic
echo '<div class="payment-info">';
if (!empty($userData['PaymentNumber'])) {
    echo '<p><strong>Card Number:</strong> ' . substr($userData['PaymentNumber'], -4) . ' (last 4 digits)</p>';
    echo '<p><strong>Expires:</strong> ' . htmlspecialchars($userData['PaymentExpire']) . '</p>';
    echo '<a href="../public/paymentedit.php" class="edit-btn">Edit Payment Info</a>';
} else {
    echo '<p>No payment method added.</p>';
    echo '<a href="../public/payment.php" class="add-btn">Add Payment Method</a>';
}
echo '</div>';

echo '<form action="../public/checkout.php" method="post">';
echo '<button type="submit" name="checkout" class="checkout-btn">Proceed to Checkout</button>';
echo '</form>';

include '../template/footer.php';
?>
