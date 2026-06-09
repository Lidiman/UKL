<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard – ProductivityFlow</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-layout">

    {{-- ── Admin Sidebar ──────────────────────────────────── --}}
    <aside class="admin-sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon"><i class='bx bx-bolt'></i></div>
            <div>
                <div class="logo-text">ProductivityFlow</div>
                <div class="logo-badge">ADMIN</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-section-label">Management</span>
            <a href="/admin" class="nav-item active">
                <i class='bx bx-shield-quarter'></i><span>Admin Dashboard</span>
            </a>
            <a href="/admin/users" class="nav-item">
                <i class='bx bx-group'></i><span>Users</span>
            </a>
            <a href="/admin/tasks" class="nav-item">
                <i class='bx bx-task'></i><span>Tasks</span>
            </a>

            <span class="nav-section-label" style="margin-top:.75rem;">User Area</span>
            <a href="/dashboard" class="nav-item">
                <i class='bx bx-home'></i><span>Dashboard</span>
            </a>
            <a href="/analytics" class="nav-item">
                <i class='bx bx-bar-chart-alt-2'></i><span>Analytics</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="admin-info">
                <img src="{{ 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(Auth::user()->email))).'?s=36&d=identicon' }}"
                     alt="Admin" class="admin-avatar">
                <div>
                    <div class="admin-name">{{ Auth::user()->name }}</div>
                    <div class="admin-role">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="padding:.5rem;">
                @csrf
                <button type="submit" class="nav-item danger" style="width:100%;border:none;cursor:pointer;background:none;font-family:inherit;">
                    <i class='bx bx-log-out'></i><span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Top Navbar ─────────────────────────────────────── --}}
    <header class="admin-navbar">
        <h1 class="page-title">Admin Dashboard</h1>
        <div class="navbar-right">
            <div class="admin-chip"><i class='bx bx-shield-quarter'></i> Admin Panel</div>
            <div class="profile-dropdown-container">
                <button class="profile-btn" id="profileBtn">
                    <img src="{{ 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(Auth::user()->email))).'?s=28&d=identicon' }}"
                         alt="Admin" class="profile-avatar-small">
                    <span>{{ Auth::user()->name }}</span>
                    <i class='bx bx-chevron-down'></i>
                </button>
                <div class="dropdown-menu" id="profileMenu">
                    <a href="/profile" class="dropdown-item"><i class='bx bx-user'></i> Profile</a>
                    <hr class="dropdown-divider">
                    <form method="POST" action="{{ route('logout') }}" style="width:100%;">
                        @csrf
                        <button type="submit" class="dropdown-item danger"><i class='bx bx-log-out'></i> Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- ── Main Content ────────────────────────────────────── --}}
    <main class="admin-main">

        {{-- Stats Row --}}
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon"><i class='bx bx-group'></i></div>
                <div class="stat-label">Total Users</div>
                <div class="stat-value" id="statUsers">—</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class='bx bx-folder'></i></div>
                <div class="stat-label">Total Projects</div>
                <div class="stat-value" id="statProjects">—</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class='bx bx-task'></i></div>
                <div class="stat-label">Total Tasks</div>
                <div class="stat-value" id="statTasks">—</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class='bx bx-check-circle'></i></div>
                <div class="stat-label">Completed</div>
                <div class="stat-value" id="statCompleted">—</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class='bx bx-trending-up'></i></div>
                <div class="stat-label">Completion Rate</div>
                <div class="stat-value" id="statRate">—</div>
            </div>
        </div>

        {{-- Search Section --}}
        <div class="search-section">
            <div class="section-title"><i class='bx bx-search-alt'></i> Cari User</div>
            <div class="search-row">
                <div class="search-input-wrap">
                    <i class='bx bx-search'></i>
                    <input type="text" id="searchField" class="search-field"
                           placeholder="Cari by ID, nama, atau email...">
                </div>
                <button class="btn-search" id="btnSearch">
                    <i class='bx bx-search-alt'></i> Cari
                </button>
                <button class="btn-clear" id="btnClear">Reset</button>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="table-card">
            <div class="table-header-row">
                <div class="section-title" style="margin-bottom:0;"><i class='bx bx-group'></i> Semua User</div>
                <div style="display:flex;align-items:center;gap:1rem;">
                    <div class="user-count" id="userCountLabel"></div>
                    <button class="btn-search" onclick="openUserModal()" style="padding:.4rem 1rem;font-size:.8rem;"><i class='bx bx-plus'></i> Tambah</button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Tasks</th>
                        <th>Projects</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <tr class="empty-row"><td colspan="7">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>

    </main>
