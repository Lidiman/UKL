<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Analytics - Weekly Summary | ProductivityFlow</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard-final.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/analytics.css') }}">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<div class="dashboard-layout">

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Top Navbar -->
    <header class="navbar-top">
        <div class="navbar-content">
            <div class="navbar-left">
                <h1 class="page-title">Analytics</h1>
            </div>
            <div class="navbar-center">
                <div class="search-box">
                    <i class='bx bx-search'></i>
                    <input type="text" class="search-input" placeholder="Search...">
                </div>
            </div>
            <div class="navbar-right">
                <button class="notification-btn" id="notificationBtn">
                    <i class='bx bx-bell'></i>
                    <span class="notification-badge">3</span>
                </button>
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
                    <div class="dropdown-menu" id="profileMenu">
                        <a href="/profile" class="dropdown-item">
                            <i class='bx bx-user'></i>
                            <span>Profile</span>
                        </a>
                        <hr class="dropdown-divider">
                        <form method="POST" action="{{ route('logout') }}" style="width:100%">
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

    <!-- Main Content -->
    <main class="main-content" style="grid-column:2; grid-row:2; display:flex; flex-direction:column; gap:0;">
        <div class="analytics-content">

            <!-- ===== PAGE HEADER ===== -->
            <div class="analytics-header">
                <div class="analytics-header-top">
                    <div class="analytics-title-group">
                        <h2 class="analytics-title">
                            <i class='bx bx-line-chart'></i>
                            Weekly Summary
                        </h2>
                        <p class="analytics-subtitle">
                            Review your weekly performance, identify improvement areas, and plan a better week ahead.
                        </p>
                        <div class="analytics-badges">
                            <span class="a-badge a-badge-primary">
                                <i class='bx bx-calendar-week'></i>
                                Weekly Review
                            </span>
                            <span class="a-badge a-badge-green">
                                <i class='bx bx-trending-up'></i>
                                Continuous Improvement
                            </span>
                        </div>
                    </div>

                    <div class="analytics-week-selector">
                        <button class="week-nav-btn" id="prevWeekBtn" title="Previous week">
                            <i class='bx bx-chevron-left'></i>
                        </button>
                        <span class="week-label" id="weekLabel">Loading...</span>
                        <button class="week-nav-btn" id="nextWeekBtn" title="Next week">
                            <i class='bx bx-chevron-right'></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ===== SUMMARY STAT CARDS ===== -->
            <div class="stats-overview">
                <div class="stat-card">
                    <div class="stat-card-icon icon-green">
                        <i class='bx bx-check-circle'></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-value" id="statGoals">0</div>
                        <div class="stat-card-label">Goals Completed</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon icon-indigo">
                        <i class='bx bx-task'></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-value" id="statTasks">0</div>
                        <div class="stat-card-label">Tasks Finished</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon icon-amber">
                        <i class='bx bx-bar-chart-alt-2'></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-value" id="statScore">0%</div>
                        <div class="stat-card-label">Productivity Score</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon icon-blue">
                        <i class='bx bx-target-lock'></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-value" id="statFocus">0</div>
                        <div class="stat-card-label">Focus Sessions</div>
                    </div>
                </div>
            </div>

            <!-- ===== REFLECTION + PLANNING ===== -->
            <div class="analytics-two-col">

                <!-- Weekly Reflection -->
                <div class="a-card">
                    <div class="a-card-header">
                        <h3 class="a-card-title">
                            <i class='bx bx-book-open'></i>
                            Weekly Reflection
                        </h3>
                        <span class="a-card-meta">3 areas to review</span>
                    </div>

                    <div class="reflection-sections">
                        <!-- What Went Well -->
                        <div class="reflection-block">
                            <label class="reflection-label" for="wentWell">
                                <i class='bx bx-up-arrow-circle r-icon-green'></i>
                                What Went Well
                            </label>
                            <p class="reflection-hint">Achievements, successes, and best activities this week.</p>
                            <textarea
                                id="wentWell"
                                class="reflection-textarea"
                                placeholder="Write about your wins and accomplishments this week..."
                                rows="3"
                            ></textarea>
                        </div>

                        <!-- Challenges -->
                        <div class="reflection-block">
                            <label class="reflection-label" for="challenges">
                                <i class='bx bx-error-circle r-icon-red'></i>
                                Challenges
                            </label>
                            <p class="reflection-hint">Obstacles, blockers, and targets not yet reached.</p>
                            <textarea
                                id="challenges"
                                class="reflection-textarea"
                                placeholder="What obstacles did you face? What held you back?"
                                rows="3"
                            ></textarea>
                        </div>

                        <!-- Lessons Learned -->
                        <div class="reflection-block">
                            <label class="reflection-label" for="lessons">
                                <i class='bx bx-bulb r-icon-amber'></i>
                                Lessons Learned
                            </label>
                            <p class="reflection-hint">Key insights, takeaways, and personal evaluations.</p>
                            <textarea
                                id="lessons"
                                class="reflection-textarea"
                                placeholder="What did you learn? What would you do differently?"
                                rows="3"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Next Week Planning -->
                <div class="a-card">
                    <div class="a-card-header">
                        <h3 class="a-card-title">
                            <i class='bx bx-calendar-plus'></i>
                            Next Week Planning
                        </h3>
                        <span class="a-card-meta">Set intentions</span>
                    </div>

                    <div class="planning-sections">
                        <!-- Priorities -->
                        <div class="planning-block">
                            <span class="planning-block-label">
                                <i class='bx bx-list-ol'></i>
                                Priorities For Next Week
                            </span>
                            <div class="priority-input-group">
                                <div class="priority-input-row">
                                    <span class="priority-num">1</span>
                                    <input type="text" id="priority1" class="priority-input" placeholder="Top priority...">
                                </div>
                                <div class="priority-input-row">
                                    <span class="priority-num">2</span>
                                    <input type="text" id="priority2" class="priority-input" placeholder="Second priority...">
                                </div>
                                <div class="priority-input-row">
                                    <span class="priority-num">3</span>
                                    <input type="text" id="priority3" class="priority-input" placeholder="Third priority...">
                                </div>
                            </div>
                        </div>

                        <!-- Main Focus -->
                        <div class="planning-block">
                            <span class="planning-block-label">
                                <i class='bx bx-crosshair'></i>
                                Main Focus
                            </span>
                            <select id="mainFocus" class="focus-select">
                                <option value="">Select your main focus area...</option>
                                <option value="study">Study</option>
                                <option value="project">Project</option>
                                <option value="work">Work</option>
                                <option value="personal_growth">Personal Growth</option>
                                <option value="health">Health</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== PROGRESS VISUALIZATION ===== -->
            <div class="a-card">
                <div class="a-card-header">
                    <h3 class="a-card-title">
                        <i class='bx bx-trending-up'></i>
                        Weekly Progress
                    </h3>
                    <span class="a-card-meta">Daily task completion</span>
                </div>
                <div class="progress-viz">
                    <div class="weekly-progress-bars" id="weeklyBars">
                        <!-- Populated by JS -->
                    </div>
                    <div class="completion-ring-card">
                        <div class="donut-wrap">
                            <svg class="donut-svg" viewBox="0 0 110 110">
                                <defs>
                                    <linearGradient id="donutGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%"   stop-color="#6366F1"/>
                                        <stop offset="100%" stop-color="#8B5CF6"/>
                                    </linearGradient>
                                </defs>
                                <circle class="donut-track" cx="55" cy="55" r="45"/>
                                <circle class="donut-fill"  cx="55" cy="55" r="45" id="donutFill"/>
                            </svg>
                            <div class="donut-center">
                                <span class="donut-pct" id="donutPct">0%</span>
                                <span class="donut-sub">done</span>
                            </div>
                        </div>
                        <div class="donut-legend">
                            <div><strong id="donutDone">0</strong> tasks completed</div>
                            <div><strong id="donutTotal">0</strong> total this week</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== MOTIVATION ===== -->
            <div class="motivation-card">
                <div class="motivation-icon-wrap">
                    <i class='bx bx-rocket'></i>
                </div>
                <div class="motivation-text">
                    <p class="motivation-quote" id="motivationQuote">
                        "Small improvements every week lead to remarkable results over time."
                    </p>
                    <span class="motivation-attr" id="motivationAttr">— ProductivityFlow</span>
                </div>
            </div>

            <!-- ===== ACTION BUTTONS ===== -->
            <div class="analytics-actions">
                <button class="btn-analytics-secondary" id="resetBtn">
                    <i class='bx bx-reset'></i>
                    Reset Form
                </button>
                <button class="btn-analytics-primary" id="saveBtn">
                    <i class='bx bx-save'></i>
                    Save Summary
                </button>
            </div>

        </div><!-- /analytics-content -->
    </main>

