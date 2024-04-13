<?php

class Order {
    private $idCustomer;
    private $idAdmin;
    private $orderDate;
    private $totalAmount;
    private $idPayment;
    private $idOrder;
    private $pdo;

    // Single constructor to initialize the database connection and optionally initialize order properties
    public function __construct($pdo, $idCustomer = null, $idAdmin = null, $orderDate = null, $totalAmount = null, $idPayment = null) {
        $this->pdo = $pdo;
        $this->idCustomer = $idCustomer;
        $this->idAdmin = $idAdmin;
        $this->orderDate = $orderDate;
        $this->totalAmount = $totalAmount;
        $this->idPayment = $idPayment;
    }

    // Retrieve order details
    public function getOrderDetails($orderId) {
        $stmt = $this->pdo->prepare("SELECT product_id, quantity FROM orders WHERE id = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Save order to database
    public function saveOrderToDatabase() {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO orders (idCustomer, idAdmin, orderDate, totalAmount, idPayment) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$this->idCustomer, $this->idAdmin, $this->orderDate, $this->totalAmount, $this->idPayment]);

            if ($result) {
                $this->idOrder = $this->pdo->lastInsertId(); // Retrieve the auto-generated order ID
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo 'Error saving order: ' . $e->getMessage();
            return false;
        }
    }

    // Get the ID of the order
    public function getIdOrder() {
        return $this->idOrder;
    }
}

?>
