<?php

class Payment {
    private $pdo;
    private $idCustomer;
    private $paymentMethod;
    private $paymentName;
    private $paymentNumber;
    private $paymentExpiryDate;

    // Constructor to initialize the PDO object required for database operations
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Set the payment details for a payment transaction
    public function setPaymentDetails(int $idCustomer, string $paymentMethod, string $paymentName, string $paymentNumber, string $paymentExpiryDate): void {
        $this->idCustomer = $idCustomer;
        $this->paymentMethod = $paymentMethod;
        $this->paymentName = $paymentName;
        $this->paymentNumber = $paymentNumber;
        $this->paymentExpiryDate = $paymentExpiryDate;
    }

    // Validate the payment CCV
    public function validatePaymentCCV(string $paymentCCV): bool {
        // Add your validation logic here, for example, checking the length or format of the CCV
        // For simplicity, let's assume CCV must be a numeric value of length 3 or 4
        return is_numeric($paymentCCV) && (strlen($paymentCCV) === 3 || strlen($paymentCCV) === 4);
    }

    // Process the payment: insert the payment details into the database
// Process the payment: insert the payment details into the database
    public function processPayment(string $paymentCCV): string {
        try {
            if (!$this->validatePaymentCCV($paymentCCV)) {
                throw new Exception("Invalid CCV provided.");
            }

            $sql = "INSERT INTO payment (idCustomer, paymentMethod, paymentName, paymentNumber, paymentCCV, paymentExpiryDate) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$this->idCustomer, $this->paymentMethod, $this->paymentName, $this->paymentNumber, $paymentCCV, $this->paymentExpiryDate]);
            return (string)$this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // Log the detailed error message to a log file or error management system
            error_log("Error processing payment: " . $e->getMessage());
            return "Error processing payment. Please try again later.";
        }
    }


    public function getAllCards(int $customerId): array {
        $sql = "SELECT * FROM payment WHERE idCustomer = :customerId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['customerId' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaymentInfo(int $paymentId): ?array {
        $sql = "SELECT * FROM payment WHERE idPayment = :paymentId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['paymentId' => $paymentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePayment(int $paymentId, string $paymentName, string $paymentNumber, string $paymentCCV, string $paymentExpiryDate): bool {
        try {
            $sql = "UPDATE payment SET PaymentName = ?, PaymentNumber = ?, PaymentCCV = ?, PaymentExpiryDate = ? WHERE idPayment = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$paymentName, $paymentNumber, $paymentCCV, $paymentExpiryDate, $paymentId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deletePayment(int $paymentId): bool {
        try {
            $sql = "DELETE FROM payment WHERE idPayment = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$paymentId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}

