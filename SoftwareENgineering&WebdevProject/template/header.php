<?php
// This code is based on the assignment PHP : Sessions, by Robert Smith;

session_start();
include '../src/dbconnect.php';
$isAdminLoggedIn = isset($_SESSION['admin_id']);
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DVS Expansion</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dropdown.css">
</head>
<body>
<div class="background-banner"></div>
<header>
    <div class="logos-container">
        <div class="logo">
            <img src="../images/logo.png" alt="DVS Expansion">
        </div>
        <nav>
            <ul>
                <li><a href="../public/index.php">HOME</a></li>
                <li><a href="../public/productpage.php">PRODUCT</a></li>
                <li><a href="../public/contactus.php">CONTACT US</a></li>
                <li><a href="../public/findus.php">FIND US</a></li>
            </ul>
        </nav>
        <br>
        <div class="account-basket">
            <div class="dropdown">
                <?php
                if ($isAdminLoggedIn || $isLoggedIn) {
                    echo '<img src="../images/login.png" alt="Dropdown">';
                    echo '<div class="dropdown-content">';
                    echo '<a href="../public/dashboard.php">Dashboard</a>';
                    echo '<a href="../public/payment.php">Payment</a>';
                    echo '<a href="../public/paymentedit.php">EditCard</a>';
                    echo '<a href="../public/Passwordchanger.php">ChangePassword</a>';
                    echo '<a href="../public/deliveryStatus.php">CheckOrder</a>';
                    if ($isAdminLoggedIn) {
                        echo '<a href="../administrator/adminscrud.php">Connections</a>';
                    }
                    echo '<a href="../public/logout.php">Logout</a>';
                    echo '</div>';
                } else {
                    echo '<a href="../public/login.php"><img src="../images/login.png" alt="Login"></a>';
                }
                ?>
                <div class="loginButton">
                    <?php
                    if ($isAdminLoggedIn || $isLoggedIn) {
                        echo '<a href="../public/logout.php">Logout</a>';
                    } else {
                        echo '<a href="../public/login.php">Login</a>';
                    }
                    ?>
                </div>
            </div>
            <div>
                <a href="../public/cart.php"><img src="../images/cart.png" alt="Basket"></a>
                <div class="cartButton"><a href="../public/cart.php">Cart</a></div>
            </div>
        </div>
        <div class="searchBarButton">
            <input type="text" placeholder="Search" id="searchInput">
            <button type="button" onclick="redirectToSearch()">Search</button>
        </div>

        <script>
            function redirectToSearch() {
                var searchTerm = document.getElementById('searchInput').value;
                if (searchTerm.trim() !== '') {
                    window.location.href = 'productpage.php?search=' + encodeURIComponent(searchTerm);
                }
            }
        </script>

    </div>
</header>
</body>
</html>