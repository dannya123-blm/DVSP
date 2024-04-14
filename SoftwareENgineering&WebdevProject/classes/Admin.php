<?php
require_once 'User.php';
class Admin extends User {


    public function authenticate($username, $password, $admin_id) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM admin WHERE Username = ? AND idAdmin = ?");
        $stmt->execute([$username, $admin_id]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && $admin['Password'] === $password) {
            $this->setUserID($admin['idAdmin']);
            $this->setUsername($admin['Username']);
            $this->setRole($admin['Role']);
            return $admin;
        } else {
            return false;
        }
    }

    public function setRole($role) {
        $this->role = $role;
    }
}
?>
