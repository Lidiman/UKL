<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Projects - ProductivityFlow</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard-final.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/projects.css') }}">

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
                <h1 class="page-title">Projects</h1>
            </div>
            <div class="navbar-center">
                <div class="search-box">
                    <i class='bx bx-search'></i>
                    <input type="text" class="search-input" id="projectSearch" placeholder="Search projects...">
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
                            <i class='bx bx-user'></i><span>Profile</span>
                        </a>
                        <hr class="dropdown-divider">
                        <form method="POST" action="{{ route('logout') }}" style="width:100%">
                            @csrf
                            <button type="submit" class="dropdown-item dropdown-item-logout">
                                <i class='bx bx-log-out'></i><span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="projects-main">

        <!-- =========================================================
             LIST VIEW
             ========================================================= -->
        <div id="listView">

            <!-- Page Header -->
            <div class="projects-page-header">
                <div class="projects-title-group">
                    <h2 class="projects-page-title">
                        <i class='bx bx-folder'></i>
                        My Projects
                    </h2>
                    <p class="projects-page-subtitle">Manage and track all your projects in one place.</p>
                </div>
                <div class="projects-header-actions">
                    <button class="btn-proj-primary" onclick="openAddProjectModal()">
                        <i class='bx bx-plus'></i> Add Project
                    </button>
                </div>
            </div>

            <!-- Project Groups Container -->
            <div class="projects-groups" id="projectGroupsContainer">
                <!-- Rendered by JS -->
                <div class="projects-empty">
                    <div class="empty-icon"><i class='bx bx-loader-alt bx-spin'></i></div>
                    <h3 class="empty-title">Loading projects...</h3>
                </div>
            </div>

        </div><!-- /listView -->

        <!-- =========================================================
             DETAIL VIEW
             ========================================================= -->
        <div id="detailView">

            <!-- Back row + title -->
            <div class="detail-back-row">
                <button class="btn-back" onclick="backToList()">
                    <i class='bx bx-chevron-left'></i> Back
                </button>
                <h2 class="detail-title" id="detailTitle"></h2>
            </div>

            <!-- Info bar -->
            <div class="detail-info-bar" id="detailInfoBar"></div>

            <!-- Kanban Board -->
            <div class="kanban-board" id="kanbanBoard"></div>

        </div><!-- /detailView -->

    </main>

</div><!-- /dashboard-layout -->

<!-- =====================================================================
     ADD / EDIT PROJECT MODAL
     ===================================================================== -->
<div class="proj-modal" id="projectModal">
    <div class="proj-modal-content">
        <div class="proj-modal-header">
            <h3 class="proj-modal-title" id="projModalTitle">
                <i class='bx bx-folder-plus'></i> Add Project
            </h3>
            <button class="proj-modal-close" onclick="closeProjectModal()">
                <i class='bx bx-x'></i>
            </button>
        </div>

        <form id="projectForm">
            <!-- Project Name -->
            <div class="proj-form-group">
                <label class="proj-form-label" for="projName">Project Name <span>*</span></label>
                <input type="text" id="projName" class="proj-form-input"
                       placeholder="e.g. Website Redesign" required>
            </div>

            <!-- Description -->
            <div class="proj-form-group">
                <label class="proj-form-label" for="projDesc">Description <span>(optional)</span></label>
                <textarea id="projDesc" class="proj-form-textarea"
                          placeholder="Brief description of the project..."></textarea>
            </div>

            <div class="proj-form-row">
                <!-- Group -->
                <div class="proj-form-group">
                    <label class="proj-form-label" for="projGroup">Group / Category</label>
                    <select id="projGroup" class="proj-form-select" onchange="handleGroupChange()">
                        <option value="Work">Work</option>
                        <option value="School">School</option>
                        <option value="Personal">Personal</option>
                        <option value="Competition">Competition</option>
                        <option value="Development">Development</option>
                        <option value="Other">Other</option>
                        <option disabled>──────────</option>
                        <option value="__custom__">+ New Group...</option>
                    </select>
                    <!-- Custom group input (shown when __custom__ selected) -->
                    <div class="proj-custom-group-row" id="customGroupRow">
                        <input type="text" id="projCustomGroup" class="proj-form-input"
                               placeholder="Group name...">
                    </div>
                </div>

                <!-- Status -->
                <div class="proj-form-group">
                    <label class="proj-form-label" for="projStatus">Status</label>
                    <select id="projStatus" class="proj-form-select">
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>

            <div class="proj-modal-actions">
                <button type="button" class="btn-proj-secondary" onclick="closeProjectModal()">
                    Cancel
                </button>
                <button type="submit" class="btn-proj-primary" id="projSaveBtn">
                    <i class='bx bx-save'></i> Save Project
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =====================================================================
     ADD TASK MODAL (for project context)
     ===================================================================== -->
<div class="proj-modal" id="addTaskModal">
    <div class="proj-modal-content">
        <div class="proj-modal-header">
            <h3 class="proj-modal-title">
                <i class='bx bx-task'></i> Add Task to
                <span id="taskModalProjectName" style="color:#A5B4FC"></span>
            </h3>
            <button class="proj-modal-close" onclick="closeAddTaskModal()">
                <i class='bx bx-x'></i>
            </button>
        </div>

        <form id="addTaskForm">
            <input type="hidden" id="taskModalProjectId">

            <!-- Title -->
            <div class="proj-form-group">
                <label class="proj-form-label" for="projTaskTitle">Task Title <span>*</span></label>
                <input type="text" id="projTaskTitle" class="proj-form-input"
                       placeholder="What needs to be done?" required>
            </div>

            <!-- Description -->
            <div class="proj-form-group">
                <label class="proj-form-label" for="projTaskDesc">Description <span>(optional)</span></label>
                <textarea id="projTaskDesc" class="proj-form-textarea" rows="2"
                          placeholder="Additional details..."></textarea>
            </div>

            <!-- Due Date -->
            <div class="proj-form-group">
                <label class="proj-form-label" for="projTaskDueDate">Deadline <span>*</span></label>
                <input type="date" id="projTaskDueDate" class="proj-form-input" required>
            </div>

            <div class="proj-form-row">
                <!-- Category -->
                <div class="proj-form-group">
                    <label class="proj-form-label" for="projTaskCategory">Category</label>
                    <select id="projTaskCategory" class="proj-form-select">
                        <option value="work">Work</option>
                        <option value="personal">Personal</option>
                        <option value="learning">Learning</option>
                        <option value="health">Health</option>
                    </select>
                </div>

                <!-- Priority -->
                <div class="proj-form-group">
                    <label class="proj-form-label" for="projTaskPriority">Priority</label>
                    <select id="projTaskPriority" class="proj-form-select">
                        <option value="high">High</option>
                        <option value="medium" selected>Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
            </div>

            <div class="proj-modal-actions">
                <button type="button" class="btn-proj-secondary" onclick="closeAddTaskModal()">
                    Cancel
                </button>
                <button type="submit" class="btn-proj-primary">
                    <i class='bx bx-plus'></i> Add Task
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Toast -->
<div class="proj-toast success-toast" id="projToast">
    <i class='bx bx-check-circle'></i>
    <span class="toast-msg"></span>
</div>

<script src="{{ asset('js/projects.js') }}"></script>
</body>
</html>
