<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../classes/Payment.php';  // Ensure the path to your Payment class is correct
    include '../src/dbconnect.php';         // Ensure your database connection file is correctly included

    $payment = new Payment($pdo);  // Make sure $pdo is correctly instantiated from your database connection script

    // Define your test cases for both validatePaymentCCV and processPayment methods
    $testCases = [
        ['ccv' => '1234', 'expected' => 'pass'],   // Valid CCV (Assuming '1234' is a correct CCV)
        ['ccv' => 'abc', 'expected' => 'fail'],    // Non-numeric CCV, should fail
        ['ccv' => '12', 'expected' => 'fail'],     // Too short CCV, should fail
        ['ccv' => '12345', 'expected' => 'fail'],  // Too long CCV, should fail
        ['ccv' => '', 'expected' => 'fail'],       // Empty CCV, should fail
        ['ccv' => null, 'expected' => 'fail']      // Null CCV, should fail (handled as a string in the function)
    ];

    // Function to test validatePaymentCCV
    function testValidatePaymentCCV($payment, $testCase) {
        $ccv = $testCase['ccv'] ?? '';  // Using null coalescing operator to handle null cases
        $expectedResult = $testCase['expected'] === 'pass';
        $actualResult = $payment->validatePaymentCCV($ccv);

        echo "Testing validatePaymentCCV with CCV '{$ccv}': ";
        if ($actualResult === $expectedResult) {
            echo "Passed.<br>";
        } else {
            echo "Failed.<br>";
        }
    }

    // Function to test processPayment
    function testProcessPayment($payment, $testCase) {
        $ccv = $testCase['ccv'] ?? '';  // Convert null to an empty string to avoid type error
        $ccvDescription = $ccv === '' ? 'empty' : $ccv;
        try {
            $result = $payment->processPayment($ccv);
            $expectedPass = $testCase['expected'] === 'pass' && $result !== "Error processing payment. Please try again later." && $result !== "Invalid CCV provided.";
            $expectedFail = $testCase['expected'] === 'fail' && ($result === "Error processing payment. Please try again later." || $result === "Invalid CCV provided.");

            if ($expectedPass || $expectedFail) {
                echo "Test Case with CCV '{$ccvDescription}' Passed.<br>";
            } else {
                echo "Test Case with CCV '{$ccvDescription}' Failed.<br>";
            }
        } catch (Exception $e) {
            echo "Test Case with CCV '{$ccvDescription}' Failed. Caught unexpected error: " . $e->getMessage() . "<br>";
        }
    }

    // Execute test cases
    echo '<div>CCV Validation Test Results:<br>';
    foreach ($testCases as $testCase) {
        testValidatePaymentCCV($payment, $testCase);
    }
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Processing Tests</title>
</head>
<body>
<form method="post" action="">
    <button type="submit" name="testButton">Run Tests</button>
</form>
</body>
</html>

