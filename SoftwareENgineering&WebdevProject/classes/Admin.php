<?php
require_once 'User.php';
class Admin extends User {
    protected $role;

    public function getRole() {
        return $this->role;
    }
    public static function authenticate($username, $password, $admin_id, $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ? AND password = ? AND idAdmin = ?");
        $stmt->execute([$username, $password, $admin_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Assuming fetching as associative array
    }

    public function setRole($role) {
        $this->role = $role;
    }
}
?>
