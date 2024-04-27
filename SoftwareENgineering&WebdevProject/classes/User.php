<?php

class User {
    protected $idUser;
    protected $username;
    protected $password;
    protected $email;
    protected $mobileNumber;
    public $pdo;

    public function setUserID($id) {
        $this->idUser = $id;
    }
    // Constructor with PDO dependency injection
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function setUserID($id) {
        $this->idUser = $id;
    }

    // Register a new user with unique username and email
    public function registerUser($username, $password, $email, $mobileNumber, $address) {
        // Check for unique username and email before proceeding
        if (!$this->isUsernameUnique($username)) {
            throw new Exception("Username '$username' already exists. Please choose a different username.");
        }
        if (!$this->isEmailUnique($email)) {
            throw new Exception("Email '$email' already exists. Please choose a different email.");
        }

        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement for inserting new user
        $stmt = $this->pdo->prepare("INSERT INTO customer (username, password, email, mobileNumber, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $hashedPassword, $email, $mobileNumber, $address]);
    }

    // Method to check if username is unique
    protected function isUsernameUnique($username) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM customer WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() == 0;
    }

    // Method to check if email is unique
    protected function isEmailUnique($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM customer WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() == 0;
    }

    // Update username in the database
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

    // Update email in the database
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

    // Update mobile number in the database
    public function updateMobileNumber($userId, $newMobileNumber) {
        try {
            $stmt = $this->pdo->prepare("UPDATE customer SET mobileNumber = :mobileNumber WHERE id = :userId");
            $stmt->bindParam(':mobileNumber', $newMobileNumber, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $this->mobileNumber = $newMobileNumber;
        } catch (PDOException $e) {
            throw new Exception("Error updating mobile number: " . $e->getMessage());
        }
    }

}
