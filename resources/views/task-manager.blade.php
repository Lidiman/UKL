<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - ProductivityFlow</title>

    <!-- style utama -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task-manager.css') }}">

    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- lucide icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
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
                <li><a href="/dashboard">Dashboard</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-logout">Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- task manager section -->
    <section class="task-manager-section">
        <div class="container">

            <!-- header -->
            <div class="tm-header">
                <div class="tm-title-group">
                    <h1 class="tm-title"><i data-lucide="clipboard-list" class="tm-title-icon"></i> Task Manager</h1>
                    <p class="tm-subtitle">Kelola tugas harian & target mingguan dengan jelas</p>
                </div>
                <button class="tm-btn-create" data-action="create-task">
                    <span class="tm-btn-icon">+</span>
                    Buat Tugas Baru
                </button>
            </div>

            <!-- stats overview -->
            <div class="tm-stats">
                <div class="stat-card stat-total">
                    <div class="stat-icon"><i data-lucide="list-todo"></i></div>
                    <div class="stat-content">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Total Tugas</div>
                    </div>
                </div>
                <div class="stat-card stat-pending">
                    <div class="stat-icon"><i data-lucide="clock"></i></div>
                    <div class="stat-content">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
                <div class="stat-card stat-progress">
                    <div class="stat-icon"><i data-lucide="rotate-cw"></i></div>
                    <div class="stat-content">
                        <div class="stat-number">0</div>
                        <div class="stat-label">In Progress</div>
                    </div>
                </div>
                <div class="stat-card stat-done">
                    <div class="stat-icon"><i data-lucide="check-circle"></i></div>
                    <div class="stat-content">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Selesai</div>
                    </div>
                </div>
            </div>

            <!-- filters & search -->
            <div class="tm-filters">
                <div class="tm-search">
                    <input 
                        type="text" 
                        class="tm-search-input" 
                        placeholder="Cari tugas..."
                        data-filter="search"
                    >
                </div>
                <div class="tm-filter-group">
                    <select class="tm-filter-select" data-filter="status">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="in-progress">In Progress</option>
                        <option value="completed">Selesai</option>
                    </select>
                    <select class="tm-filter-select" data-filter="priority">
                        <option value="">Semua Prioritas</option>
                        <option value="low">Rendah</option>
                        <option value="medium">Sedang</option>
                        <option value="high">Tinggi</option>
                    </select>
                    <button class="tm-btn-filter">Filter</button>
                </div>
            </div>

            <!-- task list -->
            <div class="tm-tasks">
                <!-- task item template (akan di-populate dari backend) -->
                <div class="tm-task-item tm-task-pending tm-task-high">
                    <div class="tm-task-header">
                        <div class="tm-task-checkbox">
                            <input type="checkbox" class="tm-checkbox" data-task-id="1">
                        </div>
                        <div class="tm-task-title-group">
                            <h3 class="tm-task-title">Buat laporan mingguan</h3>
                            <p class="tm-task-description">Laporan performa sistem untuk minggu ini</p>
                        </div>
                        <div class="tm-task-meta">
                            <span class="tm-priority-badge tm-priority-high">Tinggi</span>
                            <span class="tm-status-badge tm-status-pending">Pending</span>
                        </div>
                    </div>
                    <div class="tm-task-footer">
                        <span class="tm-due-date"><i data-lucide="calendar"></i> 20 Feb 2026</span>
                        <div class="tm-task-actions">
                            <button class="tm-btn-action" data-action="edit" title="Edit"><i data-lucide="edit-2"></i></button>
                            <button class="tm-btn-action" data-action="delete" title="Hapus"><i data-lucide="trash-2"></i></button>
                        </div>
                    </div>
                </div>

                <!-- empty state template -->
                <div class="tm-empty-state" style="display: none;">
                    <div class="tm-empty-icon"><i data-lucide="inbox"></i></div>
                    <h3>Tidak Ada Tugas</h3>
                    <p>Mulai buat tugas pertama kamu sekarang</p>
                    <button class="tm-btn-create tm-btn-empty" data-action="create-task">
                        <span class="tm-btn-icon">+</span>
                        Buat Tugas Baru
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- modals (akan di-implementasi di backend) -->
    <!-- create/edit modal akan di-inject via backend -->

    <script>
        // Simple event delegation untuk buttons (akan di-integrate dengan backend)
        document.addEventListener('click', function(e) {
            const action = e.target.closest('[data-action]')?.dataset.action;
            
            if (action === 'create-task') {
                console.log('Create task action - akan di-handle oleh backend');
                // Nanti akan di-redirect ke create form
            } else if (action === 'edit') {
                console.log('Edit task action - akan di-handle oleh backend');
                // Nanti akan di-redirect ke edit form
            } else if (action === 'delete') {
                console.log('Delete task action - akan di-handle oleh backend');
                // Nanti akan di-delete via backend
            }
        });

        // Filter handler (akan di-integrate dengan backend)
        document.querySelectorAll('[data-filter]').forEach(el => {
            el.addEventListener('change', function() {
                console.log('Filter changed - akan di-send ke backend');
                // Nanti akan fetch data dari backend dengan filter ini
            });
        });

        // Search handler (akan di-integrate dengan backend)
        document.querySelector('[data-filter="search"]')?.addEventListener('input', function() {
            console.log('Search input - akan di-send ke backend');
            // Nanti akan fetch data dari backend dengan search ini
        });

        // Initialize Lucide icons
        lucide.createIcons();
    </script>

</body>
</html>
