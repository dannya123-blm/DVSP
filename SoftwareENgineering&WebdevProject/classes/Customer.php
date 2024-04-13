<?php
require 'user.php'; // Include base User class definition

class Customer extends User
{
    protected $address;
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function authenticateUser($username, $password)
    {
        try {
            // Retrieve user data from the database based on username
            $stmt = $this->pdo->prepare("SELECT * FROM customer WHERE Username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Verify the password
                if (password_verify($password, $user['Password'])) {
                    return $user; // Return user data on successful authentication
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Error authenticating user: " . $e->getMessage());
        }

        return false; // Return false if authentication fails
    }

    public function registerUser($username, $password, $email, $mobileNumber, $address)
    {
        try {
            // Validate password criteria
            if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password)) {
                throw new Exception("Password must be at least 8 characters long and contain at least one uppercase letter");
            }

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement to insert user data
            $sql = "INSERT INTO customer (Username, Password, Email, MobileNumber, Address) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$username, $hashedPassword, $email, $mobileNumber, $address]);
        } catch (PDOException $e) {
            // Handle database error (duplicate entry)
            if ($e->getCode() == '23000') {
                // Determine which field caused the duplicate entry
                if (strpos($e->getMessage(), 'Email')) {
                    throw new Exception("Sorry, this email already exists. Please try a different email or <a href='../public/login.php'>login</a>.");
                } elseif (strpos($e->getMessage(), 'Username')) {
                    throw new Exception("Sorry, this username already exists. Please choose another username or <a href='../public/login.php'>login</a>.");
                } else {
                    throw new Exception("An error occurred. Please try again.");
                }
            } else {
                throw new Exception("Database error: " . $e->getMessage());
            }
        }
    }

    public function updateAddress($userId, $newAddress)
    {
        try {
            // Update the address for the specified user in the database
            $sql = "UPDATE customer SET Address = :newAddress WHERE idCustomer = :userId";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':newAddress', $newAddress);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error updating address: " . $e->getMessage());
        }
    }

    public function updateMobileNumber($userId, $newMobileNumber)
    {
        try {
            // Update the mobile number for the specified user in the database
            $sql = "UPDATE customer SET MobileNumber = :newMobileNumber WHERE idCustomer = :userId";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':newMobileNumber', $newMobileNumber);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error updating mobile number: " . $e->getMessage());
        }
    }

    public function getUserDataById($userId)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT Username, Email, MobileNumber, Address FROM customer WHERE idCustomer = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Error fetching user data: " . $e->getMessage());
        }
    }

    public function updateUsername($userId, $newUsername)
    {
        try {
            // Update the username for the specified user in the database
            $sql = "UPDATE customer SET Username = :newUsername WHERE idCustomer = :userId";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':newUsername', $newUsername);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error updating username: " . $e->getMessage());
        }
    }

    public function updateEmail($userId, $newEmail)
    {
        try {
            // Update the email for the specified user in the database
            $sql = "UPDATE customer SET Email = :newEmail WHERE idCustomer = :userId";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':newEmail', $newEmail);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error updating email: " . $e->getMessage());
        }
    }

    public function updatePassword($userId, $newPassword)
    {
        try {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password for the specified user in the database
            $sql = "UPDATE customer SET Password = :newPassword WHERE idCustomer = :userId";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':newPassword', $hashedPassword);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error updating password: " . $e->getMessage());
        }
    }

    public function emailExists($email)
    {
        try {
            // Check if the given email already exists in the database
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM customer WHERE Email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        } catch (PDOException $e) {
            throw new Exception("Error checking email existence: " . $e->getMessage());
        }
    }

    public function usernameExists($username)
    {
        try {
            // Check if the given username already exists in the database
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM customer WHERE Username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        } catch (PDOException $e) {
            throw new Exception("Error checking username existence: " . $e->getMessage());
        }
    }
}

