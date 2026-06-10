<!-- div1: Sidebar Navigation -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">
            <i class='bx bx-bolt'></i>
        </div>
        <span class="logo-text">ProductivityFlow</span>
    </div>

    <nav class="sidebar-menu">
        <a href="/dashboard" class="menu-item {{ Request::is('dashboard*') ? 'active' : '' }}">
            <i class='bx bx-home'></i>
            <span>Dashboard</span>
        </a>
        <a href="/projects" class="menu-item {{ Request::is('projects*') ? 'active' : '' }}">
            <i class='bx bx-folder'></i>
            <span>Projects</span>
        </a>
        <a href="/task-manager" class="menu-item {{ Request::is('task-manager*') ? 'active' : '' }}">
            <i class='bx bx-task'></i>
            <span>Tasks</span>
        </a>
        <a href="/focus" class="menu-item {{ Request::is('focus*') ? 'active' : '' }}">
            <i class='bx bx-target-lock'></i>
            <span>Focus</span>
        </a>
        <a href="/analytics" class="menu-item {{ Request::is('analytics*') ? 'active' : '' }}">
            <i class='bx bx-bar-chart-alt-2'></i>
            <span>Analytics</span>
        </a>
        <a href="/profile" class="menu-item {{ Request::is('profile*') ? 'active' : '' }}">
            <i class='bx bx-cog'></i>
            <span>Profile</span>
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

<!-- Global Pomodoro Drop Notification Banner -->
<div id="pomodoroDropBanner" class="pomo-drop-banner">
    <div class="pomo-drop-inner">
        <div class="pomo-drop-icon">
            <i class='bx bx-check-circle'></i>
        </div>
        <div class="pomo-drop-body">
            <div class="pomo-drop-title" id="pomoBannerTitle">Sesi Pomodoro Selesai!</div>
            <div class="pomo-drop-msg" id="pomoBannerMsg">Waktunya istirahat sejenak.</div>
        </div>
        <a href="/focus" class="pomo-drop-action">
            <i class='bx bx-target-lock'></i> Buka Focus
        </a>
        <button class="pomo-drop-close" onclick="dismissPomoBanner()">
            <i class='bx bx-x'></i>
        </button>
    </div>
    <div class="pomo-drop-progress"><div class="pomo-drop-progress-bar" id="pomoBannerProgress"></div></div>
</div>

<style>
.pomo-drop-banner {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 10000;
    transform: translateY(-100%);
    opacity: 0;
    transition: transform .45s cubic-bezier(.4,0,.2,1), opacity .35s ease;
    pointer-events: none;
    font-family: 'Inter', sans-serif;
}
.pomo-drop-banner.visible {
    transform: translateY(0);
    opacity: 1;
    pointer-events: all;
}
.pomo-drop-inner {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: .85rem 1.5rem;
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
    box-shadow: 0 4px 24px rgba(99,102,241,.35), 0 1px 4px rgba(0,0,0,.2);
}
.pomo-drop-icon {
    font-size: 1.6rem;
    color: #a5b4fc;
    display: flex;
    align-items: center;
    animation: pomoPulse 1.5s ease-in-out infinite;
}
@keyframes pomoPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.15); }
}
.pomo-drop-body { flex: 1; min-width: 0; }
.pomo-drop-title {
    font-weight: 700;
    font-size: .95rem;
    color: #e0e7ff;
    line-height: 1.3;
}
.pomo-drop-msg {
    font-size: .8rem;
    color: #a5b4fc;
    margin-top: 2px;
}
.pomo-drop-action {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .45rem 1rem;
    background: rgba(255,255,255,.12);
    color: #e0e7ff;
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 8px;
    font-size: .8rem;
    font-weight: 600;
    text-decoration: none;
    white-space: nowrap;
    transition: background .2s, transform .15s;
}
.pomo-drop-action:hover {
    background: rgba(255,255,255,.22);
    transform: translateY(-1px);
}
.pomo-drop-close {
    background: none;
    border: none;
    color: #a5b4fc;
    font-size: 1.3rem;
    cursor: pointer;
    padding: .25rem;
    border-radius: 6px;
    display: flex;
    transition: background .2s, color .2s;
}
.pomo-drop-close:hover {
    background: rgba(255,255,255,.1);
    color: #fff;
}
.pomo-drop-progress {
    height: 3px;
    background: rgba(255,255,255,.08);
}
.pomo-drop-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #818cf8, #a5b4fc);
    width: 100%;
    transition: width linear;
}
</style>

