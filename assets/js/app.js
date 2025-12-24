document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.querySelector("#search");
    const searchForm = document.querySelector("#searchForm");
    if (searchInput && searchForm) {
        searchForm.addEventListener("submit", (e) => {
            if (!searchInput.value.trim()) {
                e.preventDefault();
            }
        });
    }
});

