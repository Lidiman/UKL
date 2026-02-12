import './bootstrap';

document.addEventListener("DOMContentLoaded", () => {
    const loader = document.getElementById("page-loader");

    window.addEventListener("load", () => {
        loader.classList.remove("active");
    });

    document.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", () => {
            const href = link.getAttribute("href");

            if (
                href &&
                !href.startsWith("#") &&
                !href.startsWith("javascript") &&
                !link.hasAttribute("target")
            ) {
                loader.classList.add("active");
            }
        });
    });

    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", () => {
            loader.classList.add("active");
        });
    });
});

