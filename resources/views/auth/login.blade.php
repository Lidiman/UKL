@extends('layouts.app')
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

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

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
                            placeholder="yourname@email.com"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
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
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
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
