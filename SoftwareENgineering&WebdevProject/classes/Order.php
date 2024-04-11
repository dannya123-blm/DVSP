<?php

class Order {
    protected $idOrder;
    protected $orderDate;
    protected $totalAmount;
    protected $idAdmin;
    protected $idCustomer;
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
            $sql = "INSERT INTO Orders (OrderDate, TotalAmount, idAdmin, idCustomer, idPayment) 
                VALUES (:orderDate, :totalAmount, :idAdmin, :idCustomer, :idPayment)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':orderDate', $this->orderDate, PDO::PARAM_STR);
            $stmt->bindParam(':totalAmount', $this->totalAmount, PDO::PARAM_STR);
            $stmt->bindParam(':idAdmin', $this->idAdmin, PDO::PARAM_INT);
            $stmt->bindParam(':idCustomer', $this->idCustomer, PDO::PARAM_INT);
            $stmt->bindParam(':idPayment', $this->idPayment, PDO::PARAM_INT);

            $result = $stmt->execute();

            if ($result) {
                // Order successfully inserted
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }


    public function getIdOrder() {
        return $this->idOrder;
    }
}
?>
