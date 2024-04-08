<?php
    require 'header.php';
?>
<header> <link rel="stylesheet" href="css/index.css"></header>
<main>
    <section class="hero" style="background-image: url('images/pcsetup.png');">
        <div class="container">
            <h1>Looking for something?</h1>
            <p>Shop our wide range of electronics and<br> accessories at unbeatable prices!</p>

            <a href="product.php" class="button">Shop Now</a>
        </div>
    </section>

 <Center><h2>Top Categories</h2></Center>
    <section class="categories top-categories">
        <div class="container">
            <div class="category-list">
                <div class="category">
                    <a href="product.php">
                        <img src="images/deals.png" alt="Category 1">
                        <span>Best Deals!</span>
                    </a>
                </div>
                <div class="category">
                    <a href="product.php">
                        <img src="images/controller.jpg" alt="Category 2">
                        <span>Controllers</span>
                    </a>
                </div>
                <div class="category">
                    <a href="product.php">
                        <img src="images/keyboard.png" alt="Category 3">
                        <span>Keyboard</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="categories bottom-categories">
        <div class="container">
            <div class="category-list">
                <div class="category">
                    <a href="product.php">
                        <img src="images/mouse.png" alt="Category 4">
                        <span>Mice</span>
                    </a>
                </div>
                <div class="category">
                    <a href="product.php">
                        <img src="images/PC.png" alt="Category 5">
                        <span>Computers</span>
                    </a>
                </div>
                <div class="category">
                    <a href="product.php">
                        <img src="images/headset.png" alt="Category 6">
                        <span>Headsets</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="products">
        <div class="container">
            <div class="product-list">
                <div class="product">
                    <a href="product.php">
                        <img src="images/pxsbundle.jpg" alt="Product 1">
                        <h3>Product Name 1</h3>
                        <p>Description...</p>
                        <span class="price">€199.99</span>
                    </a>
                </div>
                <div class="product">
                    <a href="product.php">
                        <img src="images/pxsbundle.jpg" alt="Product 2">
                        <h3>Product Name 2</h3>
                        <p>Description...</p>
                        <span class="price">€249.99</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
require 'footer.php';
?>