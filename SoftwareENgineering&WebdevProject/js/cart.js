document.addEventListener("DOMContentLoaded", function() {
    // Function to fetch cart items and calculate subtotal
    function updateCart() {
        // Fetch cart items from session
        fetch("productpage.php")
            .then(response => response.json())
            .then(data => {
                // Clear previous cart items
                const cartItemsContainer = document.getElementById("cart-items");
                cartItemsContainer.innerHTML = "";

                // Iterate through each item and display in cart
                data.forEach(item => {
                    const cartItem = document.createElement("li");
                    cartItem.innerHTML = item.name + ' - Quantity: ' + item.quantity + ' - Total: €' + (item.price * item.quantity) +
                        ' <button class="remove-from-cart-btn" data-product-id="' + item.product_id + '">Remove</button>';
                    cartItemsContainer.appendChild(cartItem);
                });

                // Calculate subtotal
                const subtotal = data.reduce((acc, item) => acc + parseFloat(item.price) * item.quantity, 0);
                document.getElementById("subtotal").textContent = "Subtotal: €" + subtotal.toFixed(2);
            });
    }

    // Call updateCart function when the page loads
    updateCart();

    // Add event listener to all Remove buttons
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-from-cart-btn')) {
            const productId = event.target.getAttribute('data-product-id');

            // Send an asynchronous request to remove the product from the cart
            fetch('productpage.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    remove_from_cart: true
                }),
            })
                .then(response => {
                    if (response.ok) {
                        // Product removed successfully, update the cart display
                        updateCart();
                    } else {
                        // Handle error
                        console.error('Failed to remove product from cart');
                    }
                })
                .catch(error => {
                    console.error('Error removing product from cart:', error);
                });
        }
    });
});
