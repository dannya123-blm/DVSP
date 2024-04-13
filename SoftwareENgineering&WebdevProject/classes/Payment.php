<?php

class Payment {
    private $pdo;
    private $idCustomer;
    private $paymentMethod;
    private $paymentName;
    private $paymentNumber;
    private $paymentExpiryDate;

    // Constructor to initialize the PDO object required for database operations
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Set the payment details for a payment transaction
    public function setPaymentDetails($idCustomer, $paymentDate, $paymentMethod, $paymentName, $paymentNumber, $paymentExpiryDate) {
        $this->idCustomer = $idCustomer;
        $this->paymentMethod = $paymentMethod;
        $this->paymentName = $paymentName;
        $this->paymentNumber = $paymentNumber;
        $this->paymentExpiryDate = $paymentExpiryDate;  // Set the expiry date
    }

    // Validate the payment CCV
    public function validatePaymentCCV($paymentCCV) {
        // Add your validation logic here, for example, checking the length or format of the CCV
        // For simplicity, let's assume CCV must be a numeric value of length 3 or 4
        if (!is_numeric($paymentCCV) || (strlen($paymentCCV) !== 3 && strlen($paymentCCV) !== 4)) {
            return false;
        }
        return true;
    }

    // Process the payment: insert the payment details into the database
    public function processPayment($paymentCCV) {
        // Validate the payment CCV
        if (!$this->validatePaymentCCV($paymentCCV)) {
            return "Error: Invalid CCV.";
        }

        $sql = "INSERT INTO payment (idCustomer, paymentMethod, paymentName, paymentNumber, paymentCCV, paymentExpiryDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->idCustomer, $this->paymentMethod, $this->paymentName, $this->paymentNumber, $paymentCCV, $this->paymentExpiryDate]);
        return $this->pdo->lastInsertId();  // Return the ID of the new payment entry
    }

    public function getAllCards($customerId) {
        // Assuming $pdo is your database connection and it's a property of this class
        $sql = "SELECT * FROM payment WHERE idCustomer = :customerId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['customerId' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}