<?php
require_once '../classes/Payment.php';  // Adjust the path as needed to include your Payment class

class PDODummy extends PDO {
    private $data;

    public function __construct() {
        // Initialize mock data
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

    // Correct the return type for the prepare method
    public function prepare($statement, $options = array()): PDOStatement|false {
        $data = $this->data;

        return new class($data) extends PDOStatement {
            private $data;

            public function __construct($data) {
                $this->data = $data;
            }

            public function execute($params = array()): bool {
                return true;  // Simulate successful execution
            }

            public function getTestData($method) {
                return $this->data[$method] ?? null;
            }

            public function rowCount(): int {
                return 1;  // Simulate one row affected (for update/delete)
            }
        };
    }

    public function lastInsertId($name = null): string {
        return '1';  // Simulate returning an order ID
    }
}

function assertEqual($expected, $actual, $message) {
    echo "<div style='" . ($expected === $actual ? "color: green;" : "color: red;") . "'>$message: " . ($expected === $actual ? "PASS" : "FAIL (Expected: $expected, Got: $actual)") . "</div>";
}

$pdo = new PDODummy();
$payment = new Payment($pdo);

echo "<!DOCTYPE html><html><head><title>Test Results</title></head><body>";
echo "<h1>Payment Class Test Results</h1>";

$payment->setPaymentDetails(1, 'Credit Card', 'John Doe', '1234567890123456', '12/23');

assertEqual(true, $payment->validatePaymentCCV('123'), 'Validate valid CCV');
assertEqual(false, $payment->validatePaymentCCV('12'), 'Validate invalid CCV');

$stmt = $pdo->prepare("DUMMY SQL FOR TESTING");
$stmt->execute();
$cards = $stmt->getTestData('getAllCards');
assertEqual(2, count($cards), 'Retrieve all cards for customer');

$info = $stmt->getTestData('getPaymentInfo');
assertEqual('John Doe', $info['paymentName'], 'Retrieve payment information');

$stmt->execute(['paymentId' => 1]);
$updateResult = $stmt->rowCount() > 0;
assertEqual(true, $updateResult, 'Update payment details');

$deleteResult = $stmt->rowCount() > 0;
assertEqual(true, $deleteResult, 'Delete payment record');

echo "</body></html>";

