<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ProductivityFlow')</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- BOXICONS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <!-- LOADING SCREEN -->
    <div id="page-loader" class="active">
        <div class="loader"></div>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <span class="logo-text">ProductivityFlow</span>
            </div>
            <ul class="nav-links">
                <li><a href="/">Landing</a></li>
                <li><a href="#profile">Profil</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-logout">Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- ISI HALAMAN -->
    <main>
        @yield('content')
    </main>

    <!-- SCRIPT LOADER -->
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const loader = document.getElementById("page-loader");

        window.addEventListener("load", () => {
            loader.classList.remove("active");
        });

        document.querySelectorAll("a").forEach(link => {
            link.addEventListener("click", () => {
                const href = link.getAttribute("href");
                if (href && !href.startsWith("#") && !link.hasAttribute("target")) {
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
    </script>

</body>
</html>
