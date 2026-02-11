<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProductivityFlow - Capai Target Mu dengan Fokus</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <svg class="logo-svg" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                    <!-- Circle background -->
                    <defs>
                        <linearGradient id="logoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#1e5a96;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#0d9488;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <circle cx="60" cy="60" r="55" fill="url(#logoGrad)" />
                    
                    <!-- P shape with spiral -->
                    <g fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Vertical line of P -->
                        <path d="M 35 35 L 35 85" />
                        <!-- Curved part of P -->
                        <path d="M 35 35 Q 55 35 55 50 Q 55 65 35 65" />
                        <!-- Spiral -->
                        <path d="M 55 50 Q 70 40 80 35" />
                        <path d="M 80 35 L 85 30" />
                    </g>
                    
                    <!-- Arrow/Check mark -->
                    <g fill="none" stroke="#4ade80" stroke-width="2.5" stroke-linecap="round">
                        <path d="M 75 70 L 85 60 L 95 50" />
                    </g>
                </svg>
                <span class="logo-text">ProductivityFlow</span>
            </div>
            <ul class="nav-links">
                <li><a href="#features">Fitur</a></li>
                <li><a href="#how-it-works">Cara Kerja</a></li>
                <li><a href="/login" class="cta-button">Masuk</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Fokus Lebih Baik, <span class="gradient-text">Capai Lebih Banyak</span></h1>
                <p class="hero-subtitle">Kelola tugas, bangun kebiasaan, dan analisa produktivitas mu dengan satu platform yang intuitif.</p>
                <div class="hero-buttons">
                    <button class="btn btn-primary" id="cta-main">Coba Sekarang</button>
                    <button class="btn btn-secondary">Lihat Demo</button>
                </div>
            </div>
            <div class="hero-visual">
                <div class="hero-card card-1">
                    <div class="card-header">📋 Tugas Hari Ini</div>
                    <div class="card-body">
                        <div class="task-item">✓ Finish project</div>
                        <div class="task-item">○ Team meeting</div>
                    </div>
                </div>
                <div class="hero-card card-2">
                    <div class="card-header">⏱️ Focus Session</div>
                    <div class="card-body">
                        <div class="timer">25:00</div>
                    </div>
                </div>
                <div class="hero-card card-3">
                    <div class="card-header">📈 Produktivitas</div>
                    <div class="card-body">
                        <div class="stat">+45% ↗️</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-header">
            <h2>Fitur Unggulan</h2>
            <p>Semua yang kamu butuhkan untuk meningkatkan produktivitas</p>
        </div>

        <div class="features-grid">
            <!-- Feature 1: Task Manager -->
            <div class="feature-card">
                <div class="feature-icon">📋</div>
                <h3>Task Manager</h3>
                <p>Kelola tugas harian & target mingguan dengan jelas. Prioritaskan apa yang paling penting dan fokus pada hal-hal yang benar-benar membuat perbedaan.</p>
                <div class="feature-highlight">Organisasi Sempurna</div>
            </div>

            <!-- Feature 2: Focus Session -->
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>Focus Session</h3>
                <p>Sesi fokus terstruktur untuk mengurangi distraksi. Gunakan teknik Pomodoro untuk bekerja dengan produktif dan istirahat yang cukup.</p>
                <div class="feature-highlight">Tanpa Gangguan</div>
            </div>

            <!-- Feature 3: Habit Tracker -->
            <div class="feature-card">
                <div class="feature-icon">✨</div>
                <h3>Habit Tracker</h3>
                <p>Bangun kebiasaan kecil yang konsisten. Lihat progress harianmu dan rayakan setiap pencapaian untuk memotivasi diri sendiri.</p>
                <div class="feature-highlight">Konsistensi Terjaga</div>
            </div>

            <!-- Feature 4: Productivity Insight -->
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Productivity Insight</h3>
                <p>Analisis sederhana tentang pola fokus & produktivitas. Pahami kapan kamu paling produktif dan optimalkan jadwal kerjamu.</p>
                <div class="feature-highlight">Data yang Berguna</div>
            </div>

            <!-- Feature 5: Weekly Reflection -->
            <div class="feature-card">
                <div class="feature-icon">🔄</div>
                <h3>Weekly Reflection</h3>
                <p>Evaluasi mingguan untuk perbaikan berkelanjutan. Lihat apa yang berhasil dan rencanakan minggu depan dengan lebih baik.</p>
                <div class="feature-highlight">Perbaikan Terus-Menerus</div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="section-header">
            <h2>Bagaimana Cara Kerjanya?</h2>
            <p>Tiga langkah sederhana menuju produktivitas maksimal</p>
        </div>

        <div class="steps-container">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Buat & Atur Tugas</h3>
                <p>Daftarkan semua tugas kamu dan kelompokkan berdasarkan prioritas dan deadline.</p>
            </div>
            <div class="step-arrow">→</div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Mulai Focus Session</h3>
                <p>Jalankan sesi fokus dan biarkan sistem menangani distraksi sambil kamu bekerja.</p>
            </div>
            <div class="step-arrow">→</div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Analisis & Perbaiki</h3>
                <p>Lihat insights mingguan dan susun strategi untuk produktivitas yang lebih baik.</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stat-item">
                <div class="stat-number">10K+</div>
                <div class="stat-label">Pengguna Aktif</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">500K+</div>
                <div class="stat-label">Tugas Selesai</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">4.9★</div>
                <div class="stat-label">Rating Aplikasi</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">150M+</div>
                <div class="stat-label">Menit Fokus</div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" id="cta">
        <div class="cta-content">
            <h2>Siap Meningkatkan Produktivitasmu?</h2>
            <p>Bergabunglah dengan ribuan pengguna yang telah mengubah cara mereka bekerja.</p>
            <button class="btn btn-primary btn-large" onclick="window.location='{{ route('register') }}'">Mulai Gratis Sekarang</button>
            <p class="cta-footer">Tidak perlu kartu kredit. Akses penuh ke semua fitur.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>ProductivityFlow</h4>
                    <p>Platform produktivitas untuk fokus yang lebih baik.</p>
                </div>
                <div class="footer-section">
                    <h4>Produk</h4>
                    <ul>

                        <li><a href="#">Harga</a></li>
                        <li><a href="#">Unduh</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Perusahaan</h4>
                    <ul>
                        <li><a href="#">Tentang</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Kontak</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#">Privacy</a></li>
                        <li><a href="#">Terms</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 ProductivityFlow. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>
