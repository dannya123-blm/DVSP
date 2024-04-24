<?php

class Delivery {
    protected $pdo; // Add this line to declare the PDO object
    protected $idDelivery;
    protected $idOrders;
    protected $deliveryDate;
    protected $deliveryAddress;
    protected $status;

    // Constructor to initialize the PDO object
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getDeliveryID() {
        return $this->idDelivery;
    }

    public function setDeliveryID($deliveryID) {
        $this->idDelivery = $deliveryID;
    }

    public function getOrderID() {
        return $this->idOrders;
    }

    public function setOrderID($orderID) {
        $this->idOrders = $orderID;
    }

    public function getDeliveryDate() {
        return $this->deliveryDate;
    }

    public function setDeliveryDate($deliveryDate) {
        $this->deliveryDate = $deliveryDate;
    }

    public function getDeliveryAddress() {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress($deliveryAddress) {
        $this->deliveryAddress = $deliveryAddress;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function createDelivery($idOrders, $DeliveryDate, $DeliveryAddress, $status) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO delivery (idOrders, DeliveryDate, DeliveryAddress, Status) VALUES (:idOrders, :DeliveryDate, :DeliveryAddress, :Status)");
            $stmt->execute([
                ':idOrders' => $idOrders,
                ':DeliveryDate' => $DeliveryDate,
                ':DeliveryAddress' => $DeliveryAddress,
                ':Status' => $status
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // Handle error appropriately
            return false;
        }
    }
}

?>
