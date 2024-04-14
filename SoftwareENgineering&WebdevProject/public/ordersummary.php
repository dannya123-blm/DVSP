<?php
// Assuming you include your database connection and autoload your classes
include '../src/dbconnect.php';
include '../classes/Order.php';
include '../classes/OrderSummary.php';
include '../classes/User.php';
include '../template/header.php';

$pdo = new PDO($dsn, $username, $password, $options);

// Simulating fetching an order ID, e.g., from a GET request
$orderId = $_GET['orderId'];

// Initialize the Order object
$order = new Order($pdo);
$orderDetails = $order->getOrderDetails($orderId);

// Initialize the OrderSummary object
$orderSummary = new OrderSummary();
$orderSummary->loadFromDatabase($pdo, $orderId);

// Assume we get user ID from the order details - adjust according to your application logic
$userId = $orderDetails['idCustomer'];

// Initialize the User object
$user = new User($pdo);
$user->setUserID($userId); // Assuming a method to set the ID if not set in the constructor

// Fetch User Details - assuming address is part of user details
// Here you need to add method in User class to fetch details or just address
$userAddress = $user->getAddress();

// Calculate delivery date
$deliveryDate = date('d M Y', strtotime($order->getOrderDate() . ' + 2 weeks'));

// Generate Tracking Code
$trackingCode = 'DVS' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 9);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Summary</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Order Summary</h1>
<p><strong>Order ID:</strong> <?php echo $orderId; ?></p>
<p><strong>Total Amount:</strong> $<?php echo $orderSummary->getSubtotal(); ?></p>
<p><strong>Delivery Address:</strong> <?php echo $userAddress; ?></p>
<p><strong>Delivery Date:</strong> <?php echo $deliveryDate; ?></p>
<p><strong>Tracking Code:</strong> <?php echo $trackingCode; ?></p>
</body>
</html>
<?php
include '../template/footer.php';
?>