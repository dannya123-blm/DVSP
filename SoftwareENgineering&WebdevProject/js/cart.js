document.addEventListener("DOMContentLoaded", function() {
    // Function to fetch cart items and calculate subtotal
    function updateCart() {
        // Fetch cart items from session
        fetch("productpage.php")
            .then(response => response.json())
            .then(data => {
                // Clear previous cart items
                document.getElementById("cart-items").innerHTML = "";

                // Iterate through each item and display in cart
                data.forEach(item => {
                    const cartItem = document.createElement("div");
                    cartItem.textContent = item.name + " - Price: €" + item.price;
                    document.getElementById("cart-items").appendChild(cartItem);
                });

                // Calculate subtotal
                const subtotal = data.reduce((acc, item) => acc + parseFloat(item.price), 0);
                document.getElementById("subtotal").textContent = "Subtotal: €" + subtotal.toFixed(2);
            });
    }

    // Call updateCart function when the page loads
    updateCart();
});
