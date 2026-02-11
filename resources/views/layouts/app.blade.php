<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="page-loader"
    class="fixed inset-0 bg-white flex items-center justify-center z-50 opacity-0 transition-all duration-300">
    <div class="flex flex-col items-center gap-4">
        <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        <p class="text-gray-600 font-medium">Loading...</p>
    </div>

</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const loader = document.getElementById("page-loader");

    // Saat pertama kali halaman selesai load → sembunyikan loader
    window.addEventListener("load", function () {
        loader.classList.add("opacity-0");
        loader.classList.add("pointer-events-none");
    });

    // Saat klik link → tampilkan loader
    document.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", function (e) {

            // Hindari link dengan target _blank atau anchor #
            if (link.target === "_blank" || link.href.includes("#")) return;

            loader.classList.remove("opacity-0");
            loader.classList.remove("pointer-events-none");
        });
    });

});
</script>
</body>
</html>