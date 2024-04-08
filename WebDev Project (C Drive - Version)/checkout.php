<?php
// Start the session
session_start();

// Include header
require_once "header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" type="text/css" href="checkout.css">
</head>

<body>

<main class="container">
    <h1>Checkout</h1>
    <!-- Your checkout page content goes here -->
    <form action="process_order.php" method="POST">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>
        <label for="credit-card">Credit Card:</label><br>
        <input type="text" id="credit-card" name="credit-card"><br>
        <label for="quantity">Quantity:</label><br>
        <input type="number" id="quantity" name="quantity"><br><br>
        <button type="submit">Purchase</button>
    </form>
</main>

</body>

</html>

<?php
// Include footer
require_once "footer.php";
?>
