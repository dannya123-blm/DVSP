<?php
class User {
    protected $idUser;
    protected $username;
    protected $password;
    protected $email;
    protected $mobileNumber;

    public function getUserID() {
        return $this->idUser;
    }

    public function setUserID($userID) {
        $this->idUser = $userID;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        // You might want to hash the password before setting it
        $this->password = $password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getMobileNumber() {
        return $this->mobileNumber;
    }

    public function setMobileNumber($mobileNumber) {
        $this->mobileNumber = $mobileNumber;
    }

    public function updateUsername($userId, $newUsername) {
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

        $this->username = $newUsername;
    }

    public function updateEmail($userId, $newEmail) {
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

        $this->email = $newEmail;
    }

    public function updateMobileNumber($userId, $newMobileNumber) {
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

        $this->mobileNumber = $newMobileNumber;
    }
}
?>
