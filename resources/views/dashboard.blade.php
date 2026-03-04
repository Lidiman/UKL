<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS Utama -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard-final.css') }}">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <!-- div1: Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <div class="logo-icon">
                    <i class='bx bx-bolt'></i>
                </div>
                <span class="logo-text">ProductivityFlow</span>
            </div>

            <nav class="sidebar-menu">
                <a href="/dashboard" class="menu-item active">
                    <i class='bx bx-home'></i>
                    <span>Dashboard</span>
                </a>
                <a href="/projects" class="menu-item">
                    <i class='bx bx-folder'></i>
                    <span>Projects</span>
                </a>
                <a href="/task-manager" class="menu-item">
                    <i class='bx bx-task'></i>
                    <span>Tasks</span>
                </a>
                <a href="/analytics" class="menu-item">
                    <i class='bx bx-bar-chart-alt-2'></i>
                    <span>Analytics</span>
                </a>
                <a href="/settings" class="menu-item">
                    <i class='bx bx-cog'></i>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class='bx bx-log-out'></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- div2: Top Navbar -->
        <header class="navbar-top">
            <div class="navbar-content">
                <!-- Left: Page Title -->
                <div class="navbar-left">
                    <h1 class="page-title">Dashboard</h1>
                </div>

                <!-- Center: Search -->
                <div class="navbar-center">
                    <div class="search-box">
                        <i class='bx bx-search'></i>
                        <input type="text" class="search-input" placeholder="Cari tugas, proyek, atau anggota...">
                    </div>
                </div>

                <!-- Right: Notifications & Profile -->
                <div class="navbar-right">
                    <!-- Notification Bell -->
                    <button class="notification-btn" id="notificationBtn">
                        <i class='bx bx-bell'></i>
                        <span class="notification-badge">3</span>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="profile-dropdown-container">
                        <button class="profile-btn" id="profileBtn">
                            <img 
                                src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(Auth::user()->email))).'?s=40&d=identicon' }}" 
                                alt="Profile" 
                                class="profile-avatar-small"
                            >
                            <span class="profile-name">{{ Auth::user()->name }}</span>
                            <i class='bx bx-chevron-down'></i>
                        </button>

                        <!-- Profile Dropdown Menu -->
                        <div class="dropdown-menu" id="profileMenu">
                            <a href="#profile" class="dropdown-item">
                                <i class='bx bx-user'></i>
                                <span>Profile</span>
                            </a>
                            <a href="/settings" class="dropdown-item">
                                <i class='bx bx-cog'></i>
                                <span>Settings</span>
                            </a>
                            <hr class="dropdown-divider">
                            <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                                @csrf
                                <button type="submit" class="dropdown-item dropdown-item-logout">
                                    <i class='bx bx-log-out'></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>

                        <!-- Notification Dropdown -->
                        <div class="notifications-dropdown" id="notificationsMenu">
                            <div class="notifications-header">
                                <h3>Notifikasi</h3>
                                <button class="close-notifications" id="closeNotifications">
                                    <i class='bx bx-x'></i>
                                </button>
                            </div>
