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
    public function setPaymentDetails($idCustomer, $paymentMethod, $paymentName, $paymentNumber, $paymentExpiryDate) {
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

        $sql = "INSERT INTO payment (idCustomer, paymentMethod, paymentName, paymentNumber, paymentCCV, paymentExpiryDate) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->idCustomer, $this->paymentMethod, $this->paymentName, $this->paymentNumber, $paymentCCV, $this->paymentExpiryDate]);
        return $this->pdo->lastInsertId();
    }

    public function getAllCards($customerId) {
        // Assuming $pdo is your database connection and it's a property of this class
        $sql = "SELECT * FROM payment WHERE idCustomer = :customerId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['customerId' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaymentInfo($paymentId) {
        $sql = "SELECT * FROM payment WHERE idPayment = :paymentId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['paymentId' => $paymentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);  // fetch the first row that matches
    }

    public function updatePayment($paymentId, $paymentName, $paymentNumber, $paymentCCV, $paymentExpiryDate) {
        // Prepare an SQL statement to update payment details
        $sql = "UPDATE payment SET PaymentName = ?, PaymentNumber = ?, PaymentCCV = ?, PaymentExpiryDate = ? WHERE idPayment = ?";
        $stmt = $this->pdo->prepare($sql);

        // Execute the SQL statement with the provided parameters
        $stmt->execute([$paymentName, $paymentNumber, $paymentCCV, $paymentExpiryDate, $paymentId]);

        // Optionally, check if the update was successful
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deletePayment($paymentId) {
        // Prepare an SQL statement to delete a payment entry
        $sql = "DELETE FROM payment WHERE idPayment = ?";
        $stmt = $this->pdo->prepare($sql);

        // Execute the SQL statement with the provided payment ID
        $stmt->execute([$paymentId]);

        // Optionally, check if the deletion was successful
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}