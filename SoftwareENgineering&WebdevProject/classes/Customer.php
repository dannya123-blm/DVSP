<?php

require 'User.php';

// Customer class inheriting from User
class Customer extends User
{
    protected $address;

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    // Method to fetch user data by ID from the database
    public function getUserDataById($userId)
    {
        global $pdo; // Assuming $pdo is accessible globally or injected

        $stmt = $pdo->prepare("SELECT Username, Email, MobileNumber, Address FROM customer WHERE idCustomer = :userId");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Return user data as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
