<?php
include '../template/header.php';
include "../src/dbconnect.php";
include "../classes/Products.php"; // Include the Product class file

// Check if product ID is provided
if(isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    // Initialize Product class with database connection
    $productObj = new Products($pdo);

    // Retrieve product details
    $product = $productObj->getProductById($productId);

    if(!$product) {
        echo "Product not found!";
        exit; // Stop further execution
    }
} else {
    echo "Product ID is missing!";
    exit; // Stop further execution
}

// Check if form is submitted for updating product details
if(isset($_POST['update_product'])) {
    // Retrieve updated product details from the form
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stockQuantity = $_POST['stock_quantity'];
    $category = $_POST['category'];

    // Update product details in the database
    $productObj->updateProduct($productId, $name, $description, $price, $stockQuantity, $category);

    // Redirect to the product list page after updating
    header("Location: adminscrud.php");
    exit; // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <title>Edit Product</title>
</head>
<body>
<h2>Edit Product</h2>
<form action="" method="POST">
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" value="<?php echo $product->getName(); ?>" required><br>

    <label for="description">Description:</label><br>
    <textarea id="description" name="description" required><?php echo $product->getDescription(); ?></textarea><br>

    <label for="price">Price:</label><br>
    <input type="number" id="price" name="price" min="0" step="0.01" value="<?php echo $product->getPrice(); ?>" required><br>

    <label for="stock_quantity">Stock Quantity:</label><br>
    <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="<?php echo $product->getStockQuantity(); ?>" required><br>

    <label for="category">Category:</label><br>
    <select id="category" name="category" required>
        <option value="mice" <?php if($product->getCategory() == 'mice') echo 'selected'; ?>>Mice</option>
        <option value="keyboard" <?php if($product->getCategory() == 'keyboard') echo 'selected'; ?>>Keyboard</option>
        <option value="pc" <?php if($product->getCategory() == 'pc') echo 'selected'; ?>>PC</option>
        <option value="headphone" <?php if($product->getCategory() == 'headphone') echo 'selected'; ?>>Headphone</option>
    </select><br><br>

    <input type="submit" name="update_product" value="Update">
</form>
</body>
</html>

<?php
include '../template/footer.php';
?>