// ================================================
// Projects Page — ProductivityFlow
// All project data from /api/projects
// Group & kanban status stored in localStorage
// ================================================

const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

// ── LocalStorage helpers ──────────────────────────────────────────────────────
const LS_GROUPS   = 'pf_project_groups';   // { projectId: groupName }
const LS_KANBAN   = 'pf_kanban_status';    // { taskId: 'todo'|'in_progress'|'done' }
const LS_CGROUPS  = 'pf_custom_groups';    // ['Custom Group Name']

function lsGet(key)        { try { return JSON.parse(localStorage.getItem(key)) || {}; } catch { return {}; } }
function lsGetArr(key)     { try { return JSON.parse(localStorage.getItem(key)) || []; } catch { return []; } }
function lsSet(key, val)   { localStorage.setItem(key, JSON.stringify(val)); }

function getProjectGroup(id)       { return lsGet(LS_GROUPS)[id] || 'Ungrouped'; }
function setProjectGroup(id, grp)  { const d = lsGet(LS_GROUPS); d[id] = grp; lsSet(LS_GROUPS, d); }
function getKanbanStatus(task)     {
    const stored = lsGet(LS_KANBAN)[task.id];
    if (stored) return stored;
    return task.status === 'completed' ? 'done' : 'todo';
}
function setKanbanStatus(taskId, status) { const d = lsGet(LS_KANBAN); d[taskId] = status; lsSet(LS_KANBAN, d); }
function getCustomGroups()          { return lsGetArr(LS_CGROUPS); }
function addCustomGroup(name)       { const arr = getCustomGroups(); if (!arr.includes(name)) { arr.push(name); lsSet(LS_CGROUPS, arr); } }

// ── State ─────────────────────────────────────────────────────────────────────
let allProjects = [];
let currentProject = null;
let currentProjectTasks = [];
let dragTaskId = null;
let editingProjectId = null;

const DEFAULT_GROUPS = ['Work', 'School', 'Personal', 'Competition', 'Development', 'Other'];

// ── API ───────────────────────────────────────────────────────────────────────
const api = {
    async get(url)           { const r = await fetch(url, { headers: { 'X-CSRF-TOKEN': CSRF } }); return r.json(); },
    async post(url, data)    { const r = await fetch(url, { method: 'POST',   headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF }, body: JSON.stringify(data) }); return r.json(); },
    async put(url, data)     { const r = await fetch(url, { method: 'PUT',    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF }, body: JSON.stringify(data) }); return r.json(); },
    async del(url)           { const r = await fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF } }); return r.json(); },
};

// ── Utilities ─────────────────────────────────────────────────────────────────
function esc(str) { const d = document.createElement('div'); d.textContent = str || ''; return d.innerHTML; }

function projectStats(project) {
    const tasks = project.tasks || [];
    const total = tasks.length;
    const done  = tasks.filter(t => t.status === 'completed').length;
    const pct   = total > 0 ? Math.round((done / total) * 100) : 0;
    return { total, done, pct };
}

function statusBadge(status) {
    const map = { active: ['status-active', 'Active'], completed: ['status-completed', 'Completed'], archived: ['status-archived', 'Archived'] };
    const [cls, label] = map[status] || ['status-archived', status];
    return `<span class="status-badge ${cls}"><i class='bx bx-circle'></i>${label}</span>`;
}

function priorityLabel(p) { return { high: 'High', medium: 'Medium', low: 'Low' }[p] || p; }

function formatDate(d) {
    if (!d) return null;
    const date = new Date(d);
    return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short' });
}

function isOverdue(d) { return d && new Date(d) < new Date(new Date().toDateString()); }

function showToast(msg, type = 'success') {
    const t = document.getElementById('projToast');
    t.querySelector('.toast-msg').textContent = msg;
    t.querySelector('i').className = type === 'success' ? 'bx bx-check-circle' : 'bx bx-error-circle';
    t.className = `proj-toast ${type}-toast show`;
    setTimeout(() => t.classList.remove('show'), 3000);
}

// ── LOAD ──────────────────────────────────────────────────────────────────────
async function loadProjects() {
    try {
        const res = await api.get('/api/projects');
        if (res.success) {
            allProjects = res.data;
            renderListView();
        }
    } catch { showToast('Failed to load projects', 'error'); }
}

