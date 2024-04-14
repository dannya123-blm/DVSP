<?php

class Order {
    private $idCustomer;
    private $orderDate;
    private $totalAmount;
    private $idPayment;
    private $idOrder;
    private $pdo;

    /**
     * Constructor to initialize the Order object.
     */
    public function __construct($pdo, $idCustomer = null, $orderDate = null, $totalAmount = null, $idPayment = null) {
        $this->pdo = $pdo;
        $this->idCustomer = $idCustomer;
        $this->orderDate = $orderDate;
        $this->totalAmount = $totalAmount;
        $this->idPayment = $idPayment;
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
     * Save order to the database.
     */
    public function saveOrderToDatabase() {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO orders (idCustomer, orderDate, totalAmount, idPayment) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$this->idCustomer, $this->orderDate, $this->totalAmount, $this->idPayment])) {
                $this->idOrder = $this->pdo->lastInsertId();
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log('Error saving order: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update existing order details.
     */
    public function updateOrder($orderId, $newDetails) {
        try {
            $stmt = $this->pdo->prepare("UPDATE orders SET orderDate = ?, totalAmount = ?, idPayment = ? WHERE idOrder = ?");
            return $stmt->execute([$newDetails['orderDate'], $newDetails['totalAmount'], $newDetails['idPayment'], $orderId]);
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