</div><!-- /dashboard-layout -->

<!-- Save Toast -->
<div class="save-toast" id="saveToast">
    <i class='bx bx-check-circle'></i>
    Weekly summary saved successfully.
</div>

<script>
// ================================================
// Analytics Page - Weekly Summary JS
// ================================================

const STORAGE_KEY = 'analytics_weekly_summary';

// --- Week Navigation ---
let weekOffset = 0;

function getWeekRange(offset) {
    const now = new Date();
    const day = now.getDay(); // 0 = Sun
    const diffToMon = (day === 0) ? -6 : 1 - day;
    const mon = new Date(now);
    mon.setDate(now.getDate() + diffToMon + offset * 7);
    const sun = new Date(mon);
    sun.setDate(mon.getDate() + 6);
    const fmt = (d) => d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short' });
    return { mon, sun, label: `${fmt(mon)} – ${fmt(sun)}` };
}

function updateWeekLabel() {
    const { label } = getWeekRange(weekOffset);
    document.getElementById('weekLabel').textContent = label;
    loadFromStorage();
    updateStatsFromTasks();
}

document.getElementById('prevWeekBtn').addEventListener('click', () => { weekOffset--; updateWeekLabel(); });
document.getElementById('nextWeekBtn').addEventListener('click', () => { weekOffset++; updateWeekLabel(); });

