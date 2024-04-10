<?php
include '../template/header.php';
include "../src/dbconnect.php"; // Assuming this includes your PDO connection as $pdo

// Process product deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];
    $deleteSql = "DELETE FROM Products WHERE idProducts = :productId";
    $stmt = $pdo->prepare($deleteSql);
    $stmt->execute(['productId' => $productId]);
}

// Process product addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stockQuantity = $_POST['stock_quantity'];
    $category = $_POST['category'];

    // Assuming you have a way to retrieve the admin ID (e.g., from session)
    // Replace 1 with the actual admin ID value
    $adminId = 1; // Example admin ID

    $insertSql = "INSERT INTO Products (Name, Description, Price, StockQuantity, Category, idAdmin) 
                  VALUES (:name, :description, :price, :stockQuantity, :category, :idAdmin)";

    $stmt = $pdo->prepare($insertSql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price, PDO::PARAM_INT);
    $stmt->bindParam(':stockQuantity', $stockQuantity, PDO::PARAM_INT);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':idAdmin', $adminId, PDO::PARAM_INT);

    try {
        $stmt->execute();
        echo "Product added successfully.";
    } catch (PDOException $e) {
        echo "Error adding product: " . $e->getMessage();
    }
}

// Display products table
$sql = "SELECT * FROM Products";
$stmt = $pdo->query($sql);

if ($stmt->rowCount() > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Product ID</th><th>Name</th><th>Description</th><th>Price</th><th>Stock Quantity</th><th>Category</th><th>Action</th></tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row["idProducts"] . "</td>";
        echo "<td>" . $row["Name"] . "</td>";
        echo "<td>" . $row["Description"] . "</td>";
        echo "<td>" . $row["Price"] . "</td>";
        echo "<td>" . $row["StockQuantity"] . "</td>";
        echo "<td>" . $row["Category"] . "</td>";
        echo "<td><form method='post'><input type='hidden' name='product_id' value='" . $row["idProducts"] . "'><input type='submit' name='delete_product' value='Delete'></form></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No products found!";
}

echo "<br/>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
<h2>Add Product</h2>
<form action="" method="POST">
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

    <input type="submit" value="Submit">
</form>
</body>
</html>

<?php
include '../template/footer.php';
?>
