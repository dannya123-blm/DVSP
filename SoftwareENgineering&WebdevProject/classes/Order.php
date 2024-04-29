<?php
// This code has used parts of Arnold Hensman Car.php Class;

class Order {
    private $idCustomer;
    private $orderDate;
    private $TotalAmount;
    private $idPayment;
    private $idOrder;
    private $pdo;

    public function __construct($pdo, $idCustomer = null, $orderDate = null, $TotalAmount = null, $idPayment = null, $idOrder = null) {
        $this->pdo = $pdo;
        $this->idCustomer = $idCustomer;
        $this->orderDate = $orderDate;
        $this->TotalAmount = $TotalAmount;
        $this->idPayment = $idPayment;
        $this->idOrder = $idOrder;
    }

    public function getOrderDetails($orderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE idOrders = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function createOrder($customerId, $TotalAmount, $paymentId) {
        try {
            $this->pdo->beginTransaction();
            $this->orderDate = date('Y-m-d H:i:s'); // Current timestamp

            // Check and log the types of the parameters
            error_log("Creating order with customer ID: $customerId, TotalAmount: $TotalAmount, paymentId: $paymentId");

            // Prepare the statement
            $stmt = $this->pdo->prepare("INSERT INTO orders (idCustomer, orderDate, TotalAmount, idPayment) VALUES (?, ?, ?, ?)");

            // Ensure all parameters are the correct type. Adjust casting as necessary.
            $stmt->execute([
                (int)$customerId, // Cast to integer
                $this->orderDate, // Should be a string
                (float)$TotalAmount, // Cast to float to ensure it's not an array or any other type
                (int)$paymentId // Cast to integer
            ]);

            $newOrderId = $this->pdo->lastInsertId(); // Get the newly created order ID
            $this->pdo->commit();
            return $newOrderId; // Return the new order ID
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log('Error creating order: ' . $e->getMessage());
            return false; // Return false on failure
        }
    }


    public function updateOrder($orderId, $orderDate, $TotalAmount, $paymentId) {
        $stmt = $this->pdo->prepare("UPDATE orders SET orderDate = ?, TotalAmount = ?, idPayment = ? WHERE idOrders = ?"); // Updated column name here
        return $stmt->execute([$orderDate, $TotalAmount, $paymentId, $orderId]);
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
