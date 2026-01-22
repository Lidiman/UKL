<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ProductivityFlow</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <span class="logo-text">ProductivityFlow</span>
            </div>
            <ul class="nav-links">
                <li><a href="/">Kembali</a></li>
            </ul>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="login-section">
        <div class="login-container">
            <div class="login-box">
                <h1>Masuk</h1>
                <p>Lanjutkan dengan akun mu</p>

                <!-- FORM LOGIN -->
                <form 
                    class="login-form"
                    method="POST"
                    action="{{ route('login.process') }}"
                >
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email"
                            id="email"
                            name="email"
                            placeholder="nama@email.com"
                            required
                        >
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Kata Sandi</label>
                        <input 
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            required
                        >
                    </div>

                    <!-- Remember Me -->
                    <div class="form-checkbox">
                        <input 
                            type="checkbox"
                            id="remember"
                            name="remember"
                            value="1"
                        >
                        <label for="remember">Ingat saya</label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-login">
                        Masuk
                    </button>
                </form>

                <!-- Links -->
                <div class="login-links">
                    <p>Belum punya akun? <a href="/register">Daftar di sini</a></p>
                    <p><a href="/forgot-password">Lupa kata sandi?</a></p>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
