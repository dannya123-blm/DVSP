<?php
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

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
                <li><a href="#">CONNECTIONS</a></li>
                <li><a href="#">DEALS</a></li>
                <li><a href="#">FIND US</a></li>
            </ul>
        </nav>
        <br>

        <div class="account-basket">
            <div>
                <?php
                // Display appropriate link and text based on login status
                if ($isLoggedIn) {
                    echo '<a href="../public/logout.php"><img src="../images/login.png" alt="Logout"></a>';
                    echo '<div class="loginButton"><a href="../public/logout.php">Logout</a></div>';
                } else {
                    echo '<a href="../public/login.php"><img src="../images/login.png" alt="Login"></a>';
                    echo '<div class="loginButton"><a href="../public/login.php">Login</a></div>';
                }
                ?>
            </div>
            <div>
                <a href="cart.php"><img src="../images/cart.png" alt="Basket"></a>
                <div class="cartButton"><a href="cart.php">Cart</a></div>
            </div>
        </div>

        <div class="searchBarButton">
            <input type="text" placeholder="Search">
            <button type="button">Search</button>
        </div>
    </div>
</header>
</body>
</html>
