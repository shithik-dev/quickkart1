document.addEventListener("DOMContentLoaded", () => {
    const slider = document.querySelector("[data-slider]");
    if (!slider) return;
    const slides = slider.querySelectorAll(".slide");
    const dotsContainer = slider.querySelector(".dots");
    let index = 0;
    const total = slides.length;

    const createDots = () => {
        slides.forEach((_, i) => {
            const dot = document.createElement("button");
            dot.className = "dot";
            dot.setAttribute("aria-label", `Slide ${i + 1}`);
            dot.addEventListener("click", () => goTo(i));
            dotsContainer.appendChild(dot);
        });
    };

    const setActive = () => {
        slides.forEach((s, i) => s.classList.toggle("active", i === index));
        dotsContainer.querySelectorAll(".dot").forEach((d, i) => d.classList.toggle("active", i === index));
    };

    const goTo = (i) => {
        index = (i + total) % total;
        setActive();
    };

    const next = () => goTo(index + 1);
    let timer = setInterval(next, 4500);

    slider.addEventListener("mouseenter", () => clearInterval(timer));
    slider.addEventListener("mouseleave", () => { timer = setInterval(next, 4500); });

    createDots();
    setActive();
});

