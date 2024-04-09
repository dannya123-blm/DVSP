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
</head>

<body>
<main>
    <section class="banner" style="background-image: url('../images/pcbannerimage2.jpg');">
        <div class="bannercontainer">
            <div class="bannercontent">
                <h1>Looking for something?</h1>
                <p>Shop our wide range of electronics and<br> accessories at unbeatable prices!</p>
                <a href="productpage.php" class="productbutton">Shop Now</a>
            </div>
        </div>
    </section>
<br>
    <h2>Top Categories</h2>
    <br>
    <section class="categories left-to-right-categories">
        <div class="container">
            <div class="category-list">
                <div class="category-item BestDeals">
                    <a href="#">
                        <img src="../images/category1.png" alt="Category 1">

                    </a>
                </div>

                <div class="category-item Controllers">
                    <a href="#">
                        <img src="../images/category2.png" alt="Category 2">

                    </a>
                </div>

                <div class="category-item Keyboard">
                    <a href="#">
                        <img src="../images/category3.png" alt="Category 3">

                    </a>
                </div>

                <div class="category-item Mice">
                    <a href="#">
                        <img src="../images/category4.png" alt="Category 4">

                    </a>
                </div>

                <div class="category-item Computers">
                    <a href="#">
                        <img src="../images/category5.png" alt="Category 5">

                    </a>
                </div>

                <div class="category-item Headsets">
                    <a href="#">
                        <img src="../images/category6.png" alt="Category 6">
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!--The Product boxes we want to it to work with the db where when the we post the product and we
    want it to show it will show there maybe or we will change it into something else like gallery or something or we might keep it like this dont know -->
<br>
    <br>
    <h2>Hot Bundles</h2>
    <p>Check out our Bundles</p>
    <section class="products">
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
<?php
require '../template/footer.php';
?>
</html>
