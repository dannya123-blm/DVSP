<?php
session_start();

// Check if user is logged in
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
    <div class="logoscontainer">
        <div class="logo">
            <img src="../images/logo.png" alt="DVS Expansion">
        </div>
        <nav>
            <ul>
                <li><a href="../public/index.php">HOME</a></li>
                <li><a href="../public/productpage.php">PRODUCT</a></li>
                <li><a href="#">CONTACT US</a></li>
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
                    echo '<a href="#">Dashboard</a>';
                    echo '<a href="#">Payment</a>';
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
            <form action="../public/productpage.php" method="GET" class="searchForm">
                <input type="text" name="search" placeholder="Search products">
                <button type="submit">Search</button>
            </form>
        </div>
    </div>
</header>
</body>
</html>
