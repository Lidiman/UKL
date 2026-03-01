<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- style utama -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <!-- navbar atas -->
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

    <!-- isi dashboard -->
    <section class="dashboard-section">
        <div class="container">

            <!-- sambutan -->
            <div class="welcome-header">
                <h1>Selamat Datang, {{ Auth::user()->name }}!</h1>
                <p>Ringkasan aktivitas kamu ada di sini</p>
            </div>

            <!-- kartu profil -->
            <div class="profile-section" id="profile">
                <div class="profile-card">
                    <div class="profile-header">
                        <img 
                            src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(Auth::user()->email))).'?s=150&d=identicon' }}" 
                            alt="avatar" 
                            class="profile-avatar"
                        >
                        <div class="profile-info">
                            <h2>{{ Auth::user()->name }}</h2>
                            <p class="profile-email">{{ Auth::user()->email }}</p>
                            <span class="profile-badge">Member</span>
                        </div>
                    </div>

                    <!-- statistik singkat -->
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-project" style="font-size: 2rem;
    font-weight: 700;
    color: var(--highlight-color);
    margin-bottom: 0.5rem;">0</div>
                            <div class="stat-label">Project</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-task" style="font-size: 2rem;
    font-weight: 700;
    color: var(--highlight-color);
    margin-bottom: 0.5rem;">0</div>
                            <div class="stat-label">Task</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-completed" style="font-size: 2rem;
    font-weight: 700;
    color: var(--highlight-color);
    margin-bottom: 0.5rem;">0</div>
                            <div class="stat-label">Completed</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- tombol aksi -->
            <div class="action-buttons">
                <a href="/" class="btn-action btn-primary">
                    <span class="btn-icon">
                        <i class='bx bx-arrow-back'></i>
                    </span>
                    Balik ke Landing
                </a>
                <a href="/task-manager" class="btn-action btn-secondary">
                    <span class="btn-icon">
                        <i class='bx bx-task'></i>
                    </span>
                    Task Manager
                </a>
            </div>

            <!-- info cepat -->
            <div class="quick-stats">
                <h3>Statistik Cepat</h3>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-box-icon">
                            <i class='bx bx-bar-chart-alt-2'></i>
                        </div>
                        <h4>Aktivitas Hari Ini</h4>
                        <p class="stat-box-value">5</p>
                    </div>
                    <div class="stat-box">
                        <div class="stat-box-icon">
                            <i class='bx bx-check-circle'></i>
                        </div>
                        <h4>Tugas Beres</h4>
                        <p class="stat-box-value-done" style='font-size: 2.5rem; font-weight: 700; background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin: 0;'>

                        </p>
                    </div>
                    <div class="stat-box">
                        <div class="stat-box-icon">
                            <i class='bx bx-bell'></i>
                        </div>
                        <h4>Notifikasi</h4>
                        <p class="stat-box-value-notification">3</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

<script src="{{ asset('js/dashboard.js') }}"></script>

</body>
</html>