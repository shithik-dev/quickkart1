// Navbar drawer & mobile sidebar
document.addEventListener("DOMContentLoaded", () => {
    const navToggle = document.querySelector(".nav-mobile-toggle");
    const navDrawer = document.querySelector(".nav-drawer");
    if (navToggle && navDrawer) {
        navToggle.addEventListener("click", () => {
            navDrawer.classList.toggle("open");
        });
    }

    const sidebarToggle = document.querySelector("[data-sidebar-toggle]");
    const sidebar = document.querySelector("[data-sidebar]");
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("open");
        });
    }
});

