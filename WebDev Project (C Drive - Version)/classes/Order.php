<?php

namespace classes;

class Order {
    protected $idOrders;
    protected $idCustomer;
    protected $idAdmin;
    protected $orderDate;
    protected $totalAmount; // Changed to camel case for consistency

    // Constructor to set initial values
    public function __construct($idCustomer, $idAdmin, $orderDate, $totalAmount) {
        $this->idCustomer = $idCustomer;
        $this->idAdmin = $idAdmin;
        $this->orderDate = $orderDate;
        $this->totalAmount = $totalAmount;
    }

    public function getOrderID() {
        return $this->idOrders;
    }

    public function setOrderID($orderID) {
        $this->idOrders = $orderID;
    }

    public function getCustomerID() {
        return $this->idCustomer;
    }

    public function setCustomerID($customerID) {
        $this->idCustomer = $customerID;
    }

    public function getAdminID() {
        return $this->idAdmin;
    }

    public function setAdminID($adminID) {
        $this->idAdmin = $adminID;
    }

    public function getOrderDate() {
        return $this->orderDate;
    }

    public function setOrderDate($orderDate) {
        $this->orderDate = $orderDate;
    }

    public function getTotalAmount() {
        return $this->totalAmount;
    }

    public function setTotalAmount($totalAmount) {
        $this->totalAmount = $totalAmount;
    }

    // Function to save order details to the database
    public function saveOrderToDatabase($conn) {
        // Prepare the SQL statement to insert the order into the database
        $sql = "INSERT INTO orders (idCustomer, idAdmin, orderDate, totalAmount) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iisd", $this->idCustomer, $this->idAdmin, $this->orderDate, $this->totalAmount);

        // Execute the prepared statement
        $result = mysqli_stmt_execute($stmt);

        // Check if the order was successfully saved
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
