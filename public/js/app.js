import './bootstrap';
const searchInput = document.getElementById('search-input');
const productCards = document.querySelectorAll('.product-card');

searchInput.addEventListener('input', () => {
    const searchTerm = searchInput.value.toLowerCase();

    productCards.forEach((card) => {
        const title = card.querySelector('.product-card-title').textContent.toLowerCase();
        const description = card.querySelector('.product-card-description').textContent.toLowerCase();

        if (title.includes(searchTerm) || description.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});
