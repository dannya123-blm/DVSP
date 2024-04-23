<?php

class Order {
    private $idCustomer;
    private $orderDate;
    private $totalAmount;
    private $idPayment;
    private $idOrder;
    private $pdo;

    /**
     * Constructor to initialize the Order object with a PDO connection and optional order details.
     */
    public function __construct($pdo, $idCustomer = null, $orderDate = null, $totalAmount = null, $idPayment = null, $idOrder = null) {
        $this->pdo = $pdo;
        $this->idCustomer = $idCustomer;
        $this->orderDate = $orderDate;
        $this->totalAmount = $totalAmount;
        $this->idPayment = $idPayment;
        $this->idOrder = $idOrder;
    }

    /**
     * Fetch order details by order ID.
     */
    public function getOrderDetails($orderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE idOrder = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Create and save a new order to the database.
     */
    public function createOrder($customerId, $totalAmount, $paymentId) {
        try {
            $this->pdo->beginTransaction();
            $this->orderDate = date('Y-m-d H:i:s'); // Current date and time
            $stmt = $this->pdo->prepare("INSERT INTO orders (idCustomer, orderDate, totalAmount, idPayment) VALUES (?, ?, ?, ?)");
            $stmt->execute([$customerId, $this->orderDate, $totalAmount, $paymentId]);
            $this->idOrder = $this->pdo->lastInsertId();
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log('Error creating order: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update existing order details.
     */
    public function updateOrder($orderId, $orderDate, $totalAmount, $paymentId) {
        try {
            $stmt = $this->pdo->prepare("UPDATE orders SET orderDate = ?, totalAmount = ?, idPayment = ? WHERE idOrder = ?");
            return $stmt->execute([$orderDate, $totalAmount, $paymentId, $orderId]);
        } catch (PDOException $e) {
            error_log('Error updating order: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete an order from the database.
     */
    public function deleteOrder($orderId) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM orders WHERE idOrder = ?");
            return $stmt->execute([$orderId]);
        } catch (PDOException $e) {
            error_log('Error deleting order: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the ID of the last inserted order.
     */
    public function getIdOrder() {
        return $this->idOrder;
    }
}

?>
