
<?php
// This code is based on the tutorial from SymfonyCasts & W3Schools:
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
if (isset($_POST['check_status'])) {
    $idOrders = $_POST['idOrders'];
    $delivery->checkAndUpdateStatus($idOrders);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['cancel'])) {
        $delivery->updateDeliveryStatus($_POST['idOrders'], 'Cancelled');
    } elseif (isset($_POST['check_status'])) {
        $delivery->checkAndUpdateStatus($_POST['idOrders']);
    }
}

$deliveries = $delivery->getDeliveriesByUser($userId);
try {
    if (isset($_POST['check_status'])) {
        $idOrders = $_POST['idOrders'];
        $delivery->checkAndUpdateStatus($idOrders);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Status</title>
    <link rel="stylesheet" href="../css/Delivery.css">
</head>
<body>
<div class="delivery-container">
    <h1>Your Delivery Status</h1>
    <?php if (empty($deliveries)): ?>
        <p>You have no deliveries.</p>
    <?php else: ?>
        <?php
        $sections = ['Pending' => [], 'Cancelled' => [], 'Delivered' => []];
        foreach ($deliveries as $deliveryDetail) {
            $sections[$deliveryDetail['Status']][] = $deliveryDetail;
        }
        ?>
        <?php foreach ($sections as $status => $items): ?>
            <?php if (!empty($items)): ?>
                <div class="delivery-section">
                    <h2 class="delivery-section-title"><?= $status ?> Orders</h2>
                    <?php foreach ($items as $item): ?>
                        <div class="delivery-item">
                            <div class="order-date"><strong>Order ID:</strong> <?= htmlspecialchars($item['idOrders']) ?></div>
                            <div class="order-total"><strong>Status:</strong> <?= htmlspecialchars($item['Status']) ?></div>
                            <div class="dispatch-to"><strong>Delivery Address:</strong> <?= htmlspecialchars($item['DeliveryAddress']) ?></div>
                            <div class="order-status"><strong>Delivery Date:</strong> <?= date('d M Y', strtotime($item['DeliveryDate'])) ?></div>
                            <form method="post">
                                <input type="hidden" name="idOrders" value="<?= $item['idOrders'] ?>">
                                <?php if ($item['Status'] === 'Pending'): ?>
                                    <button type="submit" name="cancel" class="btn-view-item">Cancel Order</button><br>
                                <?php endif; ?>
                              <br>  <button type="submit" name="check_status" class="btn-view-item">Check Status</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