<!-- Global Pomodoro Notification Script -->
<script>
let _pomoBannerTimeout = null;

function showPomoBanner(title, msg) {
    const banner = document.getElementById('pomodoroDropBanner');
    const progressBar = document.getElementById('pomoBannerProgress');
    document.getElementById('pomoBannerTitle').textContent = title;
    document.getElementById('pomoBannerMsg').textContent = msg;

    // Reset progress bar
    progressBar.style.transition = 'none';
    progressBar.style.width = '100%';

    // Show banner
    requestAnimationFrame(() => {
        banner.classList.add('visible');
        // Start progress bar countdown (15 seconds)
        requestAnimationFrame(() => {
            progressBar.style.transition = 'width 15s linear';
            progressBar.style.width = '0%';
        });
    });

    // Auto-dismiss after 15 seconds
    clearTimeout(_pomoBannerTimeout);
    _pomoBannerTimeout = setTimeout(() => dismissPomoBanner(), 15000);
}

function dismissPomoBanner() {
    const banner = document.getElementById('pomodoroDropBanner');
    banner.classList.remove('visible');
    clearTimeout(_pomoBannerTimeout);
}

document.addEventListener('DOMContentLoaded', () => {
    const userId = '{{ Auth::id() }}';
    if (!userId) return;

    // Don't run this global notifier on the focus page itself, as focus.js handles it there
    if (window.location.pathname.startsWith('/focus')) return;

    const timerKey = `focusTimerState_${userId}`;
    let notificationShown = false;

    // Request notification permission if not yet granted
    if ("Notification" in window) {
        if (Notification.permission !== "granted" && Notification.permission !== "denied") {
            Notification.requestPermission();
        }
    }

    setInterval(() => {
        const stateStr = localStorage.getItem(timerKey);
        if (!stateStr) return;

        try {
            const state = JSON.parse(stateStr);
            if (state.isRunning && state.expectedEndTime) {
                const now = Date.now();
                if (now >= state.expectedEndTime) {
                    // Timer finished!
                    if (!notificationShown) {
                        notificationShown = true;

                        const isFocus = state.currentMode === 'focus';
                        const title = isFocus ? "🎉 Sesi Pomodoro Selesai!" : "⏰ Waktu Istirahat Selesai!";
                        const msg = isFocus
                            ? "Kerja bagus! Waktunya istirahat sejenak."
                            : "Istirahat selesai! Kembali fokus sekarang.";

                        // 1. In-page drop banner
                        showPomoBanner(title, msg);

                        // 2. Browser Notification (if granted)
                        if ("Notification" in window && Notification.permission === "granted") {
                            const notification = new Notification("ProductivityFlow", {
                                body: msg,
                                icon: "https://cdn-icons-png.flaticon.com/512/2082/2082875.png"
                            });
                            notification.onclick = function() {
                                window.focus();
                                window.location.href = '/focus';
                            };
                        }

                        // 3. Play a sound
                        try {
                            const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                            audio.play().catch(e => console.log('Audio autoplay blocked'));
                        } catch (e) {}

                        // 4. Update localStorage so it stops triggering
                        state.isRunning = false;
                        state.timeLeft = 0;
                        localStorage.setItem(timerKey, JSON.stringify(state));
                    }
                } else {
                    // Timer is still running
                    notificationShown = false;
                }
            }
        } catch (e) {
            console.error("Error reading global timer state", e);
        }
    }, 1000);
});
</script>
