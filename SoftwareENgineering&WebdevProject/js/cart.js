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
                    cartItem.innerHTML = item.name + ' - Quantity: ' + item.quantity + ' - Total: €' + (item.price * item.quantity);
                    cartItemsContainer.appendChild(cartItem);
                });

                // Calculate subtotal
                const subtotal = data.reduce((acc, item) => acc + parseFloat(item.price) * item.quantity, 0);
                document.getElementById("subtotal").textContent = "Subtotal: €" + subtotal.toFixed(2);
            });
    }

    // Call updateCart function when the page loads
    updateCart();
});
