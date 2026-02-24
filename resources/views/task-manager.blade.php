<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Manager - ProductivityFlow</title>
    <link rel="stylesheet" href="{{ asset('css/task-manager.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
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
                    <li><a href="/dashboard">Dashboard</a></li>
                    <li><a href="#" class="active">Task Manager</a></li>
                    <li><a href="#" class="profile-btn">👤 Profile</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>📋 Tasks</h2>
                <a href="/dashboard" class="back-link">← Kembali ke Dashboard</a>
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

            <button class="btn-add-task btn-primary">+ Tambah Task</button>

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
                    <div class="category-tag" data-category="work">💼 Kerja</div>
                    <div class="category-tag" data-category="personal">👤 Personal</div>
                    <div class="category-tag" data-category="learning">📚 Belajar</div>
                    <div class="category-tag" data-category="health">💪 Kesehatan</div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header Section -->
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

            <!-- Progress Overview -->
            <div class="progress-section">
                <div class="progress-card">
                    <div class="progress-header">
                        <h3>Progress Minggu Ini</h3>
                        <span class="progress-percentage">58%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 58%"></div>
                    </div>
                    <div class="progress-details">
                        <p><strong>7 dari 12</strong> task telah selesai</p>
                    </div>
                </div>
            </div>

            <!-- Tasks Grid -->
            <div class="tasks-container">
                <!-- Task Card 1 -->
                <div class="task-card" data-priority="high" data-status="pending">
                    <div class="task-header">
                        <div class="task-checkbox">
                            <input type="checkbox">
                        </div>
                        <div class="task-title-section">
                            <h3 class="task-title">Finish Project Proposal</h3>
                            <p class="task-description">Selesaikan proposal untuk klien baru sebelum deadline Jumat</p>
                        </div>
                        <button class="task-menu">⋮</button>
                    </div>
                    
                    <div class="task-meta">
                        <span class="priority-badge priority-high">🔴 Tinggi</span>
                        <span class="category-badge category-work">💼 Kerja</span>
                        <span class="due-date">📅 Jumat, 28 Feb</span>
                    </div>
                </div>

                <!-- Task Card 2 -->
                <div class="task-card" data-priority="medium" data-status="pending">
                    <div class="task-header">
                        <div class="task-checkbox">
                            <input type="checkbox">
                        </div>
                        <div class="task-title-section">
                            <h3 class="task-title">Team Meeting Review</h3>
                            <p class="task-description">Diskusi hasil meeting dengan tim tentang roadmap produk</p>
                        </div>
                        <button class="task-menu">⋮</button>
                    </div>
                    
                    <div class="task-meta">
                        <span class="priority-badge priority-medium">🟡 Sedang</span>
                        <span class="category-badge category-work">💼 Kerja</span>
                        <span class="due-date">📅 Rabu, 26 Feb</span>
                    </div>
                </div>

                <!-- Task Card 3 -->
                <div class="task-card" data-priority="low" data-status="completed">
                    <div class="task-header">
                        <div class="task-checkbox">
                            <input type="checkbox" checked>
                        </div>
                        <div class="task-title-section">
                            <h3 class="task-title completed">Update Documentation</h3>
                            <p class="task-description">Perbarui dokumentasi API dengan endpoint terbaru</p>
                        </div>
                        <button class="task-menu">⋮</button>
                    </div>
                    
                    <div class="task-meta">
                        <span class="priority-badge priority-low">🟢 Rendah</span>
                        <span class="category-badge category-work">💼 Kerja</span>
                        <span class="due-date">📅 Selasa, 25 Feb</span>
                    </div>
                </div>

                <!-- Task Card 4 -->
                <div class="task-card" data-priority="high" data-status="pending">
                    <div class="task-header">
                        <div class="task-checkbox">
                            <input type="checkbox">
                        </div>
                        <div class="task-title-section">
                            <h3 class="task-title">Morning Workout</h3>
                            <p class="task-description">Olahraga pagi selama 30 menit untuk kesehatan</p>
                        </div>
                        <button class="task-menu">⋮</button>
                    </div>
                    
                    <div class="task-meta">
                        <span class="priority-badge priority-high">🔴 Tinggi</span>
                        <span class="category-badge category-health">💪 Kesehatan</span>
                        <span class="due-date">📅 Harian</span>
                    </div>
                </div>

                <!-- Task Card 5 -->
                <div class="task-card" data-priority="medium" data-status="pending">
                    <div class="task-header">
                        <div class="task-checkbox">
                            <input type="checkbox">
                        </div>
                        <div class="task-title-section">
                            <h3 class="task-title">Learn React Hooks</h3>
                            <p class="task-description">Pelajari advanced patterns untuk React Hooks</p>
                        </div>
                        <button class="task-menu">⋮</button>
                    </div>
                    
                    <div class="task-meta">
                        <span class="priority-badge priority-medium">🟡 Sedang</span>
                        <span class="category-badge category-learning">📚 Belajar</span>
                        <span class="due-date">📅 Minggu Depan</span>
                    </div>
                </div>

                <!-- Task Card 6 -->
                <div class="task-card" data-priority="low" data-status="completed">
                    <div class="task-header">
                        <div class="task-checkbox">
                            <input type="checkbox" checked>
                        </div>
                        <div class="task-title-section">
                            <h3 class="task-title completed">Grocery Shopping</h3>
                            <p class="task-description">Belanja kebutuhan sehari-hari di supermarket</p>
                        </div>
                        <button class="task-menu">⋮</button>
                    </div>
                    
                    <div class="task-meta">
                        <span class="priority-badge priority-low">🟢 Rendah</span>
                        <span class="category-badge category-personal">👤 Personal</span>
                        <span class="due-date">📅 Sabtu, 27 Feb</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Add Task -->
    <div class="modal" id="addTaskModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tambah Task Baru</h2>
                <button class="modal-close">&times;</button>
            </div>
            
            <form class="task-form">
                <div class="form-group">
                    <label>Judul Task</label>
                    <input type="text" placeholder="Masukkan judul task" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea placeholder="Deskripsi detail task..." rows="4"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Kategori</label>
                        <select required>
                            <option value="">Pilih Kategori</option>
                            <option value="work">💼 Kerja</option>
                            <option value="personal">👤 Personal</option>
                            <option value="learning">📚 Belajar</option>
                            <option value="health">💪 Kesehatan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Prioritas</label>
                        <select required>
                            <option value="">Pilih Prioritas</option>
                            <option value="high">🔴 Tinggi</option>
                            <option value="medium">🟡 Sedang</option>
                            <option value="low">🟢 Rendah</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Deadline</label>
                    <input type="date" required>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary modal-close-btn">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Task</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/task-manager.js') }}"></script>
</body>
</html>
