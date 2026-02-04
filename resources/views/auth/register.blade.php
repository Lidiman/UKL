<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - ProductivityFlow</title>

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

    <!-- regis section -->
    <section class="login-section">
        <div class="login-container">
            <div class="login-box">
                <h1>Daftar</h1>
                <p>Buat akun baru untuk memulai</p>

                <!-- error mssagse -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- FORM REGISTER -->
                <form 
                    class="login-form"
                    method="POST"
                    action="{{ route('register.process') }}"
                >
                    @csrf

                    <!-- jenng full -->
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input 
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Nama lengkap Anda"
                            value="{{ old('name') }}"
                            required
                        >
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- emil -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email"
                            id="email"
                            name="email"
                            placeholder="nama@email.com"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Pw -->
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

                    <!-- acc pw -->
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <input 
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="••••••••"
                            required
                        >
                        @error('password_confirmation')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- terms -->
                    <div class="form-checkbox">
                        <input 
                            type="checkbox"
                            id="terms"
                            name="terms"
                            value="1"
                            required
                        >
                        <label for="terms">Saya setuju dengan <a href="#">Syarat & Ketentuan</a></label>
                    </div>

                    <!-- submit nk kne -->
                    <button type="submit" class="btn-login">
                        Daftar
                    </button>
                </form>

                <!-- link e -->
                <div class="login-links">
                    <p>Sudah punya akun? <a href="/login">Masuk di sini</a></p>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
