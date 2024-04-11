<?php
// Start the session

// Include necessary files and configurations
global $pdo;
require '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../classes/Products.php';

// Initialize Products class with database connection
$productObj = new Products($pdo);

// Check if a category filter is applied
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

// Check if a search term is provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// SQL query to fetch products from the database
$sql = "SELECT * FROM products";

// If a category filter is applied, add a WHERE clause to filter products by category
if (!empty($categoryFilter)) {
    $sql .= " WHERE Category = :category";
}

// If both category filter and search term are applied, combine them with AND
if (!empty($categoryFilter) && !empty($searchTerm)) {
    $sql .= " AND Name LIKE :searchTerm";
}
// If only search term is applied, add a WHERE clause to filter products by name
elseif (!empty($searchTerm)) {
    $sql .= " WHERE Name LIKE :searchTerm";
}

$stmt = $pdo->prepare($sql);

// Bind category parameter if filter applied
if (!empty($categoryFilter)) {
    $stmt->bindParam(':category', $categoryFilter);
}

// Bind search term parameter if provided
if (!empty($searchTerm)) {
    $searchParam = "%$searchTerm%"; // Adjusted the search term to include wildcard characters (%)
    $stmt->bindParam(':searchTerm', $searchParam);
}

$stmt->execute();

// Check if the product is added to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    // Store the product ID in the session
    $_SESSION['cart'][] = $_POST['product_id'];
}
?>

<link rel="stylesheet" href="../css/products.css">
<script src="../js/filter.js"></script>

<main class="container">
    <div class="filters">
        <h2>Filters</h2>
        <a href="?category=" class="filter-btn">All</a>
        <a href="?category=Keyboard" class="filter-btn">Keyboard</a>
        <a href="?category=Mice" class="filter-btn">Mice</a>
        <a href="?category=PC" class="filter-btn">PC</a>
        <a href="?category=Headphone" class="filter-btn">Headphones</a>
        <a href="?category=Controller" class="filter-btn">Controllers</a>


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
                                <!-- Modified the form to include a hidden input for product ID -->
                                <form action="" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $product->getProductID(); ?>">
                                    <button type="submit" class="add-to-cart-btn">Add to Cart</button>
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
