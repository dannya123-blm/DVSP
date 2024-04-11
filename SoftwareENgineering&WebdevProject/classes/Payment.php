<?php

class Payment {
    protected $idPayment;
    protected $idOrder;
    protected $paymentDate;
    protected $paymentAmount;
    protected $paymentMethod;

    public function setOrderID($orderID) {
        $this->idOrder = $orderID;
    }

    public function setPaymentDate($paymentDate) {
        $this->paymentDate = $paymentDate;
    }

    public function setPaymentAmount($paymentAmount) {
        $this->paymentAmount = $paymentAmount;
    }

    public function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }

    public function savePaymentToDatabase($pdo) {
        try {
            $sql = "INSERT INTO payments (idOrder, paymentDate, paymentAmount, paymentMethod) VALUES (:idOrder, :paymentDate, :paymentAmount, :paymentMethod)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':idOrder', $this->idOrder, PDO::PARAM_INT);
            $stmt->bindParam(':paymentDate', $this->paymentDate, PDO::PARAM_STR);
            $stmt->bindParam(':paymentAmount', $this->paymentAmount, PDO::PARAM_STR);
            $stmt->bindParam(':paymentMethod', $this->paymentMethod, PDO::PARAM_STR);

            $result = $stmt->execute();

            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
