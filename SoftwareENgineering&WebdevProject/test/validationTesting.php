<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../classes/Payment.php';
    require_once '../classes/Products.php';
    require_once '../classes/User.php';
    include '../src/dbconnect.php';

    // Instantiate classes
    $payment = new Payment($pdo);
    $products = new Products($pdo);
    $user = new User($pdo);

    $paymentTestCases = [
        ['ccv' => '1234', 'expected' => 'pass'],
        ['ccv' => 'abc', 'expected' => 'fail'],
        ['ccv' => '12', 'expected' => 'fail'],
        ['ccv' => '12345', 'expected' => 'fail'],
        ['ccv' => '', 'expected' => 'fail'],
        ['ccv' => null, 'expected' => 'fail']
    ];

    $productTestCases = [
        ['id' => 1, 'expectedType' => 'object', 'expectedProperties' => ['Name' => 'Example Product', 'Price' => 29.99]],
        ['id' => -1, 'expectedType' => 'null'],
        ['id' => 0, 'expectedType' => 'null'],
        ['id' => "ABC", 'expectedType' => 'null'],
        ['id' => 999999999, 'expectedType' => 'null'],
        ['id' => "1; DROP TABLE Products;", 'expectedType' => 'error'],
        ['id' => null, 'expectedType' => 'error'],
        ['id' => "", 'expectedType' => 'null']
    ];

    $userTestCases = [
        ['username' => 'newuser123', 'password' => 'Test@1234', 'email' => 'testuser@example.com', 'mobileNumber' => '1234567890', 'address' => '123 Main St', 'expected' => 'pass'],
        ['username' => 'newuser123', 'password' => 'Test@1234', 'email' => 'testuser2@example.com', 'mobileNumber' => '1234567890', 'address' => '123 Main St', 'expected' => 'fail'],
        ['username' => 'newuser124', 'password' => 'Test@1234', 'email' => 'testuser@example.com', 'mobileNumber' => '1234567890', 'address' => '123 Main St', 'expected' => 'fail']
    ];

    function testUpdateProduct($products) {
        echo "<h3>Testing Product Update</h3>";
        try {
            // Assume product ID 1 exists and updating its details
            $products->updateProduct(1, "Updated Name", "Updated Description", 100.99, 60, "Updated Category");
            echo "<p>Product update test passed: Product details updated successfully.</p>";
        } catch (Exception $e) {
            echo "<p>Product update test failed: " . $e->getMessage() . "</p>";
        }
    }

    // Define test functions for Payment
    function testValidatePaymentCCV($payment, $testCase) {
        $ccv = $testCase['ccv'] ?? '';
        $expectedResult = $testCase['expected'] === 'pass';
        $actualResult = $payment->validatePaymentCCV($ccv);

        echo "<p>Testing validatePaymentCCV with CCV '{$ccv}': ";
        if ($actualResult === $expectedResult) {
            echo "Passed.</p>";
        } else {
            echo "Failed.</p>";
        }
    }

    function testProcessPayment($payment, $testCase) {
        $ccv = $testCase['ccv'] ?? '';
        $ccvDescription = $ccv === '' ? 'empty' : $ccv;
        try {
            $result = $payment->processPayment($ccv);
            $expectedPass = $testCase['expected'] === 'pass' && $result !== "Error processing payment. Please try again later." && $result !== "Invalid CCV provided.";
            $expectedFail = $testCase['expected'] === 'fail' && ($result === "Error processing payment. Please try again later." || $result === "Invalid CCV provided.");

            echo "<p>Test Case with CCV '{$ccvDescription}': ";
            if ($expectedPass || $expectedFail) {
                echo "Passed.</p>";
            } else {
                echo "Failed.</p>";
            }
        } catch (Exception $e) {
            echo "<p>Test Case with CCV '{$ccvDescription}' Failed. Caught unexpected error: " . $e->getMessage() . "</p>";
        }
    }

    // Define test functions for Products
    function testGetProductById($products, $testCase) {
        $idDescription = is_numeric($testCase['id']) ? $testCase['id'] : 'special';
        try {
            $result = $products->getProductById($testCase['id']);
            $resultType = is_object($result) ? 'object' : 'null';

            echo "<p>Test Case with ID {$idDescription}: ";
            if ($testCase['expectedType'] === $resultType) {
                if ($resultType === 'object') {
                    $propertyMismatch = false;
                    foreach ($testCase['expectedProperties'] as $key => $value) {
                        if ($result->$key != $value) {
                            $propertyMismatch = true;
                            break;
                        }
                    }
                    if (!$propertyMismatch) {
                        echo "Passed.</p>";
                    } else {
                        echo "Failed - Object properties do not match expected values.</p>";
                    }
                } else {
                    echo "Passed.</p>";
                }
            } else {
                echo "Failed - Expected {$testCase['expectedType']} got {$resultType}.</p>";
            }
        } catch (Exception $e) {
            echo "<p>Test Case with ID {$idDescription} Failed. Caught expected error: {$e->getMessage()}.</p>";
        }
    }

    // Define test functions for User
    function testRegisterUser($user, $testCase) {
        echo "<p>Testing User Registration with username '{$testCase['username']}' and email '{$testCase['email']}': ";
        try {
            $user->registerUser($testCase['username'], $testCase['password'], $testCase['email'], $testCase['mobileNumber'], $testCase['address']);
            echo ($testCase['expected'] === 'pass') ? "Passed.</p>" : "Failed (was supposed to fail).</p>";
        } catch (Exception $e) {
            echo ($testCase['expected'] === 'fail') ? "Passed (expected failure): {$e->getMessage()}</p>" : "Failed: {$e->getMessage()}</p>";
        }
    }

    echo '<h1>Payment Validation Tests</h1>';
    foreach ($paymentTestCases as $testCase) {
        testValidatePaymentCCV($payment, $testCase);
    }

    echo '<h1>Payment Processing Tests</h1>';
    foreach ($paymentTestCases as $testCase) {
        testProcessPayment($payment, $testCase);
    }

    echo '<h1>Product Tests</h1>';
    testUpdateProduct($products);
    foreach ($productTestCases as $testCase) {
        testGetProductById($products, $testCase);
    }

    echo '<h1>User Registration Tests</h1>';
    foreach ($userTestCases as $testCase) {
        testRegisterUser($user, $testCase);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Combined Tests</title>
</head>
<body>
<form method="post" action="">
    <button type="submit" name="testButton">Run Tests</button>
</form>
</body>
</html>
