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
}
?>
