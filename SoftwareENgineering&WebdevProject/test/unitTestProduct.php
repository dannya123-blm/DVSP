<?php
// Include the Products class definition file.
require '../classes/Products.php';

// A mock class PDODummy that simulates the PDO object for database interactions.
class PDODummy extends PDO {
    // Empty constructor since no actual database connection is required.
    public function __construct() {}
}

// Define a function to assert and display the equality of expected and actual values in HTML.
function assertEqual($expected, $actual, $message) {
    // Conditionally display test results in colored HTML based on comparison outcome.
    if ($expected !== $actual) {
        echo "<div style='color: red;'>FAIL: $message (Expected: '$expected', Actual: '$actual')</div>";
    } else {
        echo "<div style='color: green;'>PASS: $message</div>";
    }
}

// Create an instance of PDODummy to pass into the Products class.
$pdo = new PDODummy();
// Mock data representing a product record.
$row = [
    'idProducts' => 1,
    'Name' => 'Example Product',
    'Description' => 'Example Description',
    'Price' => 99.99,
    'StockQuantity' => 100,
    'Category' => 'Example',
    'idAdmin' => 1
];
// Instantiate the Products class with the dummy PDO object and the mock data.
$products = new Products($pdo, $row);

// Start HTML output for test results.
echo "<!DOCTYPE html><html><head><title>Test Results</title></head><body>";
echo "<h1>Products Class Test Results</h1>";

// Test getter methods and assert expected results.
assertEqual('Example Product', $products->getName(), 'Testing getName');
assertEqual('Example Description', $products->getDescription(), 'Testing getDescription');
assertEqual(99.99, $products->getPrice(), 'Testing getPrice');
assertEqual(100, $products->getStockQuantity(), 'Testing getStockQuantity');
assertEqual('Example', $products->getCategory(), 'Testing getCategory');

// Test setter methods and verify they correctly update the properties.
$products->setName('New Product');
assertEqual('New Product', $products->getName(), 'Testing setName');

$products->setDescription('New Description');
assertEqual('New Description', $products->getDescription(), 'Testing setDescription');

$products->setPrice(149.99);
assertEqual(149.99, $products->getPrice(), 'Testing setPrice');

$products->setStockQuantity(50);
assertEqual(50, $products->getStockQuantity(), 'Testing setStockQuantity');

$products->setCategory('New Category');
assertEqual('New Category', $products->getCategory(), 'Testing setCategory');

// End HTML document.
echo "</body></html>";
