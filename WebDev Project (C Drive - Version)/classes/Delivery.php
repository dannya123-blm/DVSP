<?php

namespace classes;

// Delivery class
class Delivery {
    protected $idDelivery;
    protected $idOrder;
    protected $deliveryDate;
    protected $deliveryAddress;
    protected $status;

    public function getDeliveryID() {
        return $this->idDelivery;
    }

    public function setDeliveryID($deliveryID) {
        $this->idDelivery = $deliveryID;
    }

    public function getOrderID() {
        return $this->idOrder;
    }

    public function setOrderID($orderID) {
        $this->idOrder = $orderID;
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
}