<?php

class Payment {
    protected $idPayment;
    protected $idCustomer;
    protected $paymentDate;
    protected $paymentMethod;
    protected $paymentName;
    protected $paymentNumber;
    protected $paymentCCV;

    // Setter method for payment ID
    public function setPaymentID($paymentID) {
        $this->idPayment = $paymentID;
    }

    // Getter method for payment ID
    public function getPaymentID() {
        return $this->idPayment;
    }

    // Setter method for customer ID
    public function setCustomerID($customerID) {
        $this->idCustomer = $customerID;
    }

    // Getter method for customer ID
    public function getCustomerID() {
        return $this->idCustomer;
    }

    // Setter method for payment date
    public function setPaymentDate($paymentDate) {
        $this->paymentDate = $paymentDate;
    }

    // Getter method for payment date
    public function getPaymentDate() {
        return $this->paymentDate;
    }

    // Setter method for payment method
    public function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }

    // Getter method for payment method
    public function getPaymentMethod() {
        return $this->paymentMethod;
    }

    // Setter method for payment name
    public function setPaymentName($paymentName) {
        $this->paymentName = $paymentName;
    }

    // Getter method for payment name
    public function getPaymentName() {
        return $this->paymentName;
    }

    // Setter method for payment number
    public function setPaymentNumber($paymentNumber) {
        $this->paymentNumber = $paymentNumber;
    }

    // Getter method for payment number
    public function getPaymentNumber() {
        return $this->paymentNumber;
    }

    // Setter method for payment CCV
    public function setPaymentCCV($paymentCCV) {
        $this->paymentCCV = $paymentCCV;
    }

    // Getter method for payment CCV
    public function getPaymentCCV() {
        return $this->paymentCCV;
    }
}

