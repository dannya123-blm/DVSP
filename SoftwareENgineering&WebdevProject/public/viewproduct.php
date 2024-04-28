<?php
global $pdo;
include '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../classes/Products.php';

$productObj = new Products($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $_SESSION['cart'][] = $_POST['product_id'];
    // Consider adding a redirect to the cart page or a confirmation message here
}

if(isset($_GET['productId'])) {
    $productId = $_GET['productId'];
    $product = $productObj->getProductById($productId);
    $category = ucfirst(strtolower($product->getCategory()));
    $imageName = "{$category}{$productId}.jpg";

    if($product) {
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlspecialchars($product->getName()); ?></title>
            <link rel="stylesheet" href="../css/viewproduct.css">
        </head>
        <body>
        <div class="navigation-link">
            <a href="productpage.php">Back to Products</a>
        </div>
        <div class="product-page-container">
            <div class="product-image-container">
                <div class="product-main-image">
                    <img src="../images/<?php echo htmlspecialchars($imageName); ?>" alt="<?php echo htmlspecialchars($product->getName()); ?>">
                </div>
            </div>
            <div class="product-details-sidebar">
                <h1><?php echo htmlspecialchars($product->getName()); ?></h1>
                <p><?php echo htmlspecialchars($product->getDescription()); ?></p>
                <p class="price">€<?php echo htmlspecialchars($product->getPrice()); ?></p>
                <?php if ($product->getStockQuantity() > 0) { ?>
                    <form action="" method="post">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product->getProductID()); ?>">
                        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                    </form>
                <?php } else { ?>
                    <p class="stock out-of-stock">Out of Stock</p>
                <?php } ?>
            </div>
        </div>
        </body>
        </html>
        <?php
    } else {
        echo "Product not found!";
    }
} else {
    echo "Product ID not provided!";
}
?>
