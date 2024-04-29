<?php
include '../template/header.php';
require '../src/dbconnect.php';
require '../classes/Delivery.php';


global $pdo;
$delivery = new Delivery($pdo);
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo "User is not logged in.";
    exit;
}

$deliveries = $delivery->getDeliveriesByUser($userId);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Status</title>
    <link rel="stylesheet" href="../css/status.css">
</head>
<body>
<div class="container">
    <h1>Your Delivery Status</h1>
    <?php if (empty($deliveries)): ?>
        <p>You have no deliveries.</p>
    <?php else: ?>
        <div class="delivery-status-container">
            <?php foreach ($deliveries as $deliveryDetail): ?>
                <div class="delivery-status-item">
                    <div class="delivery-detail">
                        <strong>Order ID:</strong> <?= htmlspecialchars($deliveryDetail['idOrders']) ?>
                    </div>
                    <div class="delivery-detail">
                        <strong>Status:</strong> <?= htmlspecialchars($deliveryDetail['Status']) ?>
                    </div>
                    <div class="delivery-detail">
                        <strong>Delivery Address:</strong> <?= htmlspecialchars($deliveryDetail['DeliveryAddress']) ?>
                    </div>
                    <div class="delivery-detail">
                        <strong>Delivery Date:</strong> <?= date('d M Y', strtotime($deliveryDetail['DeliveryDate'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
