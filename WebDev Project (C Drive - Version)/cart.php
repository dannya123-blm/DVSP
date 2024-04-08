<?php
// Start the session
session_start();

// Include header
require_once "header.php";

// Include database configuration file
require_once 'databaseconfig.php';

// Declare $conn as global to access it inside functions
global $conn;

// Function to update quantity in the cart
function updateQuantity($productId, $quantity) {
    global $conn; // Add this line
    $_SESSION['cart'][$productId] = $quantity;
}

// Function to remove item from the cart
function removeFromCart($productId) {
    global $conn; // Add this line
    unset($_SESSION['cart'][$productId]);
}
?>

<main class="container">
    <h1>Checkout</h1>
    <?php
    // Check if cart is not empty
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        ?>
        <table class="table">
            <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total = 0;
            // Loop through each item in the cart
            foreach($_SESSION['cart'] as $productId => $quantity) {
                // Retrieve product details from the database
                $sql = "SELECT * FROM products WHERE idProducts = $productId";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $productName = $row['Name'];
                    $productPrice = $row['Price'];
                    // Calculate total price for this product
                    $productTotal = $productPrice * $quantity;
                    $total += $productTotal;
                    ?>
                    <tr>
                        <td><?php echo $productName; ?></td>
                        <td>$<?php echo number_format($productPrice, 2); ?></td>
                        <td>
                            <input type="number" value="<?php echo $quantity; ?>" onchange="updateQuantity(<?php echo $productId; ?>, this.value)">
                        </td>
                        <td>$<?php echo number_format($productTotal, 2); ?></td>
                        <td>
                            <button onclick="removeFromCart(<?php echo $productId; ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr>
                <td colspan="3" class="text-right">Total:</td>
                <td>$<?php echo number_format($total, 2); ?></td>
            </tr>
            </tbody>
        </table>
        <a href="checkout.php" class="btn btn-primary">Proceed to Payment</a>


        <?php
    } else {
        echo '<p>Your cart is empty.</p>';
    }
    ?>
</main>

<?php
// Include footer
require_once "footer.php";
?>

<script>
    // Function to update quantity of item in the cart
    function updateQuantity(productId, quantity) {
        // Send an AJAX request to update the quantity
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "update_quantity.php?product_id=" + productId + "&quantity=" + quantity, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Reload the page to reflect the changes
                window.location.reload();
            }
        };
        xhr.send();
    }

    // Function to remove item from the cart
    function removeFromCart(productId) {
        // Send an AJAX request to remove the item from the cart
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "remove_from_cart.php?product_id=" + productId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Reload the page to reflect the changes
                window.location.reload();
            }
        };
        xhr.send();
    }
</script>
