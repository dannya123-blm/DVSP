<?php
require '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../classes/Products.php';

// Initialize Products class with database connection
$productObj = new Products($pdo);
?>

<!-- Start HTML -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DVS Expansion HomePage</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/random.css">
    <script src="../js/home.js"></script>
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
                <div class="category-item BestDeals">
                    <a href="productpage.php?category=">
                        <img src="../images/category1.png" alt="Category 1">
                        <span class="category-name">DEALS</span>
                    </a>
                </div>

                <div class="category-item Controllers">
                    <a href="productpage.php?category=Controller">
                        <img src="../images/category2.jpeg" alt="Category 2">
                        <span class="category-name">CONTROLLER</span>
                    </a>
                </div>

                <div class="category-item Keyboard">
                    <a href="productpage.php?category=Keyboard">
                        <img src="../images/category3.jpeg" alt="Category 3">
                        <span class="category-name">KEYBOARD</span>
                    </a>
                </div>

                <div class="category-item Mice">
                    <a href="productpage.php?category=Mice">
                        <img src="../images/category4.jpeg" alt="Category 4">
                        <span class="category-name">MOUSES</span>
                    </a>
                </div>

                <div class="category-item Computers">
                    <a href="productpage.php?category=PC">
                        <img src="../images/category5.jpeg" alt="Category 5">
                        <span class="category-name">COMPUTERS</span>
                    </a>
                </div>

                <div class="category-item Headsets">
                    <a href="productpage.php?category=Headphone">
                        <img src="../images/category6.jpeg" alt="Category 6">
                        <span class="category-name">HEADPHONES</span>
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
            // Get a random selection of products (limit to 4)
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
                        <form action="cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product->getProductID(); ?>">
                            <button type="submit">Buy Now</button>
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
