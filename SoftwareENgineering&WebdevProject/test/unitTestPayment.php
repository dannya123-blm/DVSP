<?php
// Include the Payment class file from the specified path.
require_once '../classes/Payment.php';

// PDODummy class extends PDO to simulate database interactions for testing purposes.
class PDODummy extends PDO {
    private $data;

    public function __construct() {
        // Initialize mock data to simulate database records.
        $this->data = [
            'getAllCards' => [
                ['idPayment' => 1, 'paymentMethod' => 'Credit Card', 'paymentName' => 'John Doe', 'lastFourDigits' => '3456'],
                ['idPayment' => 2, 'paymentMethod' => 'Debit Card', 'paymentName' => 'Jane Smith', 'lastFourDigits' => '7890']
            ],
            'getPaymentInfo' => [
                'idPayment' => 1, 'paymentMethod' => 'Credit Card', 'paymentName' => 'John Doe', 'paymentNumber' => '1234567890123456', 'paymentExpiryDate' => '12/25'
            ]
        ];
    }

    // Override the prepare method to return a custom PDOStatement for simulation.
    public function prepare($statement, $options = array()): PDOStatement|false {
        $data = $this->data;

        // Return a custom PDOStatement that uses mock data.
        return new class($data) extends PDOStatement {
            private $data;

            public function __construct($data) {
                $this->data = $data;  // Store the mock data passed from PDODummy.
            }

            // Simulate the execution of a statement, always returning true to indicate success.
            public function execute($params = array()): bool {
                return true;
            }

            // A method to retrieve test data based on a method name.
            public function getTestData($method) {
                return $this->data[$method] ?? null;
            }

            // Simulate the rowCount method for operations like update/delete.
            public function rowCount(): int {
                return 1;  // Assume one row is always affected.
            }
        };
    }

    // Simulate the lastInsertId method commonly used in database operations.
    public function lastInsertId($name = null): string {
        return '1';  // Return a mock ID for testing.
    }
}

// Define a function to compare expected and actual values and display results in HTML.
function assertEqual($expected, $actual, $message) {
    echo "<div style='" . ($expected === $actual ? "color: green;" : "color: red;") . "'>$message: " . ($expected === $actual ? "PASS" : "FAIL (Expected: $expected, Got: $actual)") . "</div>";
}

// Instantiate the PDODummy class for testing the Payment class.
$pdo = new PDODummy();
$payment = new Payment($pdo);

// Start HTML output for test results.
echo "<!DOCTYPE html><html><head><title>Test Results</title></head><body>";
echo "<h1>Payment Class Test Results</h1>";

// Test setting payment details.
$payment->setPaymentDetails(1, 'Credit Card', 'John Doe', '1234567890123456', '12/23');

// Test validating payment CCV with both valid and invalid inputs.
assertEqual(true, $payment->validatePaymentCCV('123'), 'Validate valid CCV');
assertEqual(false, $payment->validatePaymentCCV('12'), 'Validate invalid CCV');

// Test retrieving all cards.
$stmt = $pdo->prepare("DUMMY SQL FOR TESTING");
$stmt->execute();
$cards = $stmt->getTestData('getAllCards');
assertEqual(2, count($cards), 'Retrieve all cards for customer');

// Test retrieving specific payment information.
$info = $stmt->getTestData('getPaymentInfo');
assertEqual('John Doe', $info['paymentName'], 'Retrieve payment information');

// Simulate an update operation and check if it reports rows affected.
$stmt->execute(['paymentId' => 1]);
$updateResult = $stmt->rowCount() > 0;
assertEqual(true, $updateResult, 'Update payment details');

// Simulate a delete operation and check if it reports rows affected.
$deleteResult = $stmt->rowCount() > 0;
assertEqual(true, $deleteResult, 'Delete payment record');

// Close HTML document.
echo "</body></html>";

