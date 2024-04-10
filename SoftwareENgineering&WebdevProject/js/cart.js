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

    // Add event listener to all Add to Cart buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', addToCart);
    });

    // Function to handle adding product to the cart
    function addToCart(event) {
        const productId = event.target.getAttribute('data-product-id');

        // Send an asynchronous request to add the product to the cart
        fetch('productpage.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId
            }),
        })
            .then(response => {
                if (response.ok) {
                    // Product added successfully, redirect to cart page
                    window.location.href = "cart.php";
                } else {
                    // Handle error
                    console.error('Failed to add product to cart');
                }
            })
            .catch(error => {
                console.error('Error adding product to cart:', error);
            });
    }
});