// ── LIST VIEW ─────────────────────────────────────────────────────────────────
function renderListView() {
    showView('list');

    const container = document.getElementById('projectGroupsContainer');

    if (allProjects.length === 0) {
        container.innerHTML = `
        <div class="projects-empty">
            <div class="empty-icon"><i class='bx bx-folder-open'></i></div>
            <h3 class="empty-title">No projects yet</h3>
            <p class="empty-subtitle">Create your first project to start organizing your tasks.</p>
            <button class="btn-proj-primary" onclick="openAddProjectModal()">
                <i class='bx bx-plus'></i> Add Project
            </button>
        </div>`;
        return;
    }

    // Group projects by localStorage group
    const grouped = {};
    allProjects.forEach(p => {
        const grp = getProjectGroup(p.id);
        if (!grouped[grp]) grouped[grp] = [];
        grouped[grp].push(p);
    });

    // Sort groups: known groups first, Ungrouped last
    const groupOrder = [...DEFAULT_GROUPS, ...getCustomGroups(), 'Ungrouped'];
    const sortedGroups = Object.keys(grouped).sort((a, b) => {
        const ai = groupOrder.indexOf(a), bi = groupOrder.indexOf(b);
        const av = ai === -1 ? 999 : ai, bv = bi === -1 ? 999 : bi;
        if (av !== bv) return av - bv;
        return a.localeCompare(b);
    });

    container.innerHTML = sortedGroups.map(grp => {
        const projects = grouped[grp];
        return `
        <div class="project-group">
            <div class="group-heading">
                <span class="group-name">${esc(grp)}</span>
                <span class="group-count">${projects.length}</span>
                <span class="group-line"></span>
            </div>
            <div class="project-cards-grid">
                ${projects.map(renderProjectCard).join('')}
            </div>
        </div>`;
    }).join('');
}

function renderProjectCard(p) {
    const { total, done, pct } = projectStats(p);
    return `
    <div class="project-card" onclick="openDetailView(${p.id})" data-project-id="${p.id}">
        <div class="project-card-top">
            <h3 class="project-card-name">${esc(p.name)}</h3>
            <div class="project-card-actions" onclick="event.stopPropagation()">
                <button class="btn-icon btn-icon-edit" onclick="openEditProjectModal(${p.id})" title="Edit">
                    <i class='bx bx-pencil'></i>
                </button>
                <button class="btn-icon btn-icon-delete" onclick="deleteProject(${p.id})" title="Delete">
                    <i class='bx bx-trash'></i>
                </button>
            </div>
        </div>
        ${p.description ? `<p class="project-card-desc">${esc(p.description)}</p>` : ''}
        <div class="project-card-progress">
            <div class="proj-progress-header">
                <span class="proj-progress-label">Progress</span>
                <span class="proj-progress-pct">${pct}%</span>
            </div>
            <div class="proj-progress-bar-track">
                <div class="proj-progress-bar-fill" style="width:${pct}%"></div>
            </div>
        </div>
        <div class="project-card-footer">
            <span class="proj-task-summary"><i class='bx bx-task'></i>${done} of ${total} tasks done</span>
            ${statusBadge(p.status)}
        </div>
    </div>`;
}

// ── DETAIL VIEW ───────────────────────────────────────────────────────────────
async function openDetailView(projectId) {
    const project = allProjects.find(p => p.id === projectId);
    if (!project) return;
    currentProject = project;

    try {
        const res = await api.get(`/api/projects/${projectId}`);
        if (res.success) {
            currentProject = res.data;
            currentProjectTasks = res.data.tasks || [];
            // Sync kanban status for completed tasks
            currentProjectTasks.forEach(t => {
                if (t.status === 'completed' && getKanbanStatus(t) === 'todo') {
                    setKanbanStatus(t.id, 'done');
                }
            });
            renderDetailView();
        }
    } catch { showToast('Failed to load project details', 'error'); }
}

function renderDetailView() {
    showView('detail');
    const p = currentProject;
    const { total, done, pct } = projectStats(p);

    document.getElementById('detailTitle').textContent = p.name;

    document.getElementById('detailInfoBar').innerHTML = `
        <div>
            ${p.description ? `<p class="detail-desc">${esc(p.description)}</p>` : '<p class="detail-desc" style="color:var(--text-tertiary)">No description.</p>'}
            <div class="detail-progress-row">
                <span class="detail-progress-text">${done} of ${total} tasks done</span>
                <div class="detail-progress-bar-wrap">
                    <div class="detail-progress-bar-track">
                        <div class="detail-progress-bar-fill" style="width:${pct}%"></div>
                    </div>
                </div>
                <span class="detail-progress-pct">${pct}%</span>
                ${statusBadge(p.status)}
            </div>
        </div>
        <div class="detail-actions">
            <button class="btn-proj-primary" onclick="openAddTaskModal()">
                <i class='bx bx-plus'></i> Add Task
            </button>
            <a href="/focus?project=${encodeURIComponent(p.name)}" class="btn-focus-session" onclick="saveFocusProject('${esc(p.name)}')">
                <i class='bx bx-target-lock'></i> Start Focus
            </a>
        </div>`;

    renderKanbanBoard();
}

