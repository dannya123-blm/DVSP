<?php
global $pdo;
require '../src/dbconnect.php'; // Assuming this returns a PDO instance $pdo
require '../classes/Delivery.php';
include '../template/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit;
}

$delivery = new Delivery($pdo);
$userId = $_SESSION['user_id'];
$deliveries = $delivery->getDeliveriesByUser($userId);

if (empty($deliveries)) {
    echo "<p>No delivery made.</p>";
} else {
    echo "<h2>Your Deliveries</h2>";
    foreach ($deliveries as $deliveryDetail) {
        // Format the DeliveryDate with date and time
        $formattedDate = isset($deliveryDetail['DeliveryDate']) ? date('d M Y H:i:s', strtotime($deliveryDetail['DeliveryDate'])) : "Date not available";
        echo "<p>Delivery ID: {$deliveryDetail['idDelivery']} - Status: {$deliveryDetail['Status']} - Delivery Date: {$formattedDate}</p>";
    }
}
include '../template/footer.php';
?>
