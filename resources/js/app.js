import './bootstrap';
<<<<<<< HEAD
=======


document.addEventListener("DOMContentLoaded", () => {
    const loader = document.getElementById("page-loader");

    // 1. Matikan loader setelah halaman siap (AMAN)
    window.addEventListener("load", () => {
        setTimeout(() => {
            loader.classList.remove("active");
        }, 400); // jangan lebih dari 500ms
    });

    // 2. Loader saat pindah halaman (TANPA PREVENT DEFAULT)
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

    // 3. Loader saat submit form
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", () => {
            loader.classList.add("active");
        });
    });
});


>>>>>>> main
