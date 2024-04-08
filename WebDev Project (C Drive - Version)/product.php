<?php
require "header.php";
require_once "databaseconfig.php";
require_once "classes/Products.php";
?>

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
        <div class="container">
            <div class="product-cards">
                <?php

                // SQL query to fetch products from the database
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                // Check if there are products
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        // Create a new Product object
                        $product = new \classes\Products();
                        // Set product properties from database
                        $product->setProductID($row["idProducts"]);
                        $product->setName($row["Name"]);
                        $product->setDescription($row["Description"]);
                        $product->setPrice($row["Price"]);
                        $product->setStockQuantity($row["StockQuantity"]);
                        $product->setCategory($row["Category"]);
                        $product->setAdminID($row["idAdmin"]);
                        ?>
                        <div class="product-card">
                            <div class="product-image">
                                <!-- Placeholder image or actual product image -->
                            </div>
                            <div class="product-details">
                                <h3><?php echo $product->getName(); ?></h3>
                                <p><?php echo $product->getDescription(); ?></p>
                                <p class="price">$<?php echo $product->getPrice(); ?></p>
                                <button onclick="addToCart(<?php echo $product->getProductID(); ?>)">Add to Cart</button>
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
require "footer.php";
?>

<script>
    function addToCart(productId) {
        // Send an AJAX request to add the product to the cart
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "add_to_cart.php?product_id=" + productId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Redirect to the cart page after adding the product to the cart
                window.location.href = "cart.php";
            }
        };
        xhr.send();
    }
</script>
