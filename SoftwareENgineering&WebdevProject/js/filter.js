document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const category = this.getAttribute('data-category').toLowerCase();

            productCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category').toLowerCase();

                if (category === 'all' || cardCategory === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    const sortBySelect = document.getElementById('sort-by');

    sortBySelect.addEventListener('change', function () {
        const selectedOption = this.value;

        // Get all product cards
        const productContainer = document.querySelector('.product-cards');
        const productCards = Array.from(productContainer.children);

        // Sort the product cards based on the selected option
        switch (selectedOption) {
            case 'price-low-high':
                productCards.sort((a, b) => {
                    const priceA = parseFloat(a.querySelector('.price').innerText.replace('€', ''));
                    const priceB = parseFloat(b.querySelector('.price').innerText.replace('€', ''));
                    return priceA - priceB;
                });
                break;
            case 'price-high-low':
                productCards.sort((a, b) => {
                    const priceA = parseFloat(a.querySelector('.price').innerText.replace('€', ''));
                    const priceB = parseFloat(b.querySelector('.price').innerText.replace('€', ''));
                    return priceB - priceA;
                });
                break;
            case 'name-a-z':
                productCards.sort((a, b) => {
                    const nameA = a.querySelector('h3').innerText.toLowerCase();
                    const nameB = b.querySelector('h3').innerText.toLowerCase();
                    return nameA.localeCompare(nameB);
                });
                break;
            case 'name-z-a':
                productCards.sort((a, b) => {
                    const nameA = a.querySelector('h3').innerText.toLowerCase();
                    const nameB = b.querySelector('h3').innerText.toLowerCase();
                    return nameB.localeCompare(nameA);
                });
                break;
        }

        // Clear the product container
        productContainer.innerHTML = '';

        // Append sorted product cards to the product container
        productCards.forEach(card => {
            productContainer.appendChild(card);
        });
    });

    // Add event listener for "Mouse" button
    const mouseButton = document.getElementById('filter-mouse');
    mouseButton.addEventListener('click', function () {
        productCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category').toLowerCase();
            if (cardCategory === 'mouse') {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Add event listener for "Headphones" button
    const headphonesButton = document.getElementById('filter-headphones');
    headphonesButton.addEventListener('click', function () {
        productCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category').toLowerCase();
            if (cardCategory === 'headphones') {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
