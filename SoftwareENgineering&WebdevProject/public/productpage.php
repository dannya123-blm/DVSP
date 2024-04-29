<?php
global $pdo;
include '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../classes/Products.php';

$productObj = new Products($pdo);
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$sql = "SELECT * FROM Products";
$conditions = [];
$params = [];

if (!empty($categoryFilter)) {
    $conditions[] = "Category = :category";
    $params[':category'] = $categoryFilter;
}
if (!empty($searchTerm)) {
    $conditions[] = "Name LIKE :searchTerm";
    $params[':searchTerm'] = "%$searchTerm%";
}
if ($conditions) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

switch ($sort) {
    case 'price-high-low':
        $sql .= " ORDER BY Price DESC";
        break;
    case 'price-low-high':
        $sql .= " ORDER BY Price ASC";
        break;
    case 'name-a-z':
        $sql .= " ORDER BY Name ASC";
        break;
    case 'name-z-a':
        $sql .= " ORDER BY Name DESC";
        break;
}

$stmt = $pdo->prepare($sql);
foreach ($params as $key => &$val) {
    $stmt->bindParam($key, $val);
}
$stmt->execute();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $_SESSION['cart'][] = $_POST['product_id'];
    $productId = $_POST['product_id'];
    $updateStockStmt = $pdo->prepare("UPDATE Products SET StockQuantity = StockQuantity - 1 WHERE idProducts = :product_id");
    $updateStockStmt->bindParam(':product_id', $productId);
    $updateStockStmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="../css/products.css">
</head>
<body>
<main class="container">
    <div class="filters">
        <h2>Filters</h2>
        <a href="?category=" class="filter-btn">All</a>
        <a href="?category=Keyboard" class="filter-btn">Keyboard</a>
        <a href="?category=Mice" class="filter-btn">Mice</a>
        <a href="?category=PC" class="filter-btn">PC</a>
        <a href="?category=Headphone" class="filter-btn">Headphones</a>
        <a href="?category=Controllers" class="filter-btn">Controllers</a>

        <form method="GET">
            <div class="sort-by">
                <label for="sort-by">Sort by:</label>
                <select id="sort-by" name="sort" onchange="this.form.submit()">
                    <option value="price-low-high" <?= $sort == 'price-low-high' ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price-high-low" <?= $sort == 'price-high-low' ? 'selected' : '' ?>>Price: High to Low</option>
                    <option value="name-a-z" <?= $sort == 'name-a-z' ? 'selected' : '' ?>>Name: A to Z</option>
                    <option value="name-z-a" <?= $sort == 'name-z-a' ? 'selected' : '' ?>>Name: Z to A</option>
                </select>
            </div>

            <input type="hidden" name="category" value="<?= htmlspecialchars($categoryFilter) ?>">
            <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
        </form>
    </div>

    <div class="product-container">
        <div class="product-cards">
            <?php
            if ($stmt->rowCount() > 0)
            {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $product = $productObj->getProductById($row["idProducts"]);
                    $category = ucfirst(strtolower($product->getCategory()));
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
                            <?php if ($product->getStockQuantity() > 0) { ?>
                                <p class="stock">Stock: <?php echo $product->getStockQuantity(); ?></p>
                                <form action="" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $product->getProductID(); ?>">
                                    <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                                </form>
                                <br>
                                <form action="viewproduct.php" method="get">
                                    <input type="hidden" name="productId" value="<?php echo $product->getProductID(); ?>">
                                    <button type="submit" class="view-more-btn">View More</button>
                                </form>

                            <?php } else { ?>
                                <p class="stock out-of-stock">Out of Stock</p>
                            <?php } ?>
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
</main>
</body>
</html>
<?php
include '../template/footer.php';
?>
