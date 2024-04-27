<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../classes/Products.php';
    include '../src/dbconnect.php';

    $products = new Products($pdo);

    // Define your test cases
    $testCases = [
        ['id' => 1, 'expected' => 'object'],    // Assuming 1 is a valid product ID
        ['id' => -1, 'expected' => 'null'],       // Negative ID, expected to fail
        ['id' => 0, 'expected' => 'null'],        // Zero ID, expected to fail
        ['id' => "ABC", 'expected' => 'null'],    // Non-integer ID, expected to fail
        ['id' => 999999999, 'expected' => 'null'],// ID that doesn't exist, expected to fail
        ['id' => null, 'expected' => 'error'],    // No input given, expected to error out
    ];

    // Function to test getProductById
    function testGetProductById($products, $testCase) {
        $idDescription = $testCase['id'] === null ? 'null' : $testCase['id'];
        try {
            $result = $products->getProductById($testCase['id']);
            if (($testCase['expected'] === 'object' && is_object($result)) ||
                ($testCase['expected'] === 'null' && $result === null)) {
                echo "Test Case with ID {$idDescription} Passed.<br>";
            } else {
                echo "Test Case with ID {$idDescription} Failed.<br>";
            }
        } catch (Exception $e) {
            if ($testCase['expected'] === 'error') {
                echo "Test Case with ID {$idDescription} Passed. Caught expected error: " . $e->getMessage() . "<br>";
            } else {
                echo "Test Case with ID {$idDescription} Failed. Unexpected error: " . $e->getMessage() . "<br>";
            }
        }
    }

    // Execute test cases
    echo '<div>Test Results:<br>';
    foreach ($testCases as $testCase) {
        testGetProductById($products, $testCase);
    }
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Tests</title>
</head>
<body>
<form method="post" action="">
    <button type="submit" name="testButton">Run Tests</button>
</form>
</body>
</html>
// Only run the tests if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include the necessary files
    require_once '../classes/Products.php';
    include '../src/dbconnect.php'; // Ensure this file correctly sets up the $pdo object

    // Create an instance of the Products class
    $products = new Products($pdo);

    // Define your test cases
    $testCases = [
        ['id' => 1, 'expected' => 'object'],    // Assuming 1 is a valid product ID
        ['id' => -1, 'expected' => 'null'],       // Negative ID, expected to fail
        ['id' => 0, 'expected' => 'null'],        // Zero ID, expected to fail
        ['id' => "ABC", 'expected' => 'null'],    // Non-integer ID, expected to fail
        ['id' => 999999999, 'expected' => 'null'],// ID that doesn't exist, expected to fail
        ['id' => null, 'expected' => 'error'],    // No input given, expected to error out
    ];

    // Function to test getProductById
    function testGetProductById($products, $testCase) {
        $idDescription = $testCase['id'] === null ? 'null' : $testCase['id'];
        try {
            $result = $products->getProductById($testCase['id']);
            if (($testCase['expected'] === 'object' && is_object($result)) ||
                ($testCase['expected'] === 'null' && $result === null)) {
                echo "Test Case with ID {$idDescription} Passed.<br>";
            } else {
                echo "Test Case with ID {$idDescription} Failed.<br>";
            }
        } catch (Exception $e) {
            if ($testCase['expected'] === 'error') {
                echo "Test Case with ID {$idDescription} Passed. Caught expected error: " . $e->getMessage() . "<br>";
            } else {
                echo "Test Case with ID {$idDescription} Failed. Unexpected error: " . $e->getMessage() . "<br>";
            }
        }
    }

    // Execute test cases
    echo '<div>Test Results:<br>';
    foreach ($testCases as $testCase) {
        testGetProductById($products, $testCase);
    }
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Tests</title>
</head>
<body>
<form method="post" action="">
    <button type="submit" name="testButton">Run Tests</button>
</form>
</body>
</html>

