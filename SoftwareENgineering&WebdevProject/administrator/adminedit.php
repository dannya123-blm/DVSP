<?php
// This code is based on the assignment PHP : CRUD, by Robert Smith;

global $pdo;
include '../template/header.php';
include "../src/dbconnect.php";
include "../classes/Products.php";

if(isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    $productObj = new Products($pdo);
    $product = $productObj->getProductById($productId);

    if(!$product) {
        echo "Product not found!";
        exit;
    }
} else {
    echo "Product ID is missing!";
    exit;
}

if(isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stockQuantity = $_POST['stock_quantity'];
    $category = $_POST['category'];
    $productObj->updateProduct($productId, $name, $description, $price, $stockQuantity, $category);
    header("Location: adminscrud.php");
    exit;
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
        <option value="Mice" <?php if($product->getCategory() == 'Mice') echo 'selected'; ?>>Mouse</option>
        <option value="Keyboard" <?php if($product->getCategory() == 'Keyboard') echo 'selected'; ?>>Keyboard</option>
        <option value="Pc" <?php if($product->getCategory() == 'Pc') echo 'selected'; ?>>PC</option>
        <option value="Headphone" <?php if($product->getCategory() == 'Headphone') echo 'selected'; ?>>Headphones</option>
        <option value="Controllers" <?php if($product->getCategory() == 'Controllers') echo 'selected'; ?>>Controllers</option>
    </select><br><br>

    <input type="submit" name="update_product" value="Update">
</form>
</body>
</html>

<?php
include '../template/footer.php';
?>
