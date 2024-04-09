<?php
require '../template/header.php';
?>
<!--Start Html here i dont think we will need php for now-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DVS Expansion HomePage</title>
<link rel="stylesheet" href="../css/style.css">
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
    <br>
    <section class="categories left-to-right-categories">
        <div class="container">
            <div class="category-list">
                <div class="category-item BestDeals">
                    <a href="#">
                        <img src="../images/category1.png" alt="Category 1">
                        <span class="category-name">DEALS</span>

                    </a>
                </div>

                <div class="category-item Controllers">
                    <a href="#">
                        <img src="../images/category2.jpeg" alt="Category 2">
                        <span class="category-name">CONTROLLER</span>

                    </a>
                </div>

                <div class="category-item Keyboard">
                    <a href="#">
                        <img src="../images/category3.jpeg" alt="Category 3">
                        <span class="category-name">KEYBOARD</span>

                    </a>
                </div>

                <div class="category-item Mice">
                    <a href="#">
                        <img src="../images/category4.jpeg" alt="Category 4">
                        <span class="category-name">HEADPHONES</span>

                    </a>
                </div>

                <div class="category-item Computers">
                    <a href="#">
                        <img src="../images/category5.jpeg" alt="Category 5">
                        <span class="category-name">COMPUTERS</span>

                    </a>
                </div>

                <div class="category-item Headsets">
                    <a href="#">
                        <img src="../images/category6.jpeg" alt="Category 6">
                        <span class="category-name">MOUSES</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!--The Product boxes we want to it to work with the db where when the we post the product and we
    want it to show it will show there maybe or we will change it into something else like gallery or something or we might keep it like this dont know -->
<br>
    <br>
    <section class="products">
        <h2>Hot Bundles</h2>
        <div class="container">
            <div class="product-list">
                <div class="product">
                    <a href="#">
                        <img src="../images/pxsbundle.jpg" alt="Product 1">
                        <h3>Product Name 1</h3>
                        <p>Description...</p>
                        <span class="price">€199.99</span>
                    </a>
                </div>
                <div class="product">
                    <a href="#">
                        <img src="../images/pxsbundle.jpg" alt="Product 2">
                        <h3>Product Name 2</h3>
                        <p>Description...</p>
                        <span class="price">€249.99</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <br>
</main>

</body>
</html>

<?php
require '../template/footer.php';
?>
