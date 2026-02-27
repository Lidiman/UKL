<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Manager - ProductivityFlow</title>
    <link rel="stylesheet" href="{{ asset('css/task-manager.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <svg class="logo-svg" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="logoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#1e5a96;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#0d9488;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <circle cx="60" cy="60" r="55" fill="url(#logoGrad)" />
                    <g fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M 35 35 L 35 85" />
                        <path d="M 35 35 Q 55 35 55 50 Q 55 65 35 65" />
                        <path d="M 55 50 Q 70 40 80 35" />
                        <path d="M 80 35 L 85 30" />
                    </g>
                    <g fill="none" stroke="#4ade80" stroke-width="2.5" stroke-linecap="round">
                        <path d="M 75 70 L 85 60 L 95 50" />
                    </g>
                </svg>
                <span class="logo-text">ProductivityFlow</span>
            </div>

            <div class="nav-right">
                <ul class="nav-links">
                    <li><a href="/">Landing</a></li>
                    <li><a href="#" class="active">Task Manager</a></li>
                    <li>
                        <a href="#" class="profile-btn">
                            <i class='bx bx-user'></i> Profile
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-container">

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class='bx bx-clipboard'></i> Tasks</h2>
            </div>

            <div class="quick-stats">
                <div class="stat-box">
                    <div class="stat-number">12</div>
                    <div class="stat-label">Total Tasks</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">7</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">5</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>

            <button class="btn-add-task btn-primary">
                <i class='bx bx-plus'></i> Tambah Task
            </button>

            <div class="filters">
                <h3>Filter</h3>
                <div class="filter-group">
                    <label class="filter-item active" data-filter="all">
                        <input type="radio" name="filter" value="all" checked>
                        <span>Semua</span>
                    </label>
                    <label class="filter-item" data-filter="active">
                        <input type="radio" name="filter" value="active">
                        <span>Aktif</span>
                    </label>
                    <label class="filter-item" data-filter="completed">
                        <input type="radio" name="filter" value="completed">
                        <span>Selesai</span>
                    </label>
                </div>
            </div>

            <div class="categories">
                <h3>Kategori</h3>
                <div class="category-list">
                    <div class="category-tag active" data-category="all">Semua</div>
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
                <h1>My Tasks</h1>
                <div class="header-actions">
                    <input type="search" class="search-box" placeholder="Cari task...">
                    <select class="sort-select">
                        <option value="recent">Terbaru</option>
                        <option value="priority">Prioritas</option>
                        <option value="deadline">Deadline</option>
                    </select>
                </div>
            </div>

            <!-- Tasks Grid -->
            <div class="tasks-container">

                <div class="task-card">
                    <div class="task-header">
                        <input type="checkbox">
                        <div>
                            <h3 class="task-title">Finish Project Proposal</h3>
                            <p class="task-description">Selesaikan proposal untuk klien baru sebelum deadline Jumat</p>
                        </div>
                        <button class="task-menu">
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </button>
                    </div>

                    <div class="task-meta">
                        <span class="priority-badge priority-high">
                            <i class='bx bxs-circle' style="color:#ef4444"></i> Tinggi
                        </span>
                        <span class="category-badge category-work">
                            <i class='bx bx-briefcase'></i> Kerja
                        </span>
                        <span class="due-date">
                            <i class='bx bx-calendar'></i> Jumat, 28 Feb
                        </span>
                    </div>
                </div>

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

