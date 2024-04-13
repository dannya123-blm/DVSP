<?php
// Start the session
session_start();

// Check if user is logged in as admin, if not, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Set the variable for admin login status
$isAdminLoggedIn = true;

// Include the necessary class definitions
require_once '../classes/User.php';
require_once '../classes/Admin.php';
include '../classes/Products.php';

// Instantiate an Admin object
$admin = new Admin();
$admin->setUserID($_SESSION['admin_id']);

// Continue with the rest of your code
include "../src/dbconnect.php";

// Instantiate a Products object
$products = new Products($pdo);

// Handle product deletion
if(isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];
    $products->deleteProduct($productId);
}

// Handle product addition
if(isset($_POST['add_product'])) {
    $idAdmin = $_SESSION['admin_id'];
    $idProducts = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stockQuantity = $_POST['stock_quantity'];
    $category = $_POST['category'];
    $products->addProduct($idAdmin, $idProducts, $name, $description, $price, $stockQuantity, $category);
}

// Fetch all products
$allProducts = $products->getAllProducts();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DVS Expansion</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dropdown.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<div class="background-banner"></div>
<header>
    <div class="logoscontainer">
        <div class="logo">
            <img src="../images/logo.png" alt="DVS Expansion">
        </div>
        <nav>
            <ul>
                <li><a href="../public/index.php">HOME</a></li>
                <li><a href="../public/productpage.php">PRODUCT</a></li>
                <li><a href="#">CONTACT US</a></li>
                <li><a href="../public/findus.php">FIND US</a></li>
            </ul>
        </nav>
        <br>

        <div class="account-basket">
            <div class="dropdown">
                <?php
                if ($isAdminLoggedIn || $isLoggedIn) {
                    echo '<img src="../images/login.png" alt="Dropdown">';
                    echo '<div class="dropdown-content">';
                    echo '<a href="../public/dashboard.php">Dashboard</a>';
                    echo '<a href="../public/payment.php">Payment</a>';
                    if ($isAdminLoggedIn) {
                        echo '<a href="../administrator/adminscrud.php">Connections</a>';
                    }
                    echo '<a href="../public/logout.php">Logout</a>';
                    echo '</div>';
                } else {
                    echo '<a href="../public/login.php"><img src="../images/login.png" alt="Login"></a>';
                }
                ?>
                <div class="loginButton">
                    <?php
                    if ($isAdminLoggedIn || $isLoggedIn) {
                        echo '<a href="../public/logout.php">Logout</a>';
                    } else {
                        echo '<a href="../public/login.php">Login</a>';
                    }
                    ?>
                </div>
            </div>
            <div>
                <a href="../public/cart.php"><img src="../images/cart.png" alt="Basket"></a>
                <div class="cartButton"><a href="../public/cart.php">Cart</a></div>
            </div>
        </div>
        <div class="searchBarButton">
            <input type="text" placeholder="Search">
            <button type="button">Search</button>
        </div>
    </div>
</header>

<?php
// Display products
if (!empty($allProducts)) {
    echo "<table border='1'>";
    echo "<tr><th>Product ID</th><th>Name</th><th>Description</th><th>Price</th><th>Stock Quantity</th><th>Category</th><th>Action</th></tr>";
    foreach ($allProducts as $product) {
        echo "<tr>";
        echo "<td>" . $product->getProductID() . "</td>";
        echo "<td>" . $product->getName() . "</td>";
        echo "<td>" . $product->getDescription() . "</td>";
        echo "<td>" . $product->getPrice() . "</td>";
        echo "<td>" . $product->getStockQuantity() . "</td>";
        echo "<td>" . $product->getCategory() . "</td>";
        echo "<td>
                <form class='product-form' method='post' style='display: inline;'>
                    <input type='hidden' name='product_id' value='" . $product->getProductID() . "'>
                    <input type='submit' name='delete_product' value='Delete'>
                </form>
                <form class='product-form' method='get' action='adminedit.php' style='display: inline;'>
                    <input type='hidden' name='product_id' value='" . $product->getProductID() . "'>
                    <input type='submit' value='Edit'>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No products found!";
}
?>

<h2>Add Product</h2>
<form action="" method="POST">
    <label for="product_id">Product ID:</label><br>
    <input type="number" id="product_id" name="product_id" required><br>

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
        <option value="Mice">Mouse</option>
        <option value="Keyboard">Keyboard</option>
        <option value="Pc">PC</option>
        <option value="Headphone">Headphones</option>
        <option value="Controllers">Controllers</option>
    </select><br><br>

    <input type="submit" name="add_product" value="Submit">
</form>
</body>
</html>

