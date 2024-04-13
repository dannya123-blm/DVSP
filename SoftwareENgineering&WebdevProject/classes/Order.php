<?php

class Order {
    private $idCustomer;
    private $idAdmin;
    private $orderDate;
    private $totalAmount;
    private $idPayment;
    private $idOrder;

    public function __construct($idCustomer, $idAdmin, $orderDate, $totalAmount, $idPayment) {
        $this->idCustomer = $idCustomer;
        $this->idAdmin = $idAdmin;
        $this->orderDate = $orderDate;
        $this->totalAmount = $totalAmount;
        $this->idPayment = $idPayment;
    }

    public function saveOrderToDatabase($pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO orders (idCustomer, idAdmin, orderDate, totalAmount, idPayment) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$this->idCustomer, $this->idAdmin, $this->orderDate, $this->totalAmount, $this->idPayment]);

            if ($result) {
                // Order successfully inserted
                $this->idOrder = $pdo->lastInsertId(); // Retrieve the auto-generated order ID
                return true;
            } else {
                // Error inserting order
                return false;
            }
        } catch (PDOException $e) {
            // Handle any database errors here
            echo 'Error saving order: ' . $e->getMessage();
            return false;
        }
    }

    public function getIdOrder() {
        return $this->idOrder;
    }
}

?>
