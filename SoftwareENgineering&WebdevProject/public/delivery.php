<?php
include '../template/header.php';
include '../src/dbconnect.php';
include '../classes/Delivery.php';

try {
    // Initialize the PDO connection first
    $pdo = new PDO('mysql:host=localhost;dbname=dvsdb', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Now instantiate the Delivery object with the newly created PDO object
    $delivery = new Delivery($pdo);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$orderId = isset($_GET['idOrders']) ? $_GET['idOrders'] : null;

if (!$orderId) {
    echo "No order ID provided.";
    print_r($_GET); // This will show all GET parameters received
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM delivery WHERE idOrders = :idOrders");
    $stmt->bindParam(':idOrders', $orderId);
    $stmt->execute();
    $deliveryData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($deliveryData) {
        // Reusing the already created $delivery object to set the fetched data
        $delivery->setDeliveryID($deliveryData['idDelivery']);
        $delivery->setOrderID($deliveryData['idOrders']);
        $delivery->setDeliveryDate($deliveryData['DeliveryDate']);
        $delivery->setDeliveryAddress($deliveryData['DeliveryAddress']);
        $delivery->setStatus($deliveryData['Status']);
    } else {
        $delivery = null;
        echo "<p>Delivery details not found for Order ID: " . htmlspecialchars($orderId) . "</p>";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Sleep for 20 seconds
sleep(20);

// Update the status to "complete" after the delay
if ($delivery) {
    try {
        $stmt = $pdo->prepare("UPDATE delivery SET Status = 'complete' WHERE idOrders = :idOrders");
        $stmt->bindParam(':idOrders', $orderId);
        $stmt->execute();

        // Update the delivery object as well
        $delivery->setStatus('complete');
    } catch(PDOException $e) {
        echo "Error updating status: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Details</title>
    <link rel="stylesheet" href="../css/Delivery.css">
</head>
<body>

<div class="container">
    <?php if ($delivery): ?>
        <h2>Delivery Details</h2>
        <!-- Remove or comment out the line below to stop showing the Delivery ID -->
        <!-- <p><strong>Delivery ID:</strong> <?= htmlspecialchars($delivery->getDeliveryID()) ?></p> -->
        <p><strong>Order ID:</strong> <?= htmlspecialchars($delivery->getOrderID()) ?></p>
        <p><strong>Delivery Date:</strong> <?= htmlspecialchars($delivery->getDeliveryDate()) ?></p>
        <p><strong>Delivery Address:</strong> <?= htmlspecialchars($delivery->getDeliveryAddress()) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($delivery->getStatus()) ?></p>
    <?php else: ?>
        <p>Delivery details not found.</p>
    <?php endif; ?>
</div>

<?php include '../template/footer.php'; ?>
</body>
</html>
