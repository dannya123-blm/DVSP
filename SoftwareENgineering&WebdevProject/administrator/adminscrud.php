<?php
// Include necessary files
include '../template/header.php';
include "../src/dbconnect.php";
include "../classes/Products.php"; // Include the Products class file

// Create an instance of the Products class with the database connection parameter
$productObj = new Products($pdo);

// Check if product deletion form is submitted
if(isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];
    // Build query to delete product
    $deleteSql = "DELETE FROM Products WHERE idProducts = :productId";
    // Prepare and execute delete query
    $stmt = $pdo->prepare($deleteSql);
    $stmt->execute(['productId' => $productId]);
}

// Check if product addition form is submitted
if(isset($_POST['add_product'])) {
    $idAdmin = 1; // Set idAdmin value to 1
    $idProducts = $_POST['product_id']; // Get product ID from the form
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stockQuantity = $_POST['stock_quantity'];
    $category = $_POST['category'];

    // Build query to insert product
    $insertSql = "INSERT INTO Products (idAdmin, idProducts, Name, Description, Price, StockQuantity, Category) VALUES (:idAdmin, :idProducts, :name, :description, :price, :stockQuantity, :category)";
    // Prepare and execute insert query
    $stmt = $pdo->prepare($insertSql);
    $stmt->execute([
        'idAdmin' => $idAdmin,
        'idProducts' => $idProducts,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'stockQuantity' => $stockQuantity,
        'category' => $category
    ]);
}

// Prepare and execute query to select all products
$sql = "SELECT * FROM Products";
$stmt = $pdo->query($sql);

// Check if there are any results
if ($stmt->rowCount() > 0) {
    // Output table header
    echo "<table border='1'>";
    echo "<tr><th>Product ID</th><th>Name</th><th>Description</th><th>Price</th><th>Stock Quantity</th><th>Category</th><th>Action</th></tr>";

    // Loop through each row
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Output table row
        echo "<tr>";
        echo "<td>" . $row["idProducts"] . "</td>";
        echo "<td>" . $row["Name"] . "</td>";
        echo "<td>" . $row["Description"] . "</td>";
        echo "<td>" . $row["Price"] . "</td>";
        echo "<td>" . $row["StockQuantity"] . "</td>";
        echo "<td>" . $row["Category"] . "</td>";
        echo "<td>
                <form class='product-form' method='post' style='display: inline;'>
                    <input type='hidden' name='product_id' value='" . $row["idProducts"] . "'>
                    <input type='submit' name='delete_product' value='Delete'>
                </form>
                <form class='product-form' method='get' action='adminedit.php' style='display: inline;'>
                    <input type='hidden' name='product_id' value='" . $row["idProducts"] . "'>
                    <input type='submit' value='Edit'>
                </form>
              </td>";
        echo "</tr>";
    }

    // Close the table
    echo "</table>";
} else {
    // Output message if no products found
    echo "No products found!";
}

echo "<br/>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <title>Add Product</title>
</head>
<body>
<h2>Add Product</h2>
<form action="" method="POST">
    <label for="product_id">Product ID:</label><br>
    <input type="number" id="product_id" name="product_id" required><br>

    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" required><br>

    <label for="description">Description:</label><br>
    <textarea id="description" name="description" required></textarea><br>

    <label for="price">Price:</label><br>
    <input type="number" id="price" name="price" min="0" step="0.01" required><br>

    <label for="stock_quantity">Stock Quantity:</label><br>
    <input type="number" id="stock_quantity" name="stock_quantity" min="0" required><br>

    <label for="category">Category:</label><br>
    <select id="category" name="category" required>
        <option value="mice">Mice</option>
        <option value="keyboard">Keyboard</option>
        <option value="pc">PC</option>
        <option value="headphone">Headphone</option>
    </select><br><br>

    <input type="submit" name="add_product" value="Submit">
</form>
</body>
</html>
