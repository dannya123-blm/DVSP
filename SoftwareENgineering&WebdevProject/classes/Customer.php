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

    public static function getUserDataById($userId)
    {
        global $pdo; // Assuming $pdo is accessible globally or injected

        try {
            $stmt = $pdo->prepare("SELECT Username, Email, MobileNumber, Address FROM customer WHERE idCustomer = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching user data: " . $e->getMessage());
        }
    }

    public function verifyPassword($userId, $password)
    {
        global $pdo; // Assuming $pdo is accessible globally or injected

        try {
            $stmt = $pdo->prepare("SELECT Password FROM customer WHERE idCustomer = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($userData) {
                return password_verify($password, $userData['Password']);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Error verifying password: " . $e->getMessage());
        }
    }

    public function updatePassword($userId, $newPassword)
    {
        global $pdo; // Assuming $pdo is accessible globally or injected

        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE customer SET Password = :password WHERE idCustomer = :userId");
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error updating password: " . $e->getMessage());
        }
    }

    // Add other methods for updating user details (username, email, mobile number, etc.)
}
?>
