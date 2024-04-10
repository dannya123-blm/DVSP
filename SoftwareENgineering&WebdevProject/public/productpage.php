<?php

// Check if the product is added to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    // Store the product ID in the session
    $_SESSION['cart'][] = $_POST['product_id'];
    // Redirect to prevent form resubmission
    header("Location: cart.php");
    exit();
}

global $pdo;
include '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../classes/Products.php';

// Initialize Products class with database connection
$productObj = new Products($pdo);

// Check if a category filter is applied
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

// SQL query to fetch products from the database
$sql = "SELECT * FROM products";
// If a category filter is applied, add a WHERE clause to filter products by category
if (!empty($categoryFilter)) {
    $sql .= " WHERE Category = :category";
}
$stmt = $pdo->prepare($sql);
if (!empty($categoryFilter)) {
    $stmt->bindParam(':category', $categoryFilter);
}
$stmt->execute();

?>

<link rel="stylesheet" href="../css/products.css">
<script src="../js/filter.js"></script>
<main class="container">
    <div class="filters">
        <h2>Filters</h2>
        <a href="?category=" class="filter-btn">All</a>
        <a href="?category=Keyboard" class="filter-btn">Keyboard</a>
        <a href="?category=Mice" class="filter-btn" data-category="Mouse">Mouse</a>
        <a href="?category=PC" class="filter-btn" data-category="PC">PC</a>
        <a href="?category=Headphone" class="filter-btn" data-category="Headphones">Headphones</a>

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
                // Check if there are products
                if ($stmt->rowCount() > 0) {
                    // Output data of each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Create a new Product object
                        $product = $productObj->getProductById($row["idProducts"]);
                        $category = ucfirst(strtolower($product->getCategory())); // Capitalize the first letter
                        $imageName = "{$category}{$row['idProducts']}.jpg";
                        ?>
                        <div class="product-card" data-category="<?php echo $category; ?>">
                            <div class="product-image">
                                <img src="../images/<?php echo $imageName; ?>" alt="<?php echo $product->getName(); ?>">
                            </div>
                            <div class="product-details">
                                <h3><?php echo $product->getName(); ?></h3>
                                <p><?php echo $product->getDescription(); ?></p>
                                <p class="price">€<?php echo $product->getPrice(); ?></p>
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
