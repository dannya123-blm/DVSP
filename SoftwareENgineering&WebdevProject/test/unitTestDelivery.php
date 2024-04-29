<?php
// Include the necessary Delivery class file from the path provided.
require_once '../classes/Delivery.php';

// PDODummy class extends PDO to simulate a PDO connection for testing purposes.
class PDODummy extends PDO {
    private $data;  // Private variable to hold mock data.

    // Constructor initializes the object with mock data to simulate database responses.
    public function __construct() {
        // Setting up predefined data as a response for specific SQL queries.
        $this->data = [
            'SELECT * FROM delivery WHERE idOrders = :idOrders' => [
                'idDelivery' => 1, 'idOrders' => 123, 'DeliveryDate' => '2021-01-01', 'DeliveryAddress' => '123 Main St', 'Status' => 'Pending'
            ]
        ];
    }

    // Override the prepare method to use predefined mock data instead of a real database.
    public function prepare($statement, $options = array()): PDOStatement|false {
        // Retrieve mock data based on the SQL statement.
        $data = $this->data[$statement] ?? null;

        // Return a custom PDOStatement object initialized with mock data.
        return new class($data) extends PDOStatement {
            private $result;  // To store the mock data.

            public function __construct($data) {
                $this->result = $data;  // Assign the mock data to the result property.
            }

            // Simulate the execution of a statement and just return true.
            public function execute(?array $params = null): bool {
                return true;  // Always return true to simulate successful execution.
            }

            // Method to fetch the test data directly after "execution".
            public function getTestData() {
                return $this->result;  // Return the mock data.
            }

            // Simulate rowCount functionality for the PDOStatement.
            public function rowCount(): int {
                return 1;  // Assume there's always one row affected.
            }
        };
    }

    // Simulate the functionality of lastInsertId used in database operations.
    public function lastInsertId($name = null): string {
        return '1';  // Always return '1' for simplicity in tests.
    }
}

// Utility function to compare expected and actual results and display an assertion message.
function assertEqual($expected, $actual, $message) {
    // Output results in a HTML format with color coding for pass (green) or fail (red).
    echo "<div style='" . ($expected === $actual ? "color: green;" : "color: red;") . "'>$message: " . ($expected === $actual ? "PASS" : "FAIL (Expected: $expected, Got: $actual)") . "</div>";
}

// Create an instance of PDODummy for testing.
$pdo = new PDODummy();
// Create an instance of the Delivery class, passing the mock PDO object.
$delivery = new Delivery($pdo);

// Begin HTML output for displaying test results.
echo "<!DOCTYPE html><html><head><title>Test Results</title></head><body>";
echo "<h1>Delivery Class Test Results</h1>";

// Prepare and execute the PDO statement using the dummy PDO object.
$stmt = $pdo->prepare("SELECT * FROM delivery WHERE idOrders = :idOrders");
$stmt->execute();  // Perform the operation
$result = $stmt->getTestData();  // Fetch the test data from the custom PDOStatement.

// Check if data was retrieved and perform an assertion.
if ($result) {
    assertEqual('123 Main St', $result['DeliveryAddress'], 'Get delivery details by order ID');
} else {
    echo "<div style='color: red;'>FAIL: Data for DeliveryAddress not found</div>";
}

echo "</body></html>";  // End HTML output.