function saveFocusProject(name) {
    localStorage.setItem('pf_focus_project', name);
}

function renderKanbanBoard() {
    const board = document.getElementById('kanbanBoard');
    const tasks = currentProjectTasks;

    const columns = [
        { id: 'todo',        label: 'To Do',       cls: 'col-todo',   filter: t => getKanbanStatus(t) === 'todo' },
        { id: 'in_progress', label: 'In Progress',  cls: 'col-inprog', filter: t => getKanbanStatus(t) === 'in_progress' },
        { id: 'done',        label: 'Done',         cls: 'col-done',   filter: t => getKanbanStatus(t) === 'done' },
    ];

    board.innerHTML = columns.map(col => {
        const colTasks = tasks.filter(col.filter);
        return `
        <div class="kanban-col ${col.cls}" data-col="${col.id}"
             ondragover="handleDragOver(event)" ondrop="handleDrop(event, '${col.id}')"
             ondragenter="this.classList.add('drag-over')" ondragleave="handleDragLeave(event)">
            <div class="kanban-col-header">
                <span class="kanban-col-title"><span class="kanban-col-dot"></span>${col.label}</span>
                <span class="kanban-col-count">${colTasks.length}</span>
            </div>
            <div class="kanban-col-tasks" id="col-${col.id}">
                ${renderKanbanTasksGrouped(colTasks, col.id)}
            </div>
        </div>`;
    }).join('');
}

function renderKanbanTasksGrouped(tasks, colId) {
    if (tasks.length === 0) {
        return `<div class="kanban-empty-col"><i class='bx bx-inbox'></i>&nbsp;Drop tasks here</div>`;
    }

    // Group by category
    const catMap = {};
    tasks.forEach(t => {
        const cat = t.category || 'general';
        if (!catMap[cat]) catMap[cat] = [];
        catMap[cat].push(t);
    });

    const catIcons = { work: 'bx-briefcase', personal: 'bx-user', learning: 'bx-book', health: 'bx-dumbbell', general: 'bx-category' };
    const catLabels = { work: 'Work', personal: 'Personal', learning: 'Learning', health: 'Health', general: 'General' };

    return Object.entries(catMap).map(([cat, catTasks]) => `
        <div class="kanban-cat-group">
            <div class="kanban-cat-label">
                <i class='bx ${catIcons[cat] || 'bx-category'}'></i>
                ${catLabels[cat] || cat}
            </div>
            ${catTasks.map(t => renderKanbanTaskCard(t, colId)).join('')}
        </div>
    `).join('');
}

function renderKanbanTaskCard(task, colId) {
    const isDone = colId === 'done';
    const overdue = !isDone && isOverdue(task.due_date);
    return `
    <div class="kanban-task-card" draggable="true"
         data-task-id="${task.id}"
         ondragstart="handleDragStart(event, ${task.id})">
        <p class="kanban-task-title ${isDone ? 'done-title' : ''}">${esc(task.title)}</p>
        <div class="kanban-task-meta">
            <span class="kbadge kbadge-${task.priority}">${priorityLabel(task.priority)}</span>
            ${task.due_date ? `<span class="task-meta-pill ${overdue ? 'overdue' : ''}"><i class='bx bx-calendar'></i>${formatDate(task.due_date)}</span>` : ''}
        </div>
    </div>`;
}

// ── DRAG & DROP ───────────────────────────────────────────────────────────────
function handleDragStart(event, taskId) {
    dragTaskId = taskId;
    event.dataTransfer.setData('text/plain', taskId);
    setTimeout(() => {
        const el = document.querySelector(`[data-task-id="${taskId}"]`);
        if (el) el.classList.add('dragging');
    }, 0);
}

function handleDragOver(event) {
    event.preventDefault();
    event.dataTransfer.dropEffect = 'move';
}

