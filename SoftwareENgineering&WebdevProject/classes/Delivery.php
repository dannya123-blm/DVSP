<?php
// This code has used parts of Arnold Hensman Car.php Class;
class Delivery {
    protected $pdo;
    protected $idDelivery;
    protected $idOrders;
    protected $deliveryDate;
    protected $deliveryAddress;
    protected $status;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function getOrderDetails($orderId) {
        $stmt = $this->pdo->prepare("SELECT *, purchaseDate FROM orders WHERE idOrder = :idOrder");
        $stmt->bindParam(':idOrder', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getDeliveryDetailsByOrderID($orderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM delivery WHERE idOrders = :idOrders");
        $stmt->bindParam(':idOrders', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $this->setDeliveryData($result);
        }
        return $result;
    }


    public function createDelivery($orderId, $deliveryAddress) {
        try {
            $today = date("Y-m-d");
            $stmt = $this->pdo->prepare("INSERT INTO delivery (idOrders, DeliveryDate, DeliveryAddress, Status) VALUES (:idOrders, :DeliveryDate, :DeliveryAddress, 'Pending')");
            $stmt->bindParam(':idOrders', $orderId);
            $stmt->bindParam(':DeliveryDate', $today);
            $stmt->bindParam(':DeliveryAddress', $deliveryAddress);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error in createDelivery: " . $e->getMessage());
            return false;
        }
    }

    public function getDeliveriesByUser($userId) {
        $stmt = $this->pdo->prepare("SELECT `d`.* FROM `delivery` AS `d` JOIN `orders` AS `o` ON `d`.`idOrders` = `o`.`idOrders` WHERE `o`.`idCustomer` = :userId ORDER BY `d`.`DeliveryDate` DESC");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function updateDeliveryStatus($idOrders, $newStatus) {
        $stmt = $this->pdo->prepare("UPDATE delivery SET Status = :newStatus WHERE idOrders = :idOrders");
        $stmt->bindParam(':newStatus', $newStatus);
        $stmt->bindParam(':idOrders', $idOrders);
        $stmt->execute();
        return true;
    }

    private function setDeliveryData($data) {
        $this->idDelivery = $data['idDelivery'];
        $this->idOrders = $data['idOrders'];
        $this->deliveryDate = $data['DeliveryDate'];
        $this->deliveryAddress = $data['DeliveryAddress'];
        $this->status = $data['Status'];
    }
}
?>
