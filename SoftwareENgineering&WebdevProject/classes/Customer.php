<?php
require 'user.php';

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
    public static function getUserDataById($userId)
    {
        global $pdo; // Assuming $pdo is accessible globally or injected

        try {
            $stmt = $pdo->prepare("SELECT Username, Email, MobileNumber, Address FROM customer WHERE idCustomer = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Check if user exists
            if ($stmt->rowCount() > 0) {
                // Fetch user data as an associative array
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                // User not found
                return false;
            }
        } catch (PDOException $e) {
            // Handle database error
            throw new Exception("Error fetching user data: " . $e->getMessage());
        }
    }

    // Method to update customer username in the database
    public function updateUsername($userId, $newUsername)
    {
        global $pdo;

        try {
            $stmt = $pdo->prepare("UPDATE customer SET Username = :username WHERE idCustomer = :userId");
            $stmt->bindParam(':username', $newUsername, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle database error
            throw new Exception("Error updating username: " . $e->getMessage());
        }
    }

    // Method to update customer email in the database
    public function updateEmail($userId, $newEmail)
    {
        global $pdo;

        try {
            $stmt = $pdo->prepare("UPDATE customer SET Email = :email WHERE idCustomer = :userId");
            $stmt->bindParam(':email', $newEmail, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle database error
            throw new Exception("Error updating email: " . $e->getMessage());
        }
    }

    // Method to update customer mobile number in the database
    public function updateMobileNumber($userId, $newMobileNumber)
    {
        global $pdo;

        try {
            $stmt = $pdo->prepare("UPDATE customer SET MobileNumber = :mobileNumber WHERE idCustomer = :userId");
            $stmt->bindParam(':mobileNumber', $newMobileNumber, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle database error
            throw new Exception("Error updating mobile number: " . $e->getMessage());
        }
    }

    // Method to update customer address in the database
    public function updateAddress($userId, $newAddress)
    {
        global $pdo;

        try {
            $stmt = $pdo->prepare("UPDATE customer SET Address = :address WHERE idCustomer = :userId");
            $stmt->bindParam(':address', $newAddress, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle database error
            throw new Exception("Error updating address: " . $e->getMessage());
        }
    }
}
?>
