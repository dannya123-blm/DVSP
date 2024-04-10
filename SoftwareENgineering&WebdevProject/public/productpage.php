<?php
include '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../classes/Products.php';

// Initialize Products class with database connection
$productObj = new Products($pdo);

?>

<link rel="stylesheet" href="../css/products.css">
<main class="container">
    <div class="filters">
        <h2>Filters</h2>
        <button>Keyboard</button>
        <button>Mouse</button>
        <button>Computers</button>
        <button>Headphones</button>
        <div class="sort-by">
            <label for="sort-by">Sort by:</label>
            <select id="sort-by">
                <option value="price-low-high">Price: Low to High</option>
                <option value="price-high-low">Price: High to Low</option>
                <option value="name-a-z">Name: A to Z</option>
                <option value="name-z-a">Name: Z to A</option>
            </select>
        </div>
    </div>

    <section class="categories top-categories">
        <div class="product-container">
            <div class="product-cards">
                <?php

                // SQL query to fetch products from the database
                $sql = "SELECT * FROM products";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                // Check if there are products
                if ($stmt->rowCount() > 0) {
                    // Output data of each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Create a new Product object
                        $product = $productObj->getProductById($row["idProducts"]);
                        $category = strtolower($product->getCategory());
                        $imageName = "{$category}{$row['idProducts']}.jpg";
                        ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="../images/<?php echo $imageName; ?>" alt="<?php echo $product->getName(); ?>">
                            </div>
                            <div class="product-details">
                                <h3><?php echo $product->getName(); ?></h3>
                                <p><?php echo $product->getDescription(); ?></p>
                                <p class="price">$<?php echo $product->getPrice(); ?></p>
                                <form action="" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $product->getProductID(); ?>">
                                    <button type="submit">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p class="no-products">No products available</p>';
                }

                ?>
            </div>
        </div>
    </section>
</main>

<?php
require "../template/footer.php";
?>
