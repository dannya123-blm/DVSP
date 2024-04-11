<?php

class Order {
    protected $idCustomer;
    protected $idAdmin;
    protected $orderDate;
    protected $totalAmount;
    protected $idPayment;

    public function __construct($idCustomer, $idAdmin, $orderDate, $totalAmount, $idPayment) {
        $this->idCustomer = $idCustomer;
        $this->idAdmin = $idAdmin;
        $this->orderDate = $orderDate;
        $this->totalAmount = $totalAmount;
        $this->idPayment = $idPayment;
    }

    public function saveOrderToDatabase($pdo) {
        try {
            // Prepare the SQL statement to insert the order into the database
            $sql = "INSERT INTO orders (OrderDate, TotalAmount, idAdmin, idCustomer, idPayment) VALUES (:OrderDate, :TotalAmount, :idAdmin, :idCustomer, :idPayment)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':OrderDate', $this->orderDate, PDO::PARAM_STR);
            $stmt->bindParam(':TotalAmount', $this->totalAmount, PDO::PARAM_STR);
            $stmt->bindParam(':idAdmin', $this->idAdmin, PDO::PARAM_INT);
            $stmt->bindParam(':idCustomer', $this->idCustomer, PDO::PARAM_INT);
            $stmt->bindParam(':idPayment', $this->idPayment, PDO::PARAM_INT);

            // Execute the prepared statement
            $result = $stmt->execute();

            // Check if the order was successfully saved
            if ($result) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Handle any PDO exceptions (e.g., database connection error, SQL error)
            // You can log the error or return false based on your error handling strategy
            // For simplicity, rethrow the exception here for demonstration
            throw $e;
        }
    }
}
?>