</div>

{{-- ── Drawer Overlay ──────────────────────────────────── --}}
<div class="drawer-overlay" id="drawerOverlay"></div>

{{-- ── User Detail Drawer ──────────────────────────────── --}}
<div class="detail-drawer" id="detailDrawer">
    <div class="drawer-header">
        <span class="drawer-title" id="drawerTitle">User Detail</span>
        <button class="drawer-close" id="drawerClose"><i class='bx bx-x'></i></button>
    </div>
    <div class="drawer-body" id="drawerBody">
        <div class="drawer-loading">
            <div class="spinner"></div>
            <span>Memuat data user...</span>
        </div>
    </div>
</div>

{{-- ── User Form Modal ─────────────────────────────────── --}}
<div class="modal-overlay" id="userModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title" id="userModalTitle">Tambah User</div>
            <button class="modal-close" onclick="closeUserModal()"><i class='bx bx-x'></i></button>
        </div>
        <form id="userForm">
            <div class="modal-body">
                <input type="hidden" id="userId">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" id="userName" required placeholder="Nama lengkap">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="userEmail" required placeholder="email@example.com">
                </div>
                <div class="form-group">
                    <label>Password <span id="passwordHint" style="font-weight:normal;color:var(--text-tertiary);font-size:.75rem;">(Wajib diisi)</span></label>
                    <input type="password" id="userPassword" placeholder="Masukkan password">
                </div>
                <label class="form-checkbox">
                    <input type="checkbox" id="userIsAdmin">
                    <span>Jadikan sebagai Admin</span>
                </label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeUserModal()">Batal</button>
                <button type="submit" class="btn-save" id="btnSaveUser">Simpan User</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Toast ──────────────────────────────────────────── --}}
<div class="toast" id="toast"></div>

<script>
const CSRF  = document.querySelector('meta[name="csrf-token"]').content;
const FETCH_OPTS = { credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } };

// ── Profile dropdown ─────────────────────────────────────────────────────────
const profileBtn  = document.getElementById('profileBtn');
const profileMenu = document.getElementById('profileMenu');
profileBtn?.addEventListener('click', e => {
    e.stopPropagation();
    profileMenu.classList.toggle('active');
    profileBtn.classList.toggle('active');
});
document.addEventListener('click', () => { profileMenu?.classList.remove('active'); profileBtn?.classList.remove('active'); });

// ── Toast ─────────────────────────────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.className = 'toast ' + type;
    el.innerHTML = `<i class='bx bx-${type === 'success' ? 'check-circle' : 'error-circle'}'></i> ${msg}`;
    el.classList.add('show');
    setTimeout(() => el.classList.remove('show'), 3200);
}

// ── Fetch global stats ────────────────────────────────────────────────────────
async function loadStats() {
    try {
        const res  = await fetch('/web-api/admin/stats', FETCH_OPTS);
        const data = await res.json();
        if (data.success) {
            const d = data.data;
            document.getElementById('statUsers').textContent     = d.total_users;
            document.getElementById('statProjects').textContent  = d.total_projects;
            document.getElementById('statTasks').textContent     = d.total_tasks;
            document.getElementById('statCompleted').textContent = d.completed_tasks;
            document.getElementById('statRate').textContent      = d.completion_rate + '%';
        }
    } catch { /* silent */ }
}

// ── Render badge ──────────────────────────────────────────────────────────────
function badgeStatus(s) {
    const map = {
        completed: 'badge-completed',
        pending: 'badge-pending',
        'in-progress': 'badge-inprogress',
    };
    return `<span class="badge ${map[s] || 'badge-pending'}">${s}</span>`;
}
function badgePriority(p) {
    const map = { high: 'badge-high', medium: 'badge-medium', low: 'badge-low' };
    return `<span class="badge ${map[p] || 'badge-low'}">${p}</span>`;
}
function badgeRole(isAdmin) {
    return isAdmin
        ? `<span class="badge badge-admin"><i class='bx bx-shield-quarter'></i> Admin</span>`
        : `<span class="badge badge-member"><i class='bx bx-user'></i> Member</span>`;
}
function gravatar(email, size = 34) {
    const hash = [...email.toLowerCase().trim()].reduce((a, c) => {
        // simple hash placeholder – actual md5 computed server-side; use gravatar url
        return a;
    }, '');
    return `https://www.gravatar.com/avatar/?s=${size}&d=identicon`;
}
function gravatarUrl(email, size = 34) {
    // We'll use the email as seed for gravatar
    return `https://www.gravatar.com/avatar/${btoa(email.toLowerCase().trim()).replace(/=/g,'')}?s=${size}&d=identicon`;
}
function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

