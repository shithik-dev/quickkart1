<script>

// Category TAB Click Filter (Top Categories)
const mainCatItems = document.querySelectorAll(".main-cat-item");
const allProducts = document.querySelectorAll(".product-card");

mainCatItems.forEach(cat => {
    cat.addEventListener("click", () => {

        // Remove active class from all
        mainCatItems.forEach(c => c.classList.remove("active-category"));
        cat.classList.add("active-category");

        let selectedCat = cat.dataset.category; // get category name

        allProducts.forEach(product => {
            if (selectedCat === "all" || product.dataset.category === selectedCat) {
                product.style.display = "block";
            } else {
                product.style.display = "none";
            }
        });

        // Smooth scroll to product section
        document.getElementById("productsSection").scrollIntoView({ behavior: "smooth" });

    });
});


// BEST SELLER SLIDER
const bestSlider = document.getElementById('bestSlider');
const bestPrev = document.getElementById('bestPrev');
const bestNext = document.getElementById('bestNext');

if(bestPrev && bestNext && bestSlider) {
  bestPrev.addEventListener('click', ()=> { bestSlider.scrollBy({left:-220, behavior:'smooth'}); });
  bestNext.addEventListener('click', ()=> { bestSlider.scrollBy({left:220, behavior:'smooth'}); });
  setInterval(()=> { bestSlider.scrollBy({left:220, behavior:'smooth'}); }, 3500);
}

</script>
