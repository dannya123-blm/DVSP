<?php
session_start();

// Check if user is logged in
$isAdminLoggedIn = isset($_SESSION['admin_id']);
$isLoggedIn = isset($_SESSION['user_id']); // Assuming you have a session variable for customer login

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DVS Expansion</title>
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>
<header>
    <div class="logoscontainer">
        <div class="logo">
            <img src="../images/logo.png" alt="DVS Expansion">
        </div>
        <nav>
            <ul>
                <li><a href="../public/index.php">HOME</a></li>
                <li><a href="../public/productpage.php">PRODUCT</a></li>
                <?php
                // Display "CONNECTIONS" link only if user is logged in as admin
                if ($isAdminLoggedIn) {
                    echo '<li><a href="../administrator/adminscrud.php">CONNECTIONS</a></li>';
                }
                ?>
                <li><a href="#">CONTACT US</a></li>
                <li><a href="../public/findus.php">FIND US</a></li>
            </ul>
        </nav>
        <br>

        <div class="account-basket">
            <div>
                <?php
                if ($isAdminLoggedIn) {
                    echo '<a href="../public/logout.php"><img src="../images/login.png" alt="Logout"></a>';
                    echo '<div class="loginButton"><a href="../public/logout.php">Logout</a></div>';
                } elseif ($isLoggedIn) {
                    echo '<a href="../public/logout.php"><img src="../images/login.png" alt="Logout"></a>';
                    echo '<div class="loginButton"><a href="../public/logout.php">Logout</a></div>';
                } else {
                    echo '<a href="../public/login.php"><img src="../images/login.png" alt="Login"></a>';
                    echo '<div class="loginButton"><a href="../public/login.php">Login</a></div>';
                }
                ?>
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