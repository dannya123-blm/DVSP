<?php
require_once 'User.php';

class Admin extends User {
    protected $role;

    public function getRole() {
        return $this->role;
    }

    public function authenticate($adminId, $username, $password) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM admin WHERE idAdmin = ?");
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['Password']) && $admin['Username'] === $username) {
            $this->setUserID($admin['idAdmin']); // Set correct column name for Admin ID
            $this->setUsername($admin['Username']);
            $this->setRole($admin['Role']);
            return true;
        } else {
            return false;
        }
    }

    public function setRole($role) {
        $this->role = $role;
    }
}
?>
