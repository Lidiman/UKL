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
