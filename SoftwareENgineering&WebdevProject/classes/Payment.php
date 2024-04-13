<?php

class Payment {
private $pdo;
private $idCustomer;
private $paymentDate;
private $paymentMethod;
private $paymentName;
private $paymentNumber;

// Constructor to initialize the PDO object required for database operations
public function __construct($pdo) {
$this->pdo = $pdo;
}

// Set the payment details for a payment transaction
public function setPaymentDetails($idCustomer, $paymentDate, $paymentMethod, $paymentName, $paymentNumber) {
$this->idCustomer = $idCustomer;
$this->paymentDate = $paymentDate;
$this->paymentMethod = $paymentMethod;
$this->paymentName = $paymentName;
$this->paymentNumber = $paymentNumber;
}

// Validate the payment CCV
public function validatePaymentCCV($paymentCCV) {
// You can add your validation logic here, for example, checking the length or format of the CCV
// For simplicity, let's assume CCV must be a numeric value of length 3 or 4
if (!is_numeric($paymentCCV) || (strlen($paymentCCV) !== 3 && strlen($paymentCCV) !== 4)) {
return false; // CCV is invalid
}
return true; // CCV is valid
}

// Process the payment: insert the payment details into the database
public function processPayment($paymentCCV) {
// Validate the payment CCV
if (!$this->validatePaymentCCV($paymentCCV)) {
return "Error: Invalid CCV.";
}

$sql = "INSERT INTO payment (idCustomer, paymentDate, paymentMethod, paymentName, paymentNumber, paymentCCV) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $this->pdo->prepare($sql);
$stmt->execute([$this->idCustomer, $this->paymentDate, $this->paymentMethod, $this->paymentName, $this->paymentNumber, $paymentCCV]);
return $this->pdo->lastInsertId();  // Return the ID of the new payment entry
}

public function getAllCards($customerId) {
    $sql = "SELECT * FROM payment WHERE idCustomer = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$customerId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function deletePayment($paymentId) {
        $sql = "DELETE FROM payment WHERE idPayment = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$paymentId]);
    }
    // Inside the Payment class
    public function updatePayment($paymentId, $paymentName, $paymentNumber, $paymentCCV) {
        $sql = "UPDATE payment SET PaymentName = ?, PaymentNumber = ?, PaymentCCV = ? WHERE idPayment = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$paymentName, $paymentNumber, $paymentCCV, $paymentId]);
    }

    public function getPaymentInfo($paymentId) {
        $sql = "SELECT * FROM payment WHERE idPayment = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$paymentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>