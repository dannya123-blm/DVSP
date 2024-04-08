<?php
// Start the session
session_start();

// Include the database configuration file
require_once 'databaseconfig.php';

// Include the Order class
require_once 'classes/Order.php';

// Function to save order details to the database using prepared statements
function saveOrderToDatabase($order) {
    global $conn;

    // Prepare the SQL statement to insert the order into the database using prepared statements
    $sql = "INSERT INTO orders (OrderDate, TotalAmount, idAdmin, idCustomer, idPayment) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "sdiid", $order->getOrderDate(), $order->getTotalAmount(), $order->getIdAdmin(), $order->getIdCustomer(), $order->getIdPayment());

    // Execute the prepared statement
    $result = mysqli_stmt_execute($stmt);

    // Check if the order was successfully saved
    if ($result) {
        // Get the auto-generated order ID
        $orderID = mysqli_insert_id($conn);

        // Set the order ID in the Order object
        $order->setIdOrder($orderID);

        return true;
    } else {
        return false;
    }
}

// Check if session variables are set
if (isset($_SESSION['idCustomer']) && isset($_SESSION['idAdmin'])) {
    // Create a new Order object
    $order = new \classes\Order();

    // Populate Order object with data
    // Example:
    $order->setOrderDate(date('Y-m-d')); // Set current date as order date
    $order->setTotalAmount(calculateTotal()); // Set total amount (You need to implement calculateTotal() function)
    $order->setIdAdmin($_SESSION['idAdmin']); // Set admin ID
    $order->setIdCustomer($_SESSION['idCustomer']); // Set customer ID
    $order->setIdPayment(1); // Set payment ID (Example)

    // Save the order to the database
    $orderSaved = saveOrderToDatabase($order);

    if ($orderSaved) {
        // Clear the cart session
        unset($_SESSION['cart']);

        // Redirect to order confirmation page
        header("Location: order_confirmation.php");
        exit;
    } else {
        // If the order is not saved successfully, redirect back to cart
        header("Location: cart.php");
        exit;
    }
} else {
    // Redirect to cart page if session variables are not set
    header("Location: order_confirmation.php");
    exit;
}
?>
