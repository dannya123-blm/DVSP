<?php

namespace classes;

// Payment class
class Payment {
    protected $idPayment;
    protected $idOrder;
    protected $paymentDate;
    protected $paymentAmount;
    protected $paymentMethod;

    public function getPaymentID() {
        return $this->idPayment;
    }

    public function setPaymentID($paymentID) {
        $this->idPayment = $paymentID;
    }

    public function getOrderID() {
        return $this->idOrder;
    }

    public function setOrderID($orderID) {
        $this->idOrder = $orderID;
    }

    public function getPaymentDate() {
        return $this->paymentDate;
    }

    public function setPaymentDate($paymentDate) {
        $this->paymentDate = $paymentDate;
    }

    public function getPaymentAmount() {
        return $this->paymentAmount;
    }

    public function setPaymentAmount($paymentAmount) {
        $this->paymentAmount = $paymentAmount;
    }

    public function getPaymentMethod() {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }
}
