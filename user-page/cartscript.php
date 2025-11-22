<script>
    function addcart(button) {
    const productCard = button.closest('.product-card');

    const name = productCard.querySelector('.productname').textContent.trim();
    const id = productCard.querySelector('.product-id').textContent.replace('Product ID: ', '').replace('product ID: ', '');
    const priceText = productCard.querySelector('.price').textContent.replace('Rs. ', '');
    const price = parseFloat(priceText);
    const image = productCard.querySelector('img').src; // Get the image source URL

    let orders = [];
    try {
        orders = JSON.parse(localStorage.getItem('orders')) || [];
    } catch (e) {
        orders = [];
    }

    orders.push({
        name: name,
        id: id,
        price: price,
        image: image, // Add the image URL to the order object
        date: new Date().toLocaleString()
    });

    localStorage.setItem('orders', JSON.stringify(orders));
    alert(name + " added to cart!");
}
</script>