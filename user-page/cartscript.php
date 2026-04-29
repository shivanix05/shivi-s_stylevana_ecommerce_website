<script>
function addToCart(button) {
    const productCard = button.closest('.product-card');
    
    // Product ID nikalne ka tareeka (Check karein ki aapne class 'pid-badge' ya 'product-id' di hai)
    const idText = productCard.querySelector('.pid-badge').textContent;
    const pid = idText.replace('#', '').trim(); 

    let formData = new FormData();
    formData.append('pid', pid);

    fetch('manage_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if(data.trim() == "Success") {
            alert("Item added to cart!");
        } else {
            console.log(data); // Error console mein dikhega
            alert(data);
        }
    });
}
</script>
