<?php
class User {
    protected $idUser;
    protected $username;
    protected $password;
    protected $email;
    protected $mobileNumber;
    public $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

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
        $this->password = $password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
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

    protected function isUsernameUnique($username) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM customer WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() == 0;
    }

    protected function isEmailUnique($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM customer WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() == 0;
    }

    public function updateUsername($userId, $newUsername) {
        if ($this->isUsernameUnique($newUsername)) {
            $stmt = $this->pdo->prepare("UPDATE customer SET username = :username WHERE id = :userId");
            $stmt->bindParam(':username', $newUsername, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $this->username = $newUsername;
        } else {
            throw new Exception("Username '$newUsername' already exists. Please choose a different username.");
        }
    }

    public function updateEmail($userId, $newEmail) {
        if ($this->isEmailUnique($newEmail)) {
            $stmt = $this->pdo->prepare("UPDATE customer SET email = :email WHERE id = :userId");
            $stmt->bindParam(':email', $newEmail, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $this->email = $newEmail;
        } else {
            throw new Exception("Email '$newEmail' already exists. Please choose a different email.");
        }
    }

    public function updateMobileNumber($userId, $newMobileNumber) {
        $stmt = $this->pdo->prepare("UPDATE customer SET mobileNumber = :mobileNumber WHERE id = :userId");
        $stmt->bindParam(':mobileNumber', $newMobileNumber, PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $this->mobileNumber = $newMobileNumber;
    }
}
