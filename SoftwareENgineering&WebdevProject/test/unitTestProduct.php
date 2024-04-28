<?php

require '../classes/Products.php';

// Mocking a PDO object for database interaction
class PDODummy extends PDO {
    public function __construct() {}
}

// Define a simple assertion function that outputs HTML
function assertEqual($expected, $actual, $message) {
    if ($expected !== $actual) {
        echo "<div style='color: red;'>FAIL: $message (Expected: '$expected', Actual: '$actual')</div>";
    } else {
        echo "<div style='color: green;'>PASS: $message</div>";
    }
}

// Create a Products instance with a dummy PDO object and mock data
$pdo = new PDODummy();
$row = [
    'idProducts' => 1,
    'Name' => 'Example Product',
    'Description' => 'Example Description',
    'Price' => 99.99,
    'StockQuantity' => 100,
    'Category' => 'Example',
    'idAdmin' => 1
];
$products = new Products($pdo, $row);

// Output HTML header to make output nicer in browser
echo "<!DOCTYPE html><html><head><title>Test Results</title></head><body>";
echo "<h1>Products Class Test Results</h1>";

// Test Getters
assertEqual('Example Product', $products->getName(), 'Testing getName');
assertEqual('Example Description', $products->getDescription(), 'Testing getDescription');
assertEqual(99.99, $products->getPrice(), 'Testing getPrice');
assertEqual(100, $products->getStockQuantity(), 'Testing getStockQuantity');
assertEqual('Example', $products->getCategory(), 'Testing getCategory');

// Test Setters
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

echo "</body></html>";

