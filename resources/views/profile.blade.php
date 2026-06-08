<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile - ProductivityFlow</title>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<div class="dashboard-layout">

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Navbar -->
    <header class="navbar-top">
        <div class="navbar-content">
            <div class="navbar-left">
                <h1 class="page-title">My Profile</h1>
            </div>
            <div class="navbar-right">
                <div class="profile-dropdown-container">
                    <button class="profile-btn" id="profileBtn">
                        <img src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(Auth::user()->email))).'?s=40&d=identicon' }}" alt="Profile" class="profile-avatar-small">
                        <span class="profile-name">{{ Auth::user()->name }}</span>
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <div class="dropdown-menu" id="profileMenu">
                        <a href="/profile" class="dropdown-item"><i class='bx bx-user'></i><span>Profile</span></a>
                        <hr class="dropdown-divider">
                        <form method="POST" action="{{ route('logout') }}" style="width:100%;">
                            @csrf
                            <button type="submit" class="dropdown-item dropdown-item-logout"><i class='bx bx-log-out'></i><span>Logout</span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="profile-main">

        <!-- Hero Banner -->
        <div class="profile-hero">
            <div class="hero-glow hero-glow-1"></div>
            <div class="hero-glow hero-glow-2"></div>

            <div class="hero-inner">
                <div class="hero-left">
                    <div class="avatar-ring-wrapper">
                        <div class="avatar-ring">
                            <img src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(Auth::user()->email))).'?s=120&d=identicon' }}"
                                alt="Avatar" class="hero-avatar" id="heroAvatar">
                        </div>
                        <div class="online-dot"></div>
                    </div>
                    <div class="hero-info">
                        <div class="hero-name-row">
                            <h2 class="hero-name">{{ Auth::user()->name }}</h2>
                            @if(Auth::user()->role === 'admin')
                                <span class="role-chip chip-admin"><i class='bx bx-shield-quarter'></i> Admin</span>
                            @else
                                <span class="role-chip chip-member"><i class='bx bx-user-check'></i> Member</span>
                            @endif
                        </div>
                        <p class="hero-email"><i class='bx bx-envelope'></i> {{ Auth::user()->email }}</p>
                        <p class="hero-since"><i class='bx bx-calendar'></i> Member sejak {{ Auth::user()->created_at->format('d F Y') }}</p>
                    </div>
                </div>

                <div class="hero-right">
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <div class="hero-stat-num" id="statTotal">—</div>
                            <div class="hero-stat-label">Total Tasks</div>
                        </div>
                        <div class="hero-stat-sep"></div>
                        <div class="hero-stat">
                            <div class="hero-stat-num green" id="statCompleted">—</div>
                            <div class="hero-stat-label">Selesai</div>
                        </div>
                        <div class="hero-stat-sep"></div>
                        <div class="hero-stat">
                            <div class="hero-stat-num orange" id="statPending">—</div>
                            <div class="hero-stat-label">Pending</div>
                        </div>
                    </div>
                    <button class="btn-edit-profile" id="openEditModal">
                        <i class='bx bx-edit'></i> Edit Profile
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Grid -->
        <div class="profile-grid">

            <!-- Account Details Card -->
            <div class="pcard">
                <div class="pcard-header">
                    <div class="pcard-icon-wrap"><i class='bx bx-id-card'></i></div>
                    <h3>Account Details</h3>
                </div>
                <div class="detail-list">
                    <div class="detail-row">
                        <div class="detail-key"><i class='bx bx-user'></i> Username</div>
                        <div class="detail-val">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key"><i class='bx bx-envelope'></i> Email</div>
                        <div class="detail-val email-val">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key"><i class='bx bx-shield'></i> Role</div>
                        <div class="detail-val">
                            @if(Auth::user()->role === 'admin')
                                <span class="badge-pill badge-gold">Admin</span>
                            @else
                                <span class="badge-pill badge-indigo">Member</span>
                            @endif
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key"><i class='bx bx-calendar-check'></i> Bergabung</div>
                        <div class="detail-val">{{ Auth::user()->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key"><i class='bx bx-time'></i> Last Activity</div>
                        <div class="detail-val">{{ Auth::user()->updated_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>

            <!-- Activity & Quick Links -->
            <div class="pcard-col">

                <!-- Progress Card -->
                <div class="pcard pcard-progress">
                    <div class="pcard-header">
                        <div class="pcard-icon-wrap"><i class='bx bx-trending-up'></i></div>
                        <h3>Completion Rate</h3>
                    </div>
                    <div class="progress-ring-wrap">
                        <svg class="progress-ring" width="110" height="110">
                            <circle class="ring-track" cx="55" cy="55" r="45"/>
                            <circle class="ring-fill" cx="55" cy="55" r="45" id="ringFill"/>
                        </svg>
                        <div class="ring-label">
                            <span class="ring-pct" id="ringPct">—</span>
                            <span class="ring-sub">Done</span>
                        </div>
                    </div>
                    <div class="progress-breakdown">
                        <div class="pb-item">
                            <span class="pb-dot dot-green"></span>
                            <span class="pb-label">Selesai</span>
                            <span class="pb-val" id="pbDone">—</span>
                        </div>
                        <div class="pb-item">
                            <span class="pb-dot dot-orange"></span>
                            <span class="pb-label">Pending</span>
                            <span class="pb-val" id="pbPend">—</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Nav -->
                <div class="pcard">
                    <div class="pcard-header">
                        <div class="pcard-icon-wrap"><i class='bx bx-compass'></i></div>
                        <h3>Quick Navigation</h3>
                    </div>
                    <div class="quick-nav-list">
                        <a href="/dashboard" class="quick-nav-item">
                            <div class="qn-icon qn-blue"><i class='bx bx-home'></i></div>
                            <div class="qn-info"><span class="qn-title">Dashboard</span><span class="qn-sub">Overview & stats</span></div>
                            <i class='bx bx-chevron-right qn-arrow'></i>
                        </a>
                        <a href="/task-manager" class="quick-nav-item">
                            <div class="qn-icon qn-green"><i class='bx bx-task'></i></div>
                            <div class="qn-info"><span class="qn-title">Task Manager</span><span class="qn-sub">Manage your tasks</span></div>
                            <i class='bx bx-chevron-right qn-arrow'></i>
                        </a>
                        <a href="/focus" class="quick-nav-item">
                            <div class="qn-icon qn-purple"><i class='bx bx-target-lock'></i></div>
                            <div class="qn-info"><span class="qn-title">Focus Mode</span><span class="qn-sub">Pomodoro timer</span></div>
                            <i class='bx bx-chevron-right qn-arrow'></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

<!-- ===================== EDIT PROFILE MODAL ===================== -->
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title-wrap">
                <div class="modal-icon"><i class='bx bx-edit'></i></div>
                <div>
                    <h2 class="modal-title">Edit Profile</h2>
                    <p class="modal-subtitle">Update your personal information</p>
                </div>
            </div>
            <button class="modal-close-btn" id="closeEditModal"><i class='bx bx-x'></i></button>
        </div>

        <div class="modal-body">
            <!-- Avatar Upload -->
            <div class="modal-avatar-section">
                <div class="modal-avatar-wrap">
                    <img src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(Auth::user()->email))).'?s=80&d=identicon' }}"
                        alt="Avatar" class="modal-avatar-img" id="modalAvatarPreview">
                    <div class="modal-avatar-overlay">
                        <i class='bx bx-camera'></i>
                    </div>
                </div>
                <div class="modal-avatar-info">
                    <p class="modal-avatar-name">{{ Auth::user()->name }}</p>
                    <p class="modal-avatar-hint">Click avatar to change photo</p>
                </div>
            </div>

            <!-- Alert -->
            <div class="modal-alert" id="modalAlert" style="display:none;"></div>

            <!-- Form -->
            <div class="modal-form-grid">
                <div class="mform-group">
                    <label>Full Name</label>
                    <div class="minput-wrap">
                        <i class='bx bx-user minput-icon'></i>
                        <input type="text" class="minput" id="editName" value="{{ Auth::user()->name }}" placeholder="Your full name">
                    </div>
                </div>
                <div class="mform-group">
                    <label>Email Address</label>
                    <div class="minput-wrap">
                        <i class='bx bx-envelope minput-icon'></i>
                        <input type="email" class="minput" id="editEmail" value="{{ Auth::user()->email }}" placeholder="your@email.com">
                    </div>
                </div>
            </div>

            <div class="modal-divider"><span>Security</span></div>

            <div class="modal-form-grid">
                <div class="mform-group">
                    <label>Current Password</label>
                    <div class="minput-wrap">
                        <i class='bx bx-lock minput-icon'></i>
                        <input type="password" class="minput" id="editCurrentPw" placeholder="Enter current password">
                        <button type="button" class="mpw-toggle" data-target="editCurrentPw"><i class='bx bx-hide'></i></button>
                    </div>
                </div>
                <div class="mform-group">
                    <label>New Password</label>
                    <div class="minput-wrap">
                        <i class='bx bx-lock-open minput-icon'></i>
                        <input type="password" class="minput" id="editNewPw" placeholder="New password (min 8 chars)">
                        <button type="button" class="mpw-toggle" data-target="editNewPw"><i class='bx bx-hide'></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="mbtn-cancel" id="cancelEditModal">Cancel</button>
            <button class="mbtn-save" id="saveEditProfile">
                <i class='bx bx-save'></i> Save Changes
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    // Profile dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    profileBtn?.addEventListener('click', e => { e.stopPropagation(); profileBtn.classList.toggle('active'); profileMenu.classList.toggle('active'); });
    document.addEventListener('click', () => { profileBtn?.classList.remove('active'); profileMenu?.classList.remove('active'); });

    // Fetch stats
    fetch('/api/tasks/stats', { headers: { 'X-CSRF-TOKEN': csrf } })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                const d = res.data;
                document.getElementById('statTotal').textContent = d.total;
                document.getElementById('statCompleted').textContent = d.completed;
                document.getElementById('statPending').textContent = d.pending;
                document.getElementById('pbDone').textContent = d.completed;
                document.getElementById('pbPend').textContent = d.pending;

                // Ring
                const pct = d.total > 0 ? Math.round((d.completed / d.total) * 100) : 0;
                document.getElementById('ringPct').textContent = pct + '%';
                const ring = document.getElementById('ringFill');
                const circ = 2 * Math.PI * 45;
                ring.style.strokeDasharray = circ;
                ring.style.strokeDashoffset = circ - (pct / 100) * circ;
            }
        })
        .catch(() => {});

    // Modal open/close
    const editModal = document.getElementById('editModal');
    document.getElementById('openEditModal').addEventListener('click', () => openModal());
    document.getElementById('closeEditModal').addEventListener('click', () => closeModal());
    document.getElementById('cancelEditModal').addEventListener('click', () => closeModal());
    editModal.addEventListener('click', e => { if (e.target === editModal) closeModal(); });

    function openModal() {
        editModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        editModal.classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('modalAlert').style.display = 'none';
    }

    // Password toggle
    document.querySelectorAll('.mpw-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            const icon = this.querySelector('i');
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.className = input.type === 'password' ? 'bx bx-hide' : 'bx bx-show';
        });
    });

    // Save profile (demo)
    document.getElementById('saveEditProfile').addEventListener('click', function () {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = "<i class='bx bx-loader-alt bx-spin'></i> Saving...";

        setTimeout(() => {
            showAlert('success', '<i class="bx bx-check-circle"></i> Profile updated successfully!');
            btn.disabled = false;
            btn.innerHTML = "<i class='bx bx-save'></i> Save Changes";
            setTimeout(() => closeModal(), 1500);
        }, 1200);
    });

    function showAlert(type, html) {
        const el = document.getElementById('modalAlert');
        el.className = 'modal-alert alert-' + type;
        el.innerHTML = html;
        el.style.display = 'flex';
    }

    // Escape key
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
});
</script>
</body>
</html>
