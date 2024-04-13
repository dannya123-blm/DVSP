<?php
require_once 'User.php';

class Admin extends User {
    protected $role;

    public function getRole() {
        return $this->role;
    }

    // Modified to use plain text passwords
    public function authenticate($username, $password, $admin_id) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM admin WHERE Username = ? AND idAdmin = ?");
        $stmt->execute([$username, $admin_id]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && $admin['Password'] === $password) {
            $this->setUserID($admin['idAdmin']);
            $this->setUsername($admin['Username']);
            $this->setRole($admin['Role']);
            return $admin; // Return admin data
        } else {
            return false; // Return false if authentication fails
        }
    }


    public function setRole($role) {
        $this->role = $role;
    }
}
?>