<div class="notifications-list" id="Notification-Wrapper">
                                <div class="notification-item">
                                    <div class="notification-icon">
                                        <i class='bx bx-task'></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-title">Task: Review Design</p>
                                        <span class="notification-time">5 menit lalu</span>
                                    </div>
                                </div>
                                <div class="see-all-notifications">
                                    <a href="#">Lihat Semua Notifikasi <i class='bx bx-right-arrow-alt'></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Grid -->
        <main class="main-content">
            <!-- div3: Weekly Summary -->
            <section class="card weekly-summary">
                <div class="card-header">
                    <h2 class="card-title">Ringkasan Mingguan</h2>
                    <span class="card-subtitle">7 hari terakhir</span>
                </div>

                <div class="summary-content">
                    <div class="summary-stat">
                        <div class="stat-icon">
                            <i class='bx bx-task'></i>
                        </div>
                        <div class="stat-data">
                            <span class="stat-label">Total Tasks</span>
                            <p class="stat-box-value-total">24</p>
                        </div>
                    </div>

                    <div class="summary-stat">
                        <div class="stat-icon">
                            <i class='bx bx-check-circle'></i>
                        </div>
                        <div class="stat-data">
                            <span class="stat-label">Selesai</span>
                            <p class="stat-box-value-done">18</p>
                        </div>
                    </div>

                    <div class="summary-stat">
                        <div class="stat-icon">
                            <i class='bx bx-time'></i>
                        </div>
                        <div class="stat-data">
                            <span class="stat-label">Pending</span>
                            <p class="stat-value">6</p>
                        </div>
                    </div>
                </div>

                <div class="progress-section">
                    <div class="progress-header">
                        <span class="progress-label">Completion Rate</span>
                        <span class="progress-percentage">75%</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: 75%"></div>
                    </div>
                </div>

                <div class="summary-footer">
                    <i class='bx bx-trending-up'></i>
                    <span>Naik 10% dari minggu lalu</span>
                </div>
            </section>

            <!-- div5: Task Overview -->
            <section class="card task-overview">
                <div class="card-header">
                    <h2 class="card-title">Task Overview</h2>
                </div>
                
                <div class="overview-content">
                    <div class="overview-stat">
                        <span class="overview-label">Urgent</span>
                        <p class="overview-value">3</p>
                    </div>
                    <div class="overview-stat">
                        <span class="overview-label">In Progress</span>
                        <p class="overview-value">5</p>
                    </div>
                    <div class="overview-stat">
                        <span class="overview-label">Completed</span>
                        <p class="overview-value">18</p>
                    </div>
                </div>

                <div class="overview-chart">
                    <div class="chart-bar" style="height: 60%"></div>
                    <div class="chart-bar" style="height: 40%"></div>
                    <div class="chart-bar" style="height: 80%"></div>
                </div>
            </section>

            <!-- div6: Notifications Panel -->
            <section class="card notifications-panel">
                <div class="card-header">
                    <h2 class="card-title">Recent Alerts</h2>
                </div>
                
                <div class="alerts-list">
                    <div class="alert-item priority-high">
                        <div class="alert-icon">
                            <i class='bx bx-error-circle'></i>
                        </div>
                        <div class="alert-content">
                            <p class="alert-message">Deadline approaching: API Integration</p>
                            <span class="alert-time">2 jam lagi</span>
                        </div>
                    </div>

                    <div class="alert-item priority-medium">
                        <div class="alert-icon">
                            <i class='bx bx-info-circle'></i>
                        </div>
                        <div class="alert-content">
                            <p class="alert-message">Tinjauan: Frontend Testing</p>
                            <span class="alert-time">3 jam lalu</span>
                        </div>
                    </div>

                    <div class="alert-item priority-medium">
                        <div class="alert-icon">
                            <i class='bx bx-check-double'></i>
                        </div>
                        <div class="alert-content">
                            <p class="alert-message">Tugas Selesai: Database Optimization</p>
                            <span class="alert-time">1 jam lalu</span>
                        </div>
                    </div>

                    <div class="alert-item priority-low">
                        <div class="alert-icon">
                            <i class='bx bx-comment'></i>
                        </div>
                        <div class="alert-content">
                            <p class="alert-message">Komentar baru di Project Q2</p>
                            <span class="alert-time">30 menit lalu</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- div7: Project Progress -->
            <section class="card project-progress">
                <div class="card-header">
                    <h2 class="card-title">Project Progress</h2>
                </div>
                
                <div class="projects-list">
                    <div class="project-item">
                        <div class="project-name">Website Redesign</div>
                        <div class="project-bar">
                            <div class="project-progress-fill" style="width: 85%"></div>
                        </div>
                        <span class="project-percentage">85%</span>
                    </div>

                    <div class="project-item">
                        <div class="project-name">Mobile App</div>
                        <div class="project-bar">
                            <div class="project-progress-fill" style="width: 60%"></div>
                        </div>
                        <span class="project-percentage">60%</span>
                    </div>

                    <div class="project-item">
                        <div class="project-name">API Integration</div>
                        <div class="project-bar">
                            <div class="project-progress-fill" style="width: 45%"></div>
                        </div>
                        <span class="project-percentage">45%</span>
                    </div>
                </div>
            </section>

            <!-- div8: Productivity Score -->
            <section class="card productivity-score">
                <div class="card-header">
                    <h2 class="card-title">Productivity</h2>
                </div>
                
                <div class="score-content">
                    <div class="score-number">
                        <span class="score-value">8.4</span>
                        <span class="score-unit">/10</span>
                    </div>
                    
                    <div class="score-mini-chart">
                        <div class="mini-bar" style="height: 40%"></div>
                        <div class="mini-bar" style="height: 60%"></div>
                        <div class="mini-bar" style="height: 75%"></div>
                        <div class="mini-bar" style="height: 85%"></div>
                        <div class="mini-bar" style="height: 70%"></div>
                    </div>

                    <div class="score-label">Minggu Ini</div>
                </div>
            </section>
        </main>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>