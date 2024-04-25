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
        // Make sure that 'DeliveryDate' is the correct column name in your SQL database
        $deliveryDate = isset($deliveryDetail['DeliveryDate']) ? date('Y M d', strtotime($deliveryDetail['DeliveryDate'])) : "Date not available";
        echo "<p>Delivery ID: {$deliveryDetail['idDelivery']} - Status: {$deliveryDetail['Status']} - Delivery Date: {$deliveryDate}</p>";
        echo date('d M Y H:i:s', strtotime($setDeliveryData['DeliveryDate']));

    }
}
include '../template/footer.php';
?>
