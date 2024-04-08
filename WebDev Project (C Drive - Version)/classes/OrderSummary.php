<?php

namespace classes;

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
}
