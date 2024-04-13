<?php


// OrderSummary class
class OrderSummary {
    protected $idOrderSummary;
    protected $idOrder;
    protected $subtotal;

    public function getOrderSummaryID() {
        return $this->idOrderSummary;
    }

    public function setOrderSummaryID($orderSummaryID) {
        $this->idOrderSummary = $orderSummaryID;
    }

    public function getOrderID() {
        return $this->idOrder;
    }

    public function setOrderID($orderID) {
        $this->idOrder = $orderID;
    }

    public function getSubtotal() {
        return $this->subtotal;
    }

    public function setSubtotal($subtotal) {
        $this->subtotal = $subtotal;
    }
    public function loadFromDatabase($pdo, $orderId) {
        $sql = "SELECT idOrderSummary, idOrder, subtotal FROM order_summaries WHERE idOrder = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$orderId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $this->idOrderSummary = $result['idOrderSummary'];
            $this->idOrder = $result['idOrder'];
            $this->subtotal = $result['subtotal'];
            return true;
        }
        return false;
    }
}
