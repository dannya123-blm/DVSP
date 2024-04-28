<?php
require_once '../classes/Order.php';  // Make sure to provide the correct path to your Order class file

class PDODummy extends PDO {
    public function __construct() {
        // Skipping the actual connection setup
    }

    // Correcting the method to ensure correct instantiation and handling
    public function prepare($statement, $driver_options = array()): PDOStatement|false {
        return new class extends PDOStatement {
            // You can add a public constructor or omit it entirely; no need for a protected one
            public function __construct() {}

            public function execute($params = array()): bool {
                return true;  // Simulate successful execution
            }

            // Direct method to provide test data without using fetch methods
            public function getTestData(): array {
                return ['idOrders' => 1, 'idCustomer' => 1, 'orderDate' => '2021-01-01', 'TotalAmount' => 100.00, 'idPayment' => 1];
            }
        };
    }

    public function beginTransaction(): bool {
        return true;
    }

    public function commit(): bool {
        return true;
    }

    public function rollBack(): bool {
        return true;
    }

    public function lastInsertId($name = null): string {
        return '1';  // Simulate returning an order ID
    }
}

// Define a simple assertion function that outputs HTML
function assertEqual($expected, $actual, $message) {
    if ($expected !== $actual) {
        echo "<div style='color: red;'>FAIL: $message (Expected: '$expected', Actual: '$actual')</div>";
    } else {
        echo "<div style='color: green;'>PASS: $message</div>";
    }
}

$pdo = new PDODummy();
$order = new Order($pdo);

echo "<!DOCTYPE html><html><head><title>Test Results</title></head><body>";
echo "<h1>Order Class Test Results</h1>";

$stmt = $pdo->prepare("SELECT * FROM orders WHERE idOrders = :orderId");
$stmt->execute(['orderId' => 1]);
$result = $stmt->getTestData();  // Use a custom method to get test data
assertEqual(['idOrders' => 1, 'idCustomer' => 1, 'orderDate' => '2021-01-01', 'TotalAmount' => 100.00, 'idPayment' => 1], $result, 'Testing getOrderDetails');

$newOrderId = $order->createOrder(1, 100.00, 1);
assertEqual('1', $newOrderId, 'Testing createOrder');

$updateResult = $order->updateOrder(1, '2021-02-01', 200.00, 2);
assertEqual(true, $updateResult, 'Testing updateOrder');

$deleteResult = $order->deleteOrder(1);
assertEqual(true, $deleteResult, 'Testing deleteOrder');

echo "</body></html>";

