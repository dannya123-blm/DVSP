<?php
global $pdo;
require '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../classes/Products.php';

$productObj = new Products($pdo);

// Check if the product is added to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    // Store the product ID in the session
    $_SESSION['cart'][] = $_POST['product_id'];
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DVS Expansion HomePage</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/random.css">
</head>
<body>
<main>
    <section class="banner" style="background-image: url('../images/test2.png');">
        <div class="bannercontainer">
            <div class="bannercontent">
                <a href="productpage.php" class="productbutton">Shop Now</a>
            </div>
        </div>
    </section>
    <h2>Top Categories</h2>
    <section class="categories left-to-right-categories">
        <div class="container">
            <div class="category-list">
                <div class="category-item Controllers">
                    <a href="productpage.php?category=Controllers">
                        <img src="../images/category2.jpeg" alt="Category 2">
                    </a>
                </div>

                <div class="category-item Keyboard">
                    <a href="productpage.php?category=Keyboard">
                        <img src="../images/category3.jpeg" alt="Category 3">
                    </a>
                </div>

                <div class="category-item Mice">
                    <a href="productpage.php?category=Mice">
                        <img src="../images/category6.jpeg" alt="Category 4">
                    </a>
                </div>

                <div class="category-item Computers">
                    <a href="productpage.php?category=PC">
                        <img src="../images/category5.jpeg" alt="Category 5">
                    </a>
                </div>

                <div class="category-item Headsets">
                    <a href="productpage.php?category=Headphone">
                        <img src="../images/category4.jpeg" alt="Category 6">
                    </a>
                </div>
            </div>
        </div>
    </section>
    <br>
    <br>
    <section class="random-products">
        <h2>Random Products</h2>
        <div class="product-container">
            <?php
            $randomProducts = $productObj->getRandomProducts(4);

            foreach ($randomProducts as $product) {
                $imageName = strtolower($product->getCategory()) . $product->getProductID() . '.jpg';
                ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="../images/<?php echo $imageName; ?>" alt="<?php echo htmlspecialchars($product->getName()); ?>">
                    </div>
                    <div class="product-details">
                        <h3><?php echo htmlspecialchars($product->getName()); ?></h3>
                        <p><?php echo htmlspecialchars($product->getDescription()); ?></p>
                        <p class="price">€<?php echo number_format($product->getPrice(), 2); ?></p>
                        <form action="" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product->getProductID(); ?>">
                            <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
</main>
</body>
</html>
<?php require '../template/footer.php'; ?>