// ── Load users ────────────────────────────────────────────────────────────────
let allUsersData = [];

async function loadUsers(search = '') {
    const tbody = document.getElementById('usersTableBody');
    tbody.innerHTML = `<tr class="empty-row"><td colspan="7">Memuat...</td></tr>`;

    try {
        const url = '/web-api/admin/users' + (search ? `?search=${encodeURIComponent(search)}` : '');
        const res  = await fetch(url, FETCH_OPTS);
        const data = await res.json();

        if (!data.success) throw new Error(data.message);

        const users = data.data;
        allUsersData = users;
        document.getElementById('userCountLabel').textContent = `${users.length} user ditemukan`;

        if (users.length === 0) {
            tbody.innerHTML = `<tr class="empty-row"><td colspan="7"><i class='bx bx-search' style="font-size:2rem;opacity:.3"></i><br>Tidak ada user yang cocok</td></tr>`;
            return;
        }

        tbody.innerHTML = users.map(u => `
            <tr onclick="openUserDetail(${u.id}, '${u.name.replace(/'/g, "\\'")}')">
                <td style="color:var(--text-tertiary);font-size:.8rem">#${u.id}</td>
                <td>
                    <div class="user-cell">
                        <img src="https://www.gravatar.com/avatar/?s=34&d=identicon&seed=${u.id}" class="user-avatar" alt="${u.name}">
                        <div>
                            <div class="user-name">${u.name}</div>
                            <div class="user-email">${u.email}</div>
                        </div>
                    </div>
                </td>
                <td>${badgeRole(u.is_admin)}</td>
                <td><span style="font-weight:600;color:var(--text-primary)">${u.tasks_count}</span> <span style="color:var(--text-tertiary);font-size:.78rem">tasks</span></td>
                <td><span style="font-weight:600;color:var(--text-primary)">${u.projects_count}</span> <span style="color:var(--text-tertiary);font-size:.78rem">projects</span></td>
                <td style="color:var(--text-tertiary);font-size:.8rem">${formatDate(u.created_at)}</td>
                <td>
                    <div style="display:flex;gap:.4rem;">
                        <button class="view-btn" onclick="event.stopPropagation();openUserDetail(${u.id}, '${u.name.replace(/'/g, "\\'")}')" title="Detail User">
                            <i class='bx bx-show'></i>
                        </button>
                        <button class="btn-action edit" onclick="event.stopPropagation();openUserModal(${u.id})" title="Edit User">
                            <i class='bx bx-edit-alt'></i>
                        </button>
                        <button class="btn-action delete" onclick="event.stopPropagation();deleteUser(${u.id}, '${u.name.replace(/'/g, "\\'")}')" title="Hapus User">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    } catch (e) {
        tbody.innerHTML = `<tr class="empty-row"><td colspan="7">Gagal memuat data: ${e.message}</td></tr>`;
        showToast('Gagal memuat users: ' + e.message, 'error');
    }
}

// ── Open user detail drawer ────────────────────────────────────────────────────
async function openUserDetail(userId, userName) {
    document.getElementById('drawerTitle').textContent = 'Detail: ' + userName;
    document.getElementById('drawerBody').innerHTML = `
        <div class="drawer-loading">
            <div class="spinner"></div>
            <span>Memuat data ${userName}...</span>
        </div>`;
    document.getElementById('detailDrawer').classList.add('active');
    document.getElementById('drawerOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';

    try {
        const res  = await fetch(`/web-api/admin/users/${userId}/detail`, FETCH_OPTS);
        const data = await res.json();
        if (!data.success) throw new Error(data.message);

        renderDrawer(data.data);
    } catch (e) {
        document.getElementById('drawerBody').innerHTML = `
            <div class="drawer-loading">
                <i class='bx bx-error-circle' style="font-size:2.5rem;color:var(--danger)"></i>
                <span>Gagal memuat: ${e.message}</span>
            </div>`;
    }
}

// ── Render drawer content ─────────────────────────────────────────────────────
function renderDrawer(d) {
    const u   = d.user;
    const ts  = d.task_stats;
    const pct = ts.completion_rate;
    const circ = 2 * Math.PI * 36;
    const offset = circ - (pct / 100) * circ;

    const taskRows = d.tasks.length === 0
        ? `<div style="text-align:center;padding:1.5rem;color:var(--text-tertiary)">Belum ada task</div>`
        : d.tasks.map(t => {
            const dotClass = t.status === 'completed' ? 'dot-completed' : t.status === 'in-progress' ? 'dot-inprogress' : 'dot-pending';
            return `
            <div class="task-row">
                <div class="task-dot ${dotClass}"></div>
                <div class="task-row-info">
                    <div class="task-row-title" title="${t.title}">${t.title}</div>
                    <div class="task-row-meta">
                        ${badgePriority(t.priority)}
                        <span>·</span>
                        <span>${t.is_single_task ? '<i class=\'bx bx-task\'></i> Single' : '<i class=\'bx bx-folder\'></i> Project Task'}</span>
                        ${t.due_date ? `<span>· Due ${formatDate(t.due_date)}</span>` : ''}
                    </div>
                </div>
                ${badgeStatus(t.status)}
            </div>`;
        }).join('');

    const projectRows = d.projects.length === 0
        ? `<div style="text-align:center;padding:1.5rem;color:var(--text-tertiary)">Belum ada project</div>`
        : d.projects.map(p => `
            <div class="project-row">
                <div class="project-row-header">
                    <span class="project-row-name"><i class='bx bx-folder' style="color:var(--primary)"></i> ${p.name}</span>
                    <span class="project-row-pct">${p.completion_rate}%</span>
                </div>
                <div class="project-bar-track">
                    <div class="project-bar-fill" style="width:${p.completion_rate}%"></div>
                </div>
                <div class="project-row-footer">
                    <span class="project-row-meta"><i class='bx bx-task'></i> ${p.total_tasks} tasks</span>
                    <span class="project-row-meta"><i class='bx bx-check-circle' style="color:var(--success)"></i> ${p.completed_tasks} selesai</span>
                    <span class="project-row-meta"><i class='bx bx-circle' style="color:var(--text-tertiary)"></i> ${p.status}</span>
                </div>
            </div>`).join('');

    document.getElementById('drawerBody').innerHTML = `
        {{-- User Hero --}}
        <div class="user-hero">
            <img src="https://www.gravatar.com/avatar/?s=64&d=identicon&seed=${u.id}" class="user-hero-avatar" alt="${u.name}">
            <div class="user-hero-info">
                <div class="user-hero-name">${u.name}</div>
                <div class="user-hero-email">${u.email}</div>
                <div class="user-hero-meta">
                    ${badgeRole(u.is_admin)}
                    <span class="user-hero-stat"><i class='bx bx-calendar'></i> Bergabung ${u.created_at}</span>
                    <span class="user-hero-stat"><i class='bx bx-time'></i> Aktif ${u.updated_at}</span>
                </div>
            </div>
        </div>

        {{-- Mini stats --}}
        <div class="mini-stats-row">
            <div class="mini-stat-card">
                <div class="mini-stat-value">${ts.total}</div>
                <div class="mini-stat-label">Total Tasks</div>
            </div>
            <div class="mini-stat-card">
                <div class="mini-stat-value green">${ts.completed}</div>
                <div class="mini-stat-label">Selesai</div>
            </div>
            <div class="mini-stat-card">
                <div class="mini-stat-value orange">${ts.pending}</div>
                <div class="mini-stat-label">Pending</div>
            </div>
        </div>

        {{-- Completion Ring --}}
        <div class="ring-wrap">
            <svg class="ring-svg" width="88" height="88">
                <defs>
                    <linearGradient id="ringGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#6366F1"/>
                        <stop offset="100%" style="stop-color:#8B5CF6"/>
                    </linearGradient>
                </defs>
                <circle class="ring-track" cx="44" cy="44" r="36"/>
                <circle class="ring-fill" cx="44" cy="44" r="36"
                    style="stroke-dasharray:${circ};stroke-dashoffset:${offset}"/>
            </svg>
            <div class="ring-info">
                <div class="ring-pct-text">${pct}%</div>
                <div class="ring-sub">Task Completion Rate</div>
                <div style="margin-top:.4rem;font-size:.75rem;color:var(--text-tertiary)">
                    ${ts.single} single tasks &nbsp;·&nbsp; ${ts.in_project} project tasks
                </div>
            </div>
        </div>

        {{-- Projects --}}
        <div>
            <div class="drawer-section-title"><i class='bx bx-folder'></i> Projects (${d.projects.length})</div>
            <div class="project-list-drawer">${projectRows}</div>
        </div>

        {{-- Tasks --}}
        <div>
            <div class="drawer-section-title"><i class='bx bx-task'></i> Tasks (${d.tasks.length})</div>
            <div class="task-list-drawer">${taskRows}</div>
        </div>
    `;
}

// ── Close drawer ──────────────────────────────────────────────────────────────
function closeDrawer() {
    document.getElementById('detailDrawer').classList.remove('active');
    document.getElementById('drawerOverlay').classList.remove('active');
    document.body.style.overflow = '';
}
document.getElementById('drawerClose').addEventListener('click', closeDrawer);
document.getElementById('drawerOverlay').addEventListener('click', closeDrawer);
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

// ── Search ────────────────────────────────────────────────────────────────────
document.getElementById('btnSearch').addEventListener('click', () => {
    loadUsers(document.getElementById('searchField').value.trim());
});
document.getElementById('searchField').addEventListener('keydown', e => {
    if (e.key === 'Enter') loadUsers(e.target.value.trim());
});
document.getElementById('btnClear').addEventListener('click', () => {
    document.getElementById('searchField').value = '';
    loadUsers();
});

// ── CRUD Users ─────────────────────────────────────────────────────────────────
function openUserModal(userId = null) {
    const modal = document.getElementById('userModal');
    const form = document.getElementById('userForm');
    form.reset();
    document.getElementById('userId').value = '';
    
    const title = document.getElementById('userModalTitle');
    const hint = document.getElementById('passwordHint');
    
    if (userId) {
        const user = allUsersData.find(u => u.id === userId);
        if (user) {
            title.textContent = 'Edit User';
            document.getElementById('userId').value = user.id;
            document.getElementById('userName').value = user.name;
            document.getElementById('userEmail').value = user.email;
            document.getElementById('userIsAdmin').checked = user.is_admin;
            hint.textContent = '(Kosongkan jika tidak ingin ganti)';
            document.getElementById('userPassword').removeAttribute('required');
        }
    } else {
        title.textContent = 'Tambah User';
        hint.textContent = '(Wajib diisi)';
        document.getElementById('userPassword').setAttribute('required', 'required');
    }
    
    modal.classList.add('active');
}

function closeUserModal() {
    document.getElementById('userModal').classList.remove('active');
}

document.getElementById('userForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('btnSaveUser');
    btn.disabled = true;
    btn.innerHTML = `<i class='bx bx-loader-alt bx-spin'></i> Menyimpan...`;
    
    const userId = document.getElementById('userId').value;
    const data = {
        name: document.getElementById('userName').value,
        email: document.getElementById('userEmail').value,
        is_admin: document.getElementById('userIsAdmin').checked ? 1 : 0
    };
    
    const pwd = document.getElementById('userPassword').value;
    if (pwd) data.password = pwd;
    
    try {
        const url = userId ? `/web-api/admin/users/${userId}` : '/web-api/admin/users';
        const method = userId ? 'PUT' : 'POST';
        
        const res = await fetch(url, {
            ...FETCH_OPTS,
            method,
            headers: { ...FETCH_OPTS.headers, 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await res.json();
        
        if (result.success) {
            showToast(`User berhasil ${userId ? 'diperbarui' : 'ditambahkan'}`);
            closeUserModal();
            loadUsers();
            loadStats(); // Update totals
        } else {
            showToast(result.message || 'Gagal menyimpan user', 'error');
        }
    } catch (err) {
        showToast('Terjadi kesalahan koneksi', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Simpan User';
    }
});

async function deleteUser(userId, userName) {
    if (!confirm(`Apakah Anda yakin ingin menghapus user "${userName}"? Semua task/project mereka juga akan terhapus.`)) return;
    
    try {
        const res = await fetch(`/web-api/admin/users/${userId}`, { ...FETCH_OPTS, method: 'DELETE' });
        const result = await res.json();
        if (result.success) {
            showToast('User berhasil dihapus');
            loadUsers();
            loadStats();
        } else {
            showToast(result.message || 'Gagal menghapus user', 'error');
        }
    } catch (err) {
        showToast('Terjadi kesalahan saat menghapus', 'error');
    }
}

// ── Init ──────────────────────────────────────────────────────────────────────
loadStats();
loadUsers();
</script>
</body>
</html>