function handleDragLeave(event) {
    const col = event.currentTarget;
    if (!col.contains(event.relatedTarget)) col.classList.remove('drag-over');
}

async function handleDrop(event, targetCol) {
    event.preventDefault();
    const col = event.currentTarget;
    col.classList.remove('drag-over');

    const taskId = parseInt(event.dataTransfer.getData('text/plain') || dragTaskId);
    if (!taskId) return;

    // Remove dragging class
    document.querySelectorAll('.kanban-task-card').forEach(el => el.classList.remove('dragging'));

    const task = currentProjectTasks.find(t => t.id === taskId);
    if (!task) return;

    const currentCol = getKanbanStatus(task);
    if (currentCol === targetCol) return;

    setKanbanStatus(taskId, targetCol);

    // Sync API status
    if (targetCol === 'done' && task.status !== 'completed') {
        await api.put(`/api/tasks/${taskId}`, { status: 'completed' });
        task.status = 'completed';
    } else if (targetCol !== 'done' && task.status === 'completed') {
        await api.put(`/api/tasks/${taskId}`, { status: 'pending' });
        task.status = 'pending';
    }

    // Re-render kanban & refresh detail info (progress)
    renderKanbanBoard();
    refreshDetailProgress();
    dragTaskId = null;
}

function refreshDetailProgress() {
    const done  = currentProjectTasks.filter(t => getKanbanStatus(t) === 'done').length;
    const total = currentProjectTasks.length;
    const pct   = total > 0 ? Math.round((done / total) * 100) : 0;

    // Update the project in allProjects to keep in sync
    const idx = allProjects.findIndex(p => p.id === currentProject.id);
    if (idx !== -1) {
        allProjects[idx].tasks = currentProjectTasks;
    }
}

// ── VIEWS TOGGLE ──────────────────────────────────────────────────────────────
function showView(view) {
    document.getElementById('listView').style.display   = view === 'list'   ? 'flex' : 'none';
    document.getElementById('detailView').style.display = view === 'detail' ? 'flex' : 'none';
}

function backToList() {
    currentProject = null;
    loadProjects();
}

// ── ADD/EDIT PROJECT MODAL ────────────────────────────────────────────────────
function openAddProjectModal() {
    editingProjectId = null;
    resetProjectForm();
    document.getElementById('projModalTitle').innerHTML = "<i class='bx bx-folder-plus'></i> Add Project";
    document.getElementById('projSaveBtn').textContent = 'Save Project';
    document.getElementById('projectModal').classList.add('active');
}

function openEditProjectModal(projectId) {
    const p = allProjects.find(x => x.id === projectId);
    if (!p) return;
    editingProjectId = projectId;

    document.getElementById('projModalTitle').innerHTML = "<i class='bx bx-edit'></i> Edit Project";
    document.getElementById('projSaveBtn').textContent = 'Update Project';

    document.getElementById('projName').value   = p.name;
    document.getElementById('projDesc').value   = p.description || '';
    document.getElementById('projStatus').value = p.status;
    document.getElementById('projGroup').value  = getProjectGroup(p.id);

    document.getElementById('projectModal').classList.add('active');
}

function closeProjectModal() {
    document.getElementById('projectModal').classList.remove('active');
    editingProjectId = null;
}

function resetProjectForm() {
    document.getElementById('projName').value   = '';
    document.getElementById('projDesc').value   = '';
    document.getElementById('projStatus').value = 'active';
    document.getElementById('projGroup').value  = 'Work';
    document.getElementById('customGroupRow').classList.remove('visible');
    document.getElementById('projCustomGroup').value = '';
}

function handleGroupChange() {
    const val = document.getElementById('projGroup').value;
    const row = document.getElementById('customGroupRow');
    if (val === '__custom__') {
        row.classList.add('visible');
    } else {
        row.classList.remove('visible');
    }
}

document.getElementById('projectForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const name   = document.getElementById('projName').value.trim();
    const desc   = document.getElementById('projDesc').value.trim();
    const status = document.getElementById('projStatus').value;
    let   group  = document.getElementById('projGroup').value;

    if (group === '__custom__') {
        group = document.getElementById('projCustomGroup').value.trim() || 'Other';
        if (group !== 'Other') addCustomGroup(group);
    }
    if (!group) group = 'Ungrouped';

    if (!name) return;

    const payload = { name, description: desc, status };

    try {
        let res;
        if (editingProjectId) {
            res = await api.put(`/api/projects/${editingProjectId}`, payload);
        } else {
            res = await api.post('/api/projects', payload);
        }

        if (res.success) {
            const savedId = res.data.id;
            setProjectGroup(savedId, group);
            closeProjectModal();
            showToast(editingProjectId ? 'Project updated!' : 'Project created!');
            await loadProjects();
        } else {
            showToast(res.message || 'Error saving project', 'error');
        }
    } catch { showToast('Network error', 'error'); }
});

