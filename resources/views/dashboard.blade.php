<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ProductivityFlow</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

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
                <li><a href="/">Landing</a></li>
                <li><a href="#profile">Profil</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-logout">Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Dashboard Section -->
    <section class="dashboard-section">
        <div class="container">
            <!-- Welcome Header -->
            <div class="welcome-header">
                <h1>Selamat Datang, {{ Auth::user()->name }}!</h1>
                <p>Kelola dan pantau aktivitas Anda di sini</p>
            </div>

            <!-- Profile Card -->
            <div class="profile-section" id="profile">
                <div class="profile-card">
                    <div class="profile-header">
                        <img src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(Auth::user()->email))).'?s=150&d=identicon' }}" alt="avatar" class="profile-avatar">
                        <div class="profile-info">
                            <h2>{{ Auth::user()->name }}</h2>
                            <p class="profile-email">{{ Auth::user()->email }}</p>
                            <span class="profile-badge">Member</span>
                        </div>
                    </div>

                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-value">0</div>
                            <div class="stat-label">Proyek</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">0</div>
                            <div class="stat-label">Tugas</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">0</div>
                            <div class="stat-label">Tim</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="/" class="btn-action btn-primary">
                    <span class="btn-icon">←</span>
                    Kembali ke Landing Page
                </a>
                <a href="#" class="btn-action btn-secondary">
                    <span class="btn-icon">⚙</span>
                    Pengaturan Akun
                </a>
            </div>

            <!-- Quick Stats Section -->
            <div class="quick-stats">
                <h3>Statistik Cepat</h3>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-box-icon">📊</div>
                        <h4>Aktivitas Hari Ini</h4>
                        <p class="stat-box-value">5</p>
                    </div>
                    <div class="stat-box">
                        <div class="stat-box-icon">✅</div>
                        <h4>Tugas Selesai</h4>
                        <p class="stat-box-value">12</p>
                    </div>
                    <div class="stat-box">
                        <div class="stat-box-icon">🔔</div>
                        <h4>Notifikasi</h4>
                        <p class="stat-box-value">3</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

</body>
</html>