// --- Storage helpers ---
function storageKeyForOffset(offset) {
    const { mon } = getWeekRange(offset);
    return `${STORAGE_KEY}_${mon.toISOString().slice(0,10)}`;
}

function loadFromStorage() {
    const key = storageKeyForOffset(weekOffset);
    const saved = JSON.parse(localStorage.getItem(key) || '{}');

    document.getElementById('wentWell').value   = saved.wentWell   || '';
    document.getElementById('challenges').value = saved.challenges || '';
    document.getElementById('lessons').value    = saved.lessons    || '';
    document.getElementById('priority1').value  = saved.priority1  || '';
    document.getElementById('priority2').value  = saved.priority2  || '';
    document.getElementById('priority3').value  = saved.priority3  || '';
    document.getElementById('mainFocus').value  = saved.mainFocus  || '';

    // Update stats from tasks
    updateStatsFromTasks();
}

function saveToStorage() {
    const key = storageKeyForOffset(weekOffset);
    const data = {
        wentWell:   document.getElementById('wentWell').value,
        challenges: document.getElementById('challenges').value,
        lessons:    document.getElementById('lessons').value,
        priority1:  document.getElementById('priority1').value,
        priority2:  document.getElementById('priority2').value,
        priority3:  document.getElementById('priority3').value,
        mainFocus:  document.getElementById('mainFocus').value,
    };
    localStorage.setItem(key, JSON.stringify(data));
}

