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
        // Check if the new username is unique before setting it
        if ($this->isUsernameUnique($username)) {
            $this->username = $username;
        } else {
            throw new Exception("Username '$username' already exists. Please choose a different username.");
        }
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
        // Check if the new email is unique before setting it
        if ($this->isEmailUnique($email)) {
            $this->email = $email;
        } else {
            throw new Exception("Email '$email' already exists. Please choose a different email.");
        }
    }

    public function getMobileNumber() {
        return $this->mobileNumber;
    }

    public function setMobileNumber($mobileNumber) {
        $this->mobileNumber = $mobileNumber;
    }

    // Helper method to check if a username is unique
    protected function isUsernameUnique($username) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM customer WHERE Username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() == 0;
    }

    // Helper method to check if an email is unique
    protected function isEmailUnique($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM customer WHERE Email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() == 0;
    }

    public function updateUsername($userId, $newUsername) {
        global $pdo;

        try {
            // Check if the new username is unique before updating
            if ($this->isUsernameUnique($newUsername)) {
                $stmt = $pdo->prepare("UPDATE customer SET Username = :username WHERE idCustomer = :userId");
                $stmt->bindParam(':username', $newUsername, PDO::PARAM_STR);
                $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                $stmt->execute();
                $this->username = $newUsername;
            } else {
                throw new Exception("Username '$newUsername' already exists. Please choose a different username.");
            }
        } catch (PDOException $e) {
            // Handle database error
            throw new Exception("Error updating username: " . $e->getMessage());
        }
    }

    public function updateEmail($userId, $newEmail) {
        global $pdo;

        try {
            // Check if the new email is unique before updating
            if ($this->isEmailUnique($newEmail)) {
                $stmt = $pdo->prepare("UPDATE customer SET Email = :email WHERE idCustomer = :userId");
                $stmt->bindParam(':email', $newEmail, PDO::PARAM_STR);
                $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                $stmt->execute();
                $this->email = $newEmail;
            } else {
                throw new Exception("Email '$newEmail' already exists. Please choose a different email.");
            }
        } catch (PDOException $e) {
            // Handle database error
            throw new Exception("Error updating email: " . $e->getMessage());
        }
    }

    public function updateMobileNumber($userId, $newMobileNumber) {
        global $pdo;

        try {
            $stmt = $pdo->prepare("UPDATE customer SET MobileNumber = :mobileNumber WHERE idCustomer = :userId");
            $stmt->bindParam(':mobileNumber', $newMobileNumber, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $this->mobileNumber = $newMobileNumber;
        } catch (PDOException $e) {
            // Handle database error
            throw new Exception("Error updating mobile number: " . $e->getMessage());
        }
    }
}
?>