async function deleteProject(projectId) {
    if (!confirm('Delete this project? Tasks will remain but lose their project link.')) return;
    try {
        const res = await api.del(`/api/projects/${projectId}`);
        if (res.success) {
            showToast('Project deleted.');
            await loadProjects();
        }
    } catch { showToast('Error deleting project', 'error'); }
}

// ── ADD TASK MODAL (from detail view) ────────────────────────────────────────
function openAddTaskModal() {
    if (!currentProject) return;
    // Populate the task modal, pre-select current project
    document.getElementById('taskModalProjectId').value   = currentProject.id;
    document.getElementById('taskModalProjectName').textContent = currentProject.name;
    document.getElementById('projTaskTitle').value     = '';
    document.getElementById('projTaskDesc').value      = '';
    document.getElementById('projTaskDueDate').value   = '';
    document.getElementById('projTaskCategory').value  = 'work';
    document.getElementById('projTaskPriority').value  = 'medium';
    document.getElementById('addTaskModal').classList.add('active');
}

function closeAddTaskModal() {
    document.getElementById('addTaskModal').classList.remove('active');
}

document.getElementById('addTaskForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const projectId = parseInt(document.getElementById('taskModalProjectId').value);
    const payload = {
        title:       document.getElementById('projTaskTitle').value.trim(),
        description: document.getElementById('projTaskDesc').value.trim(),
        due_date:    document.getElementById('projTaskDueDate').value,
        category:    document.getElementById('projTaskCategory').value,
        priority:    document.getElementById('projTaskPriority').value,
        project_id:  projectId,
        is_single_task: false,
    };

    if (!payload.title || !payload.due_date) return;

    try {
        const res = await api.post('/api/tasks', payload);
        if (res.success) {
            const newTask = res.data;
            setKanbanStatus(newTask.id, 'todo');
            currentProjectTasks.push(newTask);
            closeAddTaskModal();
            showToast('Task added!');
            renderKanbanBoard();
            refreshDetailProgress();
        } else {
            showToast(res.message || 'Error creating task', 'error');
        }
    } catch { showToast('Network error', 'error'); }
});

// ── GROUP DROPDOWN POPULATION ─────────────────────────────────────────────────
function populateGroupSelect() {
    const sel = document.getElementById('projGroup');
    const groups = [...DEFAULT_GROUPS, ...getCustomGroups()];
    // Keep existing options: the custom + separator are already in HTML
    // Remove old custom ones and rebuild
    while (sel.options.length > DEFAULT_GROUPS.length + 2) sel.remove(DEFAULT_GROUPS.length + 1); // Keep last 2 (sep + custom)

    const customGroups = getCustomGroups();
    if (customGroups.length > 0) {
        // Insert before the separator
        const sep = sel.querySelector('option[disabled]');
        customGroups.forEach(g => {
            const opt = document.createElement('option');
            opt.value = g;
            opt.textContent = g;
            sel.insertBefore(opt, sep);
        });
    }
}

// ── INIT ──────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    populateGroupSelect();
    loadProjects();

    // Profile dropdown
    const profileBtn  = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', e => {
            e.stopPropagation();
            profileBtn.classList.toggle('active');
            profileMenu.classList.toggle('active');
        });
    }
    document.addEventListener('click', () => {
        if (profileBtn)  profileBtn.classList.remove('active');
        if (profileMenu) profileMenu.classList.remove('active');
    });

    // Close modals on backdrop click
    document.getElementById('projectModal').addEventListener('click', e => {
        if (e.target === document.getElementById('projectModal')) closeProjectModal();
    });
    document.getElementById('addTaskModal').addEventListener('click', e => {
        if (e.target === document.getElementById('addTaskModal')) closeAddTaskModal();
    });

    // Check if coming from focus page with project name
    const focusProject = localStorage.getItem('pf_focus_project');
    if (focusProject) {
        // Could show a notification, but we'll just clear it
        localStorage.removeItem('pf_focus_project');
    }
});
