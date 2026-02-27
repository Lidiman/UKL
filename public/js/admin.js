// ==================== API Configuration ====================
const API_BASE_URL = '/api';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

// ==================== Notification ====================
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// ==================== Dashboard ====================
async function loadDashboardStats() {
    try {
        const response = await fetch(`${API_BASE_URL}/admin/stats`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            const stats = result.data;
            
            document.getElementById('totalUsers').textContent = stats.total_users;
            document.getElementById('totalTasks').textContent = stats.total_tasks;
            document.getElementById('completedTasks').textContent = stats.completed_tasks;
            document.getElementById('pendingTasks').textContent = stats.pending_tasks;
            document.getElementById('completionRate').textContent = stats.completion_rate + '%';
            
            // Render recent users
            const usersTable = document.getElementById('recentUsersTable');
            if (stats.recent_users && stats.recent_users.length > 0) {
                usersTable.innerHTML = stats.recent_users.map(user => `
                    <tr>
                        <td>${user.id}</td>
                        <td>${escapeHtml(user.name)}</td>
                        <td>${escapeHtml(user.email)}</td>
                        <td><span class="badge ${user.is_admin ? 'badge-admin' : 'badge-user'}">${user.is_admin ? 'Admin' : 'User'}</span></td>
                        <td>${formatDate(user.created_at)}</td>
                    </tr>
                `).join('');
            } else {
                usersTable.innerHTML = '<tr><td colspan="5" class="empty-state">No users found</td></tr>';
            }
            
            // Render recent tasks
            const tasksTable = document.getElementById('recentTasksTable');
            if (stats.recent_tasks && stats.recent_tasks.length > 0) {
                tasksTable.innerHTML = stats.recent_tasks.map(task => `
                    <tr>
                        <td>${task.id}</td>
                        <td>${escapeHtml(task.title)}</td>
                        <td>${escapeHtml(task.user?.name || 'Unknown')}</td>
                        <td><span class="badge ${task.status === 'completed' ? 'badge-completed' : 'badge-pending'}">${task.status}</span></td>
                        <td><span class="badge badge-${task.priority}">${task.priority}</span></td>
                        <td>${formatDate(task.created_at)}</td>
                    </tr>
                `).join('');
            } else {
                tasksTable.innerHTML = '<tr><td colspan="6" class="empty-state">No tasks found</td></tr>';
            }
        }
    } catch (error) {
        console.error('Error loading dashboard stats:', error);
    }
}

// ==================== Users Management ====================
let allUsers = [];

async function loadUsers() {
    try {
        const response = await fetch(`${API_BASE_URL}/admin/users`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            allUsers = result.data;
            renderUsers(allUsers);
        }
    } catch (error) {
        console.error('Error loading users:', error);
        document.getElementById('usersTable').innerHTML = '<tr><td colspan="7" class="error">Error loading users</td></tr>';
    }
}

function renderUsers(users) {
    const table = document.getElementById('usersTable');
    
    if (users.length === 0) {
        table.innerHTML = '<tr><td colspan="7" class="empty-state">No users found</td></tr>';
        return;
    }
    
    table.innerHTML = users.map(user => `
        <tr>
            <td>${user.id}</td>
            <td>${escapeHtml(user.name)}</td>
            <td>${escapeHtml(user.email)}</td>
            <td><span class="badge ${user.is_admin ? 'badge-admin' : 'badge-user'}">${user.is_admin ? 'Admin' : 'User'}</span></td>
            <td>${user.tasks_count || 0}</td>
            <td>${formatDate(user.created_at)}</td>
            <td class="action-btns">
                <button class="btn btn-primary btn-sm" onclick="editUser(${user.id})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Delete</button>
            </td>
        </tr>
    `).join('');
}

function openUserModal(userId = null) {
    const modal = document.getElementById('userModal');
    const title = document.getElementById('userModalTitle');
    const form = document.getElementById('userForm');
    const passwordHint = document.getElementById('passwordHint');
    
    form.reset();
    document.getElementById('userId').value = '';
    
    if (userId) {
        const user = allUsers.find(u => u.id === userId);
        if (user) {
            title.textContent = 'Edit User';
            document.getElementById('userId').value = user.id;
            document.getElementById('userName').value = user.name;
            document.getElementById('userEmail').value = user.email;
            document.getElementById('userIsAdmin').checked = user.is_admin;
            passwordHint.textContent = 'Leave blank to keep current password';
        }
    } else {
        title.textContent = 'Add User';
        passwordHint.textContent = 'Required';
    }
    
    modal.classList.add('active');
}

function closeUserModal() {
    document.getElementById('userModal').classList.remove('active');
}

function editUser(userId) {
    openUserModal(userId);
}

async function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? All their tasks will also be deleted.')) {
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('User deleted successfully');
            loadUsers();
        } else {
            showNotification(result.message || 'Failed to delete user', 'error');
        }
    } catch (error) {
        console.error('Error deleting user:', error);
        showNotification('Error deleting user', 'error');
    }
}

document.getElementById('userForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const userId = document.getElementById('userId').value;
    const formData = {
        name: document.getElementById('userName').value,
        email: document.getElementById('userEmail').value,
        password: document.getElementById('userPassword').value,
        is_admin: document.getElementById('userIsAdmin').checked,
    };
    
    // Remove empty password for edit
    if (userId && !formData.password) {
        delete formData.password;
    }
    
    try {
        const url = userId 
            ? `${API_BASE_URL}/admin/users/${userId}`
            : `${API_BASE_URL}/admin/users`;
        
        const response = await fetch(url, {
            method: userId ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('User saved successfully');
            closeUserModal();
            loadUsers();
        } else {
            showNotification(result.message || 'Failed to save user', 'error');
        }
    } catch (error) {
        console.error('Error saving user:', error);
        showNotification('Error saving user', 'error');
    }
});

