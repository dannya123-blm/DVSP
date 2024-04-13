<?php
include '../template/header.php';
include '../src/dbconnect.php';
require '../classes/Customer.php';

if (isset($_SESSION['user_id'])) {
    try {
        $userId = $_SESSION['user_id'];

        $customer = new Customer();

        $userData = $customer->getUserDataById($userId);

        if ($userData) {
            echo '<div class="user-details">';
            echo '<h3>User Information</h3>';
            echo '<p><strong>Username:</strong> ' . htmlspecialchars($userData['Username']) . '</p>';
            echo '<p><strong>Email:</strong> ' . htmlspecialchars($userData['Email']) . '</p>';
            echo '<p><strong>Mobile Number:</strong> ' . htmlspecialchars($userData['MobileNumber']) . '</p>';
            echo '<p><strong>Address:</strong> ' . htmlspecialchars($userData['Address']) . '</p>';
            echo '</div>';
        } else {
            echo "User not found";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    echo "User not logged in";
}
include '../template/footer.php';
?>
