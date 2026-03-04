<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Manager - ProductivityFlow</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/task-manager.css') }}">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar Navigation (dari dashboard) -->
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
                <a href="/task-manager" class="menu-item active">
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

        <!-- Top Navbar -->
        <header class="navbar-top">
            <div class="navbar-content">
                <!-- Left: Page Title -->
                <div class="navbar-left">
                    <h1 class="page-title">Task Manager</h1>
                </div>

                <!-- Center: Search -->
                <div class="navbar-center">
                    <div class="search-box-wrapper">
                        <i class='bx bx-search'></i>
                        <input type="text" class="search-box" placeholder="Cari task...">
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
                        <button class="profile-btn-nav" id="profileBtn">
                            <img 
                                src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(Auth::user()->email))).'?s=40&d=identicon' }}" 
                                alt="Profile" 
                                class="profile-avatar-small"
                            >
                            <span class="profile-name">{{ Auth::user()->name }}</span>
                            <i class='bx bx-chevron-down'></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Filter Sidebar -->
        <aside class="filter-sidebar">
            <div class="sidebar-header">
                <h2><i class='bx bx-filter-alt'></i> Filters</h2>
            </div>

            <div class="quick-stats">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class='bx bx-task'></i>
                    </div>
                    <div class="stat-data">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Total Tasks</div>
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class='bx bx-check-circle'></i>
                    </div>
                    <div class="stat-data">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class='bx bx-time'></i>
                    </div>
                    <div class="stat-data">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
            </div>

            <button class="btn-add-task btn-primary">
                <i class='bx bx-plus'></i> Tambah Task
            </button>

            <div class="filters">
                <h3>Status</h3>
                <div class="filter-group">
                    <label class="filter-item active" data-filter="all">
                        <input type="radio" name="filter" value="all" checked>
                        <i class='bx bx-list-ul'></i>
                        <span>Semua</span>
                    </label>
                    <label class="filter-item" data-filter="active">
                        <input type="radio" name="filter" value="active">
                        <i class='bx bx-loader-circle'></i>
                        <span>Aktif</span>
                    </label>
                    <label class="filter-item" data-filter="completed">
                        <input type="radio" name="filter" value="completed">
                        <i class='bx bx-check-double'></i>
                        <span>Selesai</span>
                    </label>
                </div>
            </div>

            <div class="categories">
                <h3>Kategori</h3>
                <div class="category-list">
                    <div class="category-tag active" data-category="all">
                        <i class='bx bx-category'></i> Semua
                    </div>
                    <div class="category-tag" data-category="work">
                        <i class='bx bx-briefcase'></i> Kerja
                    </div>
                    <div class="category-tag" data-category="personal">
                        <i class='bx bx-user'></i> Personal
                    </div>
                    <div class="category-tag" data-category="learning">
                        <i class='bx bx-book'></i> Belajar
                    </div>
                    <div class="category-tag" data-category="health">
                        <i class='bx bx-dumbbell'></i> Kesehatan
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h2 class="content-title">
                    <i class='bx bx-list-check'></i> My Tasks
                </h2>
                <div class="header-actions">
                    <select class="sort-select">
                        <option value="recent">
                            <i class='bx bx-sort'></i> Terbaru
                        </option>
                        <option value="priority">Prioritas</option>
                        <option value="deadline">Deadline</option>
                    </select>
                </div>
            </div>

            <!-- Tasks Grid -->
            <div class="tasks-container">
                <!-- Tasks will be loaded here by JavaScript -->
            </div>
        </main>
    </div>

    <!-- Add Task Modal -->
    <div class="modal" id="addTaskModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tambah Task Baru</h2>
                <button class="modal-close">&times;</button>
            </div>

            <form class="task-form">
                <div class="form-group">
                    <label>Judul Task</label>
                    <input type="text" id="taskTitle" placeholder="Masukkan judul task" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea id="taskDescription" placeholder="Deskripsi task (opsional)"></textarea>
                </div>

                <div class="form-group">
                    <label>Tanggal Deadline</label>
                    <input type="date" id="taskDueDate" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select id="taskCategory" required>
                        <option value="work">Kerja</option>
                        <option value="personal">Personal</option>
                        <option value="learning">Belajar</option>
                        <option value="health">Kesehatan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Prioritas</label>
                    <select id="taskPriority" required>
                        <option value="high">Tinggi</option>
                        <option value="medium">Sedang</option>
                        <option value="low">Rendah</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="button" class="modal-close-btn btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-plus'></i> Tambah Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal" id="editTaskModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Task</h2>
                <button class="edit-modal-close">&times;</button>
            </div>

            <form class="edit-task-form">
                <input type="hidden" id="editTaskId">

                <div class="form-group">
                    <label>Judul Task</label>
                    <input type="text" id="editTaskTitle" placeholder="Masukkan judul task" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea id="editTaskDescription" placeholder="Deskripsi task (opsional)"></textarea>
                </div>

                <div class="form-group">
                    <label>Tanggal Deadline</label>
                    <input type="date" id="editTaskDueDate" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select id="editTaskCategory" required>
                        <option value="work">Kerja</option>
                        <option value="personal">Personal</option>
                        <option value="learning">Belajar</option>
                        <option value="health">Kesehatan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Prioritas</label>
                    <select id="editTaskPriority" required>
                        <option value="high">Tinggi</option>
                        <option value="medium">Sedang</option>
                        <option value="low">Rendah</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select id="editTaskStatus" required>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="button" class="edit-modal-close-btn btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/task-manager.js') }}"></script>
</body>
</html>

