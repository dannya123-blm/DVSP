<?php
// Include your database connection and autoload your classes
include '../src/dbconnect.php';
include '../classes/Order.php';
include '../classes/OrderSummary.php';
include '../classes/User.php';
include '../template/header.php';

// Simulating fetching an order ID, e.g., from a GET request
$orderId = filter_input(INPUT_GET, 'orderId', FILTER_SANITIZE_NUMBER_INT);
if (!$orderId) {
    echo "Invalid order ID.";
    include '../template/footer.php';
    exit;
}

// Initialize the Order object
$order = new Order($pdo);
$orderDetails = $order->getOrderDetails($orderId);
if (!$orderDetails) {
    echo "Order not found.";
    include '../template/footer.php';
    exit;
}

// Initialize the OrderSummary object
$orderSummary = new OrderSummary();
$orderSummary->loadFromDatabase($pdo, $orderId);

// Fetch user ID from order details
$userId = $orderDetails['idCustomer'];

// Initialize the User object
$user = new User($pdo);
$user->setUserID($userId);

// Fetch User Details
$userAddress = $user->getAddress();
if (!$userAddress) {
    $userAddress = "Address not available";
}

// Calculate delivery date
$deliveryDate = date('d M Y', strtotime($orderDetails['orderDate'] . ' + 2 weeks'));

// Generate Tracking Code
$trackingCode = 'DVS' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 9);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Summary</title></head>
<body>
<h1>Order Summary</h1>
<p><strong>Order ID:</strong> <?php echo htmlspecialchars($orderId); ?></p>
<p><strong>Total Amount:</strong> $<?php echo htmlspecialchars($orderSummary->getSubtotal()); ?></p>
<p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($userAddress); ?></p>
<p><strong>Delivery Date:</strong> <?php echo $deliveryDate; ?></p>
<p><strong>Tracking Code:</strong> <?php echo $trackingCode; ?></p>
</body>
</html>
<?php
include '../template/footer.php';
?>
