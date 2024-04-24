<?php

class Order {
    private $idCustomer;
    private $orderDate;
    private $totalAmount;
    private $idPayment;
    private $idOrder;
    private $pdo;

    public function __construct($pdo, $idCustomer = null, $orderDate = null, $totalAmount = null, $idPayment = null, $idOrder = null) {
        $this->pdo = $pdo;
        $this->idCustomer = $idCustomer;
        $this->orderDate = $orderDate;
        $this->totalAmount = $totalAmount;
        $this->idPayment = $idPayment;
        $this->idOrder = $idOrder;
    }

    public function getOrderDetails($orderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE idOrders = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Returns false if no record is found
    }


    public function createOrder($customerId, $totalAmount, $paymentId) {
        try {
            $this->pdo->beginTransaction();
            $this->orderDate = date('Y-m-d H:i:s'); // Current timestamp
            $stmt = $this->pdo->prepare("INSERT INTO orders (idCustomer, orderDate, totalAmount, idPayment) VALUES (?, ?, ?, ?)");
            $stmt->execute([$customerId, $this->orderDate, $totalAmount, $paymentId]);
            $newOrderId = $this->pdo->lastInsertId(); // Get the newly created order ID
            $this->pdo->commit();
            return $newOrderId; // Return the new order ID
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log('Error creating order: ' . $e->getMessage());
            return false; // Return false on failure
        }
    }


    public function updateOrder($orderId, $orderDate, $totalAmount, $paymentId) {
        $stmt = $this->pdo->prepare("UPDATE orders SET orderDate = ?, totalAmount = ?, idPayment = ? WHERE idOrders = ?"); // Updated column name here
        return $stmt->execute([$orderDate, $totalAmount, $paymentId, $orderId]);
    }

    public function deleteOrder($orderId) {
        $stmt = $this->pdo->prepare("DELETE FROM orders WHERE idOrders = ?"); // Updated column name here
        return $stmt->execute([$orderId]);
    }

    public function getIdOrder() {
        return $this->idOrder;
    }
}


?>
