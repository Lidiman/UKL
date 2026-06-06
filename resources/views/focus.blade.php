<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::id() }}">
    <title>Focus Workspace - ProductivityFlow</title>

    <!-- CSS Utama -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard-final.css') }}">
    <link rel="stylesheet" href="{{ asset('css/focus.css') }}">

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
                <a href="/dashboard" class="menu-item">
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
                <a href="/focus" class="menu-item active">
                    <i class='bx bx-target-lock'></i>
                    <span>Focus</span>
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
                    <h1 class="page-title">Focus Workspace</h1>
                </div>

                <!-- Center: Empty for Focus, or minimal -->
                <div class="navbar-center">
                    <!-- Keep empty for distraction free -->
                </div>

                <!-- Right: Notifications & Profile -->
                <div class="navbar-right">
                    <!-- Notification Bell -->
                    <button class="notification-btn" id="notificationBtn">
                        <i class='bx bx-bell'></i>
                        <span class="notification-badge" style="display:none;">0</span>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="profile-dropdown-container">
                        <button class="profile-btn" id="profileBtn">
                            <img 
                                src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(Auth::user()->email))).'?s=40&d=identicon' }}" 
                                alt="Profile" 
                                class="profile-avatar-small"
                            >
                            <span class="profile-name">{{ Auth::user()->name ?? 'User' }}</span>
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
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Grid for Focus -->
        <main class="main-content focus-main">
            <!-- Center Timer Section -->
            <section class="timer-section">
                <!-- Background with blur -->
                <div class="timer-bg"></div>
                <div class="timer-overlay"></div>
                
                <div class="timer-card">
                    <!-- Modes -->
                    <div class="timer-modes">
                        <button class="mode-btn active" data-mode="focus">Pomodoro</button>
                        <button class="mode-btn" data-mode="shortBreak">Short Break</button>
                        <button class="mode-btn" data-mode="longBreak">Long Break</button>
                    </div>
                    
                    <!-- Timer -->
                    <div class="timer-display" id="timeDisplay">25:00</div>
                    
                    <!-- Controls -->
                    <div class="timer-controls">
                        <button class="control-btn btn-reset" id="btnReset" title="Reset Timer">
                            <i class='bx bx-reset'></i>
                        </button>
                        <button class="control-btn btn-start-pause" id="btnStartPause">
                            <i class='bx bx-play'></i>
                        </button>
                        <button class="control-btn btn-settings" id="btnSettings" title="Timer Settings">
                            <i class='bx bx-slider-alt'></i>
                        </button>
                    </div>
                </div>

                <!-- Task Input -->
                <div class="task-input-section">
                    <div class="task-input-wrapper" id="taskInputWrapper">
                        <i class='bx bx-check-circle'></i>
                        <input type="text" id="currentTaskInput" class="focus-task-input" placeholder="What are you working on?">
                    </div>
                    <!-- Active Task Display (Initially Hidden) -->
                    <div class="active-task-display" id="activeTaskDisplay" style="display: none;">
                        <div class="active-task-content">
                            <i class='bx bx-target-lock active-task-icon'></i>
                            <span id="activeTaskTitle">My Task</span>
                        </div>
                        <button class="clear-task-btn" id="clearTaskBtn" title="Clear Task">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Right Productivity Sidebar -->
            <aside class="productivity-sidebar">

                <div class="focus-stat-card">
                    <div class="stat-icon-wrapper icon-purple">
                        <i class='bx bx-time-five'></i>
                    </div>
                    <div class="stat-details" style="width: 100%;">
                        <h4>Focus Time Today</h4>
                        <div class="stat-val" id="dailyFocusHoursDisplay">0h 0m</div>
                        <div class="stat-sub" id="rightSidebarActiveTask" style="display: none; align-items: center; gap: 4px; margin-top: 6px; color: #8B5CF6; font-weight: 500;">
                            <i class='bx bx-loader-alt bx-spin'></i> <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">Working on: <strong id="rightSidebarActiveTaskName"></strong></span>
                        </div>
                    </div>
                </div>

                <!-- Motivation -->
                <div class="quote-card">
                    <i class='bx bxs-quote-alt-left quote-icon'></i>
                    <p class="quote-text">"Focus on being productive instead of busy."</p>
                    <div class="quote-author">- Tim Ferriss</div>
                </div>

                <!-- History -->
                <div class="history-card">
                    <h3><i class='bx bx-history'></i> Recent Sessions</h3>
                    <div class="history-list" id="sessionHistoryList">
                        <!-- Items injected by JS -->
                        <div class="history-item" style="justify-content: center; color: var(--text-tertiary);">
                            No sessions yet today.
                        </div>
                    </div>
                </div>
            </aside>
        </main>
    </div>

    <!-- Timer Settings Modal -->
    <div class="modal" id="timerSettingsModal">
        <div class="modal-content focus-settings-modal">
            <div class="modal-header">
                <h2><i class='bx bx-time'></i> Timer Settings</h2>
                <button class="modal-close" id="closeSettingsModal">&times;</button>
            </div>
            <div class="settings-form">
                <div class="settings-group">
                    <label>Pomodoro (minutes)</label>
                    <input type="number" id="settingPomodoro" min="1" max="90" value="25">
                </div>
                <div class="settings-group">
                    <label>Short Break (minutes)</label>
                    <input type="number" id="settingShortBreak" min="1" max="30" value="5">
                </div>
                <div class="settings-group">
                    <label>Long Break (minutes)</label>
                    <input type="number" id="settingLongBreak" min="1" max="60" value="15">
                </div>
                <div class="form-actions" style="margin-top: 1.5rem;">
                    <button class="btn btn-primary" id="saveSettingsBtn" style="width: 100%;">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/focus.js') }}"></script>
</body>
</html>