document.getElementById('userSearch').addEventListener('input', (e) => {
    const search = e.target.value.toLowerCase();
    const filtered = allUsers.filter(user => 
        user.name.toLowerCase().includes(search) || 
        user.email.toLowerCase().includes(search)
    );
    renderUsers(filtered);
});

// ==================== Tasks Management ====================
let allTasks = [];
let allUsersList = [];

async function loadTasks() {
    try {
        const response = await fetch(`${API_BASE_URL}/admin/tasks`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            allTasks = result.data;
            renderTasks(allTasks);
        }
    } catch (error) {
        console.error('Error loading tasks:', error);
        document.getElementById('tasksTable').innerHTML = '<tr><td colspan="8" class="error">Error loading tasks</td></tr>';
    }
}

async function loadUsersForSelect() {
    try {
        const response = await fetch(`${API_BASE_URL}/admin/users`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            allUsersList = result.data;
            const select = document.getElementById('taskUserId');
            select.innerHTML = '<option value="">Select User</option>' + 
                allUsersList.map(user => `<option value="${user.id}">${escapeHtml(user.name)}</option>`).join('');
        }
    } catch (error) {
        console.error('Error loading users:', error);
    }
}

function renderTasks(tasks) {
    const table = document.getElementById('tasksTable');
    
    if (tasks.length === 0) {
        table.innerHTML = '<tr><td colspan="8" class="empty-state">No tasks found</td></tr>';
        return;
    }
    
    table.innerHTML = tasks.map(task => `
        <tr>
            <td>${task.id}</td>
            <td>${escapeHtml(task.title)}</td>
            <td>${escapeHtml(task.user?.name || 'Unknown')}</td>
            <td>${getCategoryIcon(task.category)} ${task.category}</td>
            <td><span class="badge badge-${task.priority}">${task.priority}</span></td>
            <td><span class="badge ${task.status === 'completed' ? 'badge-completed' : 'badge-pending'}">${task.status}</span></td>
            <td>${formatDate(task.due_date)}</td>
            <td class="action-btns">
                <button class="btn btn-primary btn-sm" onclick="editTask(${task.id})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteTask(${task.id})">Delete</button>
            </td>
        </tr>
    `).join('');
}

function openTaskModal(taskId = null) {
    const modal = document.getElementById('taskModal');
    const title = document.getElementById('taskModalTitle');
    const form = document.getElementById('taskForm');
    
    form.reset();
    document.getElementById('taskId').value = '';
    
    if (taskId) {
        const task = allTasks.find(t => t.id === taskId);
        if (task) {
            title.textContent = 'Edit Task';
            document.getElementById('taskId').value = task.id;
            document.getElementById('taskUserId').value = task.user_id;
            document.getElementById('taskTitle').value = task.title;
            document.getElementById('taskDescription').value = task.description || '';
            document.getElementById('taskCategory').value = task.category;
            document.getElementById('taskPriority').value = task.priority;
            document.getElementById('taskDueDate').value = task.due_date;
            document.getElementById('taskStatus').value = task.status;
        }
    } else {
        title.textContent = 'Add Task';
    }
    
    modal.classList.add('active');
}

function closeTaskModal() {
    document.getElementById('taskModal').classList.remove('active');
}

function editTask(taskId) {
    openTaskModal(taskId);
}

async function deleteTask(taskId) {
    if (!confirm('Are you sure you want to delete this task?')) {
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}/admin/tasks/${taskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Task deleted successfully');
            loadTasks();
        } else {
            showNotification(result.message || 'Failed to delete task', 'error');
        }
    } catch (error) {
        console.error('Error deleting task:', error);
        showNotification('Error deleting task', 'error');
    }
}

document.getElementById('taskForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const taskId = document.getElementById('taskId').value;
    const formData = {
        user_id: parseInt(document.getElementById('taskUserId').value),
        title: document.getElementById('taskTitle').value,
        description: document.getElementById('taskDescription').value,
        category: document.getElementById('taskCategory').value,
        priority: document.getElementById('taskPriority').value,
        due_date: document.getElementById('taskDueDate').value,
        status: document.getElementById('taskStatus').value,
    };
    
    try {
        const url = taskId 
            ? `${API_BASE_URL}/admin/tasks/${taskId}`
            : `${API_BASE_URL}/admin/tasks`;
        
        const response = await fetch(url, {
            method: taskId ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Task saved successfully');
            closeTaskModal();
            loadTasks();
        } else {
            showNotification(result.message || 'Failed to save task', 'error');
        }
    } catch (error) {
        console.error('Error saving task:', error);
        showNotification('Error saving task', 'error');
    }
});

document.getElementById('taskSearch').addEventListener('input', (e) => {
    const search = e.target.value.toLowerCase();
    const filtered = allTasks.filter(task => 
        task.title.toLowerCase().includes(search) || 
        (task.description && task.description.toLowerCase().includes(search))
    );
    renderTasks(filtered);
});

// ==================== Utilities ====================
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function getCategoryIcon(category) {
    const icons = {
        work: '💼',
        personal: '👤',
        learning: '📚',
        health: '💪'
    };
    return icons[category] || '📝';
}

// ==================== Initialize ====================
document.addEventListener('DOMContentLoaded', () => {
    // Check which page we're on and load appropriate data
    if (document.getElementById('totalUsers')) {
        loadDashboardStats();
    }
    
    if (document.getElementById('usersTable')) {
        loadUsers();
    }
    
    if (document.getElementById('tasksTable')) {
        loadTasks();
        loadUsersForSelect();
    }
});

