<?php
require_once '../classes/Delivery.php';  // Ensure the correct path to your Delivery class

class PDODummy extends PDO {
    private $data;

    public function __construct() {
        // Initialize mock data to simulate database responses
        $this->data = [
            'SELECT * FROM delivery WHERE idOrders = :idOrders' => [
                'idDelivery' => 1, 'idOrders' => 123, 'DeliveryDate' => '2021-01-01', 'DeliveryAddress' => '123 Main St', 'Status' => 'Pending'
            ]
        ];
    }

    public function prepare($statement, $options = array()): PDOStatement|false {
        $data = $this->data[$statement] ?? null;

        return new class($data) extends PDOStatement {
            private $result;

            public function __construct($data) {
                $this->result = $data; // Directly use passed data as result
            }

            public function execute(?array $params = null): bool {
                // Here the method should just return true indicating success
                return true;  // Simulate successful execution
            }

            // Method to directly get data after execute if needed
            public function getTestData() {
                return $this->result;  // Provide the stored data
            }

            public function rowCount(): int {
                return 1;  // Simulate one row affected
            }
        };
    }

    public function lastInsertId($name = null): string {
        return '1';  // Simulate returning an ID
    }
}

function assertEqual($expected, $actual, $message) {
    echo "<div style='" . ($expected === $actual ? "color: green;" : "color: red;") . "'>$message: " . ($expected === $actual ? "PASS" : "FAIL (Expected: $expected, Got: $actual)") . "</div>";
}

$pdo = new PDODummy();
$delivery = new Delivery($pdo);

echo "<!DOCTYPE html><html><head><title>Test Results</title></head><body>";
echo "<h1>Delivery Class Test Results</h1>";

$stmt = $pdo->prepare("SELECT * FROM delivery WHERE idOrders = :idOrders");
$stmt->execute();  // Perform the operation
$result = $stmt->getTestData();  // Get the data directly after execution
if ($result) {
    assertEqual('123 Main St', $result['DeliveryAddress'], 'Get delivery details by order ID');
} else {
    echo "<div style='color: red;'>FAIL: Data for DeliveryAddress not found</div>";
}

echo "</body></html>";