// --- Fetch & Stats ---
async function updateStatsFromTasks() {
    const { mon, sun } = getWeekRange(weekOffset);
    const startDate = mon.toISOString().slice(0, 10);
    const endDate = sun.toISOString().slice(0, 10);

    try {
        const resp = await fetch(`/api/analytics/stats?start_date=${startDate}&end_date=${endDate}`, {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
        const json = await resp.json();
        if (json.success) {
            const s = json.data;
            document.getElementById('statTasks').textContent   = s.tasks_finished ?? 0;
            document.getElementById('statGoals').textContent   = s.goals_completed ?? 0;
            document.getElementById('statScore').textContent   = (s.productivity_score ?? 0) + '%';
            document.getElementById('statFocus').textContent   = s.focus_sessions ?? 0;

            // Donut
            document.getElementById('donutDone').textContent  = s.tasks_finished ?? 0;
            document.getElementById('donutTotal').textContent = s.total_tasks ?? 0;
            document.getElementById('donutPct').textContent   = (s.percentage ?? 0) + '%';
            const offset = 283 - (283 * (s.percentage ?? 0) / 100);
            document.getElementById('donutFill').style.strokeDashoffset = offset;

            // Render bars with real data
            renderWeeklyBars(s.daily_stats);
        }
    } catch (_) {
        // Silently fall back to zeros
    }
}

// --- Weekly Bar Chart ---
function renderWeeklyBars(dailyData) {
    const container = document.getElementById('weeklyBars');
    
    if (!dailyData || dailyData.length === 0) {
        // Fallback or empty state
        container.innerHTML = '<p class="text-center">No data for this week</p>';
        return;
    }

    container.innerHTML = dailyData.map((d) => {
        const pct = d.percentage;
        const cls = pct < 40 ? 'low' : pct < 65 ? 'mid' : '';
        return `
        <div class="day-progress-row">
            <span class="day-label">${d.day}</span>
            <div class="day-bar-track">
                <div class="day-bar-fill ${cls}" style="width:${pct}%"></div>
            </div>
            <span class="day-val">${pct}%</span>
        </div>`;
    }).join('');
}

// --- Motivation quotes ---
const quotes = [
    { text: "Small improvements every week lead to remarkable results over time.", attr: "— ProductivityFlow" },
    { text: "Success is the sum of small efforts, repeated day in and day out.", attr: "— Robert Collier" },
    { text: "A goal without a plan is just a wish.", attr: "— Antoine de Saint-Exupéry" },
    { text: "Progress, not perfection, is what we should be aiming for.", attr: "— Anonymous" },
    { text: "Reflect on what you do. A little analysis now saves a lot of pain later.", attr: "— Anonymous" },
];
function setRandomQuote() {
    const q = quotes[Math.floor(Math.random() * quotes.length)];
    document.getElementById('motivationQuote').textContent = `"${q.text}"`;
    document.getElementById('motivationAttr').textContent  = q.attr;
}

// --- Save button ---
document.getElementById('saveBtn').addEventListener('click', () => {
    saveToStorage();
    const toast = document.getElementById('saveToast');
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
});

// --- Reset button ---
document.getElementById('resetBtn').addEventListener('click', () => {
    if (!confirm('Reset all fields for this week?')) return;
    ['wentWell','challenges','lessons','priority1','priority2','priority3'].forEach(id => {
        document.getElementById(id).value = '';
    });
    document.getElementById('mainFocus').value = '';
    localStorage.removeItem(storageKeyForOffset(weekOffset));
});

// --- Profile dropdown ---
const profileBtn  = document.getElementById('profileBtn');
const profileMenu = document.getElementById('profileMenu');
profileBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    profileBtn.classList.toggle('active');
    profileMenu.classList.toggle('active');
});
document.addEventListener('click', () => {
    profileBtn.classList.remove('active');
    profileMenu.classList.remove('active');
});

// --- Init ---
updateWeekLabel();
setRandomQuote();
</script>
</body>
</html>
