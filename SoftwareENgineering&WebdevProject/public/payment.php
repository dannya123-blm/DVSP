<?php
// Include the database connection file
include '../src/dbconnect.php'; // Adjust the filename as per your setup
include '../template/header.php'; // Adjust the filename as per your setup

// Include the Payment class
include '../classes/Payment.php'; // Assuming the Payment class is in Payment.php file

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php"); // Adjust the filename and path as per your setup
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create a new Payment object
    $payment = new Payment();

    // Get customer ID from session
    $customerID = $_SESSION['user_id'];

    // Set customer ID
    $payment->setCustomerID($customerID);

    // Set payment details from form inputs
    $paymentDate = $_POST['payment_date'];
    $paymentMethod = $_POST['payment_method'];
    $paymentName = $_POST['payment_name'];
    $paymentNumber = $_POST['payment_number'];

    // Hash the CVV
    $paymentCCV = password_hash($_POST['payment_ccv'], PASSWORD_DEFAULT);

    // Validate payment method (only Mastercard or Visa allowed)
    if ($paymentMethod !== "Mastercard" && $paymentMethod !== "Visa") {
        echo "Error: Only Mastercard and Visa are allowed as payment methods.";
        exit;
    }

    // Insert payment details into the database using PDO prepared statements
    $sql = "INSERT INTO payments (idCustomer, paymentDate, paymentMethod, paymentName, paymentNumber, paymentCCV) 
            VALUES (:idCustomer, :paymentDate, :paymentMethod, :paymentName, :paymentNumber, :paymentCCV)";
    $stmt = $pdo->prepare($sql); // Fixed the arrow operator
    $stmt->bindParam(':idCustomer', $customerID); // Use the retrieved customer ID
    $stmt->bindParam(':paymentDate', $paymentDate);
    $stmt->bindParam(':paymentMethod', $paymentMethod);
    $stmt->bindParam(':paymentName', $paymentName);
    $stmt->bindParam(':paymentNumber', $paymentNumber);
    $stmt->bindParam(':paymentCCV', $paymentCCV);

    if ($stmt->execute()) {
        echo "Payment details saved successfully.";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }

    // Close statement
    $stmt->closeCursor(); // Optional for PDO, closes the cursor
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="../css/payment.css">
</head>
<body>
<div class="payment-container">
    <h2>Enter Payment Details</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Payment Date: <input type="date" name="payment_date"><br><br>
        Payment Method:
        <select name="payment_method">
            <option value="Mastercard">Mastercard</option>
            <option value="Visa">Visa</option>
        </select><br><br>
        Payment Name: <input type="text" name="payment_name"><br><br>
        Payment Number: <input type="text" name="payment_number"><br><br>
        Payment CCV: <input type="password" name="payment_ccv"><br><br> <!-- Use type="password" for secure input -->
        <input type="submit" value="Save Payment">
    </form>
</div>
</body>
</html>

<?php include '../template/footer.php';?>
