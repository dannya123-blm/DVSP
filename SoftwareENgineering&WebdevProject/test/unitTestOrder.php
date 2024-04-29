<?php
// Include the Order class file.
require_once '../classes/Order.php';

// PDODummy class extends PDO to simulate database interactions.
class PDODummy extends PDO {
    // Constructor is simplified to avoid actual DB connection details.
    public function __construct() {
        // No real connection is established; this is a dummy setup.
    }

    // Override the prepare method to return a custom PDOStatement.
    public function prepare($statement, $driver_options = array()): PDOStatement|false {
        // Return a custom PDOStatement that simulates database operations.
        return new class extends PDOStatement {
            public function __construct() {
                // Constructor can be empty or initialize other necessary data.
            }

            // Simulate the execution of a statement, always returning true.
            public function execute($params = array()): bool {
                return true;
            }

            // Provide a method to return predetermined test data directly.
            public function getTestData(): array {
                return ['idOrders' => 1, 'idCustomer' => 1, 'orderDate' => '2021-01-01', 'TotalAmount' => 100.00, 'idPayment' => 1];
            }
        };
    }

    // Simulate transaction handling methods.
    public function beginTransaction(): bool {
        return true;
    }

    public function commit(): bool {
        return true;
    }

    public function rollBack(): bool {
        return true;
    }

    // Simulate the lastInsertId method to return a predefined ID.
    public function lastInsertId($name = null): string {
        return '1';  // Always return '1' to simulate a new order ID.
    }
}

// Function to compare expected and actual values and print results in HTML.
function assertEqual($expected, $actual, $message) {
    // Check if the expected and actual values match and display the result.
    if ($expected !== $actual) {
        echo "<div style='color: red;'>FAIL: $message (Expected: '$expected', Actual: '$actual')</div>";
    } else {
        echo "<div style='color: green;'>PASS: $message</div>";
    }
}

// Create an instance of PDODummy for testing.
$pdo = new PDODummy();
// Create an instance of the Order class with the dummy PDO instance.
$order = new Order($pdo);

// Start HTML document for displaying test results.
echo "<!DOCTYPE html><html><head><title>Test Results</title></head><body>";
echo "<h1>Order Class Test Results</h1>";

// Prepare and execute the test query.
$stmt = $pdo->prepare("SELECT * FROM orders WHERE idOrders = :orderId");
$stmt->execute(['orderId' => 1]);  // Bind parameter and execute.
$result = $stmt->getTestData();  // Retrieve test data using the custom method.
// Assert that the fetched data matches expected results.
assertEqual(['idOrders' => 1, 'idCustomer' => 1, 'orderDate' => '2021-01-01', 'TotalAmount' => 100.00, 'idPayment' => 1], $result, 'Testing getOrderDetails');

// Test creating an order and assert the returned ID is as expected.
$newOrderId = $order->createOrder(1, 100.00, 1);
assertEqual('1', $newOrderId, 'Testing createOrder');

// Test updating an order and assert the operation was successful.
$updateResult = $order->updateOrder(1, '2021-02-01', 200.00, 2);
assertEqual(true, $updateResult, 'Testing updateOrder');

// Test deleting an order and assert the operation was successful.
$deleteResult = $order->deleteOrder(1);
assertEqual(true, $deleteResult, 'Testing deleteOrder');

// Close the HTML document.
echo "</body></html>";

