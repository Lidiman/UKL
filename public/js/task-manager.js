// ==================== API Configuration ====================
const API_BASE_URL = '/api/tasks';
const API_STATS_URL = '/api/tasks/stats';

// Get CSRF token from meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

// ==================== DOM Elements ====================
const addTaskBtn = document.querySelector('.btn-add-task');
const addTaskModal = document.getElementById('addTaskModal');
const modalCloseBtn = document.querySelector('.modal-close');
const modalCloseFormBtn = document.querySelector('.modal-close-btn');
const taskForm = document.querySelector('.task-form');
const tasksContainer = document.querySelector('.tasks-container');
const filterItems = document.querySelectorAll('.filter-item');
const categoryTags = document.querySelectorAll('.category-tag');
const searchBox = document.querySelector('.search-box');
const sortSelect = document.querySelector('.sort-select');

// Store tasks in memory
let allTasks = [];
let currentFilter = 'all';
let currentCategory = 'all';
let isSubmitting = false;
let currentIdempotencyKey = null;

// ==================== Modal Functions ====================
function openModal() {
    addTaskModal.classList.add('active');
    // Reset idempotency key for new form
    currentIdempotencyKey = null;
    isSubmitting = false;
}

function closeModal() {
    const modalContent = addTaskModal.querySelector('.modal-content');
    
    // Add closing animation
    modalContent.style.animation = 'slideDown 0.3s ease forwards';
    
    // Wait for animation to complete before hiding modal
    setTimeout(() => {
        addTaskModal.classList.remove('active');
        modalContent.style.animation = 'slideUp 0.3s ease'; // Reset animation for next open
        taskForm.reset();
        currentIdempotencyKey = null;
        isSubmitting = false;
    }, 280);
}

addTaskBtn.addEventListener('click', openModal);
modalCloseBtn.addEventListener('click', closeModal);
modalCloseFormBtn.addEventListener('click', closeModal);

addTaskModal.addEventListener('click', (e) => {
    if (e.target === addTaskModal) closeModal();
});

// ==================== Idempotency Key ====================
function generateIdempotencyKey() {
    return 'task_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}

// ==================== Form Submission ====================
taskForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Prevent double submission
    if (isSubmitting) {
        showNotification('Permintaan sedang diproses...', 'error');
        return;
    }
    
    const submitBtn = taskForm.querySelector('button[type="submit"]');
    const formData = {
        title: taskForm.querySelector('input[placeholder="Masukkan judul task"]').value,
        description: taskForm.querySelector('textarea').value,
        category: taskForm.querySelectorAll('select')[0].value,
        priority: taskForm.querySelectorAll('select')[1].value,
        due_date: taskForm.querySelector('input[type="date"]').value,
    };
    
    if (!validateFormData(formData)) {
        showNotification('Mohon lengkapi semua field yang diperlukan!', 'error');
        return;
    }

    // Generate idempotency key only once per form submission attempt
    if (!currentIdempotencyKey) {
        currentIdempotencyKey = generateIdempotencyKey();
    }
    
    // Disable submit button during request
    isSubmitting = true;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Menyimpan...';

    try {
        const response = await fetch(API_BASE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Idempotency-Key': currentIdempotencyKey,
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (result.success) {
            allTasks.push(result.data);
            createTaskCard(result.data);
            closeModal();
            updateStats();
            showNotification('Task berhasil ditambahkan!');
            // Reset key after successful submission
            currentIdempotencyKey = null;
        } else if (response.status === 409) {
            showNotification('Permintaan duplikat detected. Task mungkin sudah dibuat.', 'error');
        } else {
            showNotification('Gagal menambahkan task: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat menambahkan task', 'error');
    } finally {
        // Re-enable submit button
        isSubmitting = false;
        submitBtn.disabled = false;
        submitBtn.textContent = 'Tambah Task';
    }
});

function validateFormData(data) {
    return data.title && data.category && data.priority && data.due_date;
}

// ==================== Create Task Card ====================
function createTaskCard(taskData) {
    const priorityIcons = {
        high: '🔴',
        medium: '🟡',
        low: '🟢'
    };
    
    const categoryIcons = {
        work: '💼',
        personal: '👤',
        learning: '📚',
        health: '💪'
    };
    
    const categoryNames = {
        work: 'Kerja',
        personal: 'Personal',
        learning: 'Belajar',
        health: 'Kesehatan'
    };
    
    const priorityLabels = {
        high: 'Tinggi',
        medium: 'Sedang',
        low: 'Rendah'
    };
    
    // Clear existing cards and rebuild
    const existingCard = document.querySelector(`[data-task-id="${taskData.id}"]`);
    if (existingCard) {
        existingCard.remove();
    }
    
    const taskCard = document.createElement('div');
    taskCard.className = `task-card`;
    taskCard.setAttribute('data-task-id', taskData.id);
    taskCard.setAttribute('data-priority', taskData.priority);
    taskCard.setAttribute('data-status', taskData.status);
    taskCard.setAttribute('data-category', taskData.category);
    
    const dueDate = new Date(taskData.due_date);
    const formattedDate = dueDate.toLocaleDateString('id-ID', {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
    
    const isCompleted = taskData.status === 'completed';
    
    taskCard.innerHTML = `
        <div class="task-header">
            <div class="task-checkbox">
                <input type="checkbox" ${isCompleted ? 'checked' : ''}>
            </div>
            <div class="task-title-section">
                <h3 class="task-title ${isCompleted ? 'completed' : ''}">${escapeHtml(taskData.title)}</h3>
                <p class="task-description">${escapeHtml(taskData.description || '')}</p>
            </div>
            <button class="task-menu">⋮</button>
        </div>
        
        <div class="task-meta">
            <span class="priority-badge priority-${taskData.priority}">${priorityIcons[taskData.priority]} ${priorityLabels[taskData.priority]}</span>
            <span class="category-badge">${categoryIcons[taskData.category]} ${categoryNames[taskData.category]}</span>
            <span class="due-date">📅 ${formattedDate}</span>
        </div>
    `;
    
    // Handle checkbox - update status
    const checkbox = taskCard.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('change', async () => {
        const newStatus = checkbox.checked ? 'completed' : 'pending';
        await updateTaskStatus(taskData.id, newStatus, taskCard);
    });
    
    // Handle menu button
    const menuBtn = taskCard.querySelector('.task-menu');
    menuBtn.addEventListener('click', () => {
        showTaskMenu(taskCard, taskData.id);
    });
    
    tasksContainer.insertBefore(taskCard, tasksContainer.firstChild);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Update task status (complete/incomplete)
async function updateTaskStatus(taskId, newStatus, taskCard) {
    try {
        const response = await fetch(`${API_BASE_URL}/${taskId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ status: newStatus })
        });

        const result = await response.json();

        if (result.success) {
            const title = taskCard.querySelector('.task-title');
            if (newStatus === 'completed') {
                title.classList.add('completed');
            } else {
                title.classList.remove('completed');
            }
            taskCard.setAttribute('data-status', newStatus);
            updateStats();
        } else {
            showNotification('Gagal mengupdate task', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    }
}

// ==================== Task Menu ====================
function showTaskMenu(taskCard, taskId) {
    // Reset any existing menus
    document.querySelectorAll('.task-menu-popup').forEach(m => m.remove());
    
    const menu = document.createElement('div');
    menu.className = 'task-menu-popup';
    menu.innerHTML = `
        <button class="menu-item delete-task">🗑️ Hapus</button>
        <button class="menu-item edit-task">✏️ Edit</button>
    `;
    
    menu.style.cssText = `
        position: absolute;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        padding: 0.5rem;
        min-width: 120px;
        z-index: 100;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    `;
    
    taskCard.style.position = 'relative';
    taskCard.appendChild(menu);
    
    menu.querySelector('.delete-task').addEventListener('click', async () => {
        await deleteTask(taskId, taskCard);
        menu.remove();
    });
    
    menu.querySelector('.edit-task').addEventListener('click', () => {
        menu.remove();
        showNotification('Fitur edit sedang dikembangkan!');
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!taskCard.contains(e.target)) {
            menu.remove();
        }
    });
}

// Delete task
async function deleteTask(taskId, taskCard) {
    try {
        const response = await fetch(`${API_BASE_URL}/${taskId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            }
        });

        const result = await response.json();

        if (result.success) {
            taskCard.remove();
            allTasks = allTasks.filter(t => t.id !== taskId);
            updateStats();
            showNotification('Task berhasil dihapus!');
        } else {
            showNotification('Gagal menghapus task', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    }
}

// ==================== Filter Functions ====================
filterItems.forEach(item => {
    item.addEventListener('change', () => {
        filterItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        currentFilter = item.getAttribute('data-filter');
        filterTasks();
    });
});

categoryTags.forEach(tag => {
    tag.addEventListener('click', () => {
        categoryTags.forEach(t => t.classList.remove('active'));
        tag.classList.add('active');
        currentCategory = tag.getAttribute('data-category');
        filterTasks();
    });
});

searchBox.addEventListener('input', filterTasks);
sortSelect.addEventListener('change', sortTasks);

function filterTasks() {
    const searchValue = searchBox.value.toLowerCase();
    const taskCards = document.querySelectorAll('.task-card');
    
    taskCards.forEach(card => {
        let show = true;
        
        // Filter by status
        const cardStatus = card.getAttribute('data-status');
        if (currentFilter === 'active' && cardStatus === 'completed') show = false;
        if (currentFilter === 'completed' && cardStatus === 'pending') show = false;
        
        // Filter by category
        const cardCategory = card.getAttribute('data-category');
        if (currentCategory !== 'all' && cardCategory !== currentCategory) show = false;
        
        // Filter by search
        const taskTitle = card.querySelector('.task-title').textContent.toLowerCase();
        const taskDescription = card.querySelector('.task-description').textContent.toLowerCase();
        if (searchValue && !taskTitle.includes(searchValue) && !taskDescription.includes(searchValue)) {
            show = false;
        }
        
        card.style.display = show ? '' : 'none';
    });
}

function sortTasks() {
    const sortValue = sortSelect.value;
    const taskCards = Array.from(document.querySelectorAll('.task-card'));
    
    taskCards.sort((a, b) => {
        if (sortValue === 'priority') {
            const priorityOrder = { high: 1, medium: 2, low: 3 };
            return priorityOrder[a.getAttribute('data-priority')] - 
                   priorityOrder[b.getAttribute('data-priority')];
        } else if (sortValue === 'deadline') {
            const dateA = new Date(a.querySelector('.due-date').textContent);
            const dateB = new Date(b.querySelector('.due-date').textContent);
            return dateA - dateB;
        }
        // Recent order (default)
        return 0;
    });
    
    taskCards.forEach(card => tasksContainer.appendChild(card));
}

// ==================== Update Stats ====================
async function updateStats() {
    try {
        const response = await fetch(API_STATS_URL, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            }
        });

        const result = await response.json();

        if (result.success) {
            const stats = result.data;
            document.querySelectorAll('.stat-box')[0].querySelector('.stat-number').textContent = stats.total;
            document.querySelectorAll('.stat-box')[1].querySelector('.stat-number').textContent = stats.completed;
            document.querySelectorAll('.stat-box')[2].querySelector('.stat-number').textContent = stats.pending;
            
            // Update progress
            const progressFill = document.querySelector('.progress-fill');
            const progressPercentage = document.querySelector('.progress-percentage');
            
            progressFill.style.width = stats.percentage + '%';
            progressPercentage.textContent = stats.percentage + '%';
        }
    } catch (error) {
        console.error('Error updating stats:', error);
    }
}

// Load all tasks from API
async function loadTasks() {
    try {
        const response = await fetch(API_BASE_URL, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            }
        });

        const result = await response.json();

        if (result.success) {
            allTasks = result.data;
            tasksContainer.innerHTML = '';
            
            allTasks.forEach(task => {
                createTaskCard(task);
            });
            
            updateStats();
        }
    } catch (error) {
        console.error('Error loading tasks:', error);
        showNotification('Gagal memuat tasks', 'error');
    }
}

// ==================== Notification ==================== 
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    const bgColor = type === 'error' ? '#ff6464' : 'var(--primary-gradient)';
    
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: ${bgColor};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 3000;
        animation: slideInUp 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutDown 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// ==================== CSS Animations ==================== 
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInUp {
        from {
            transform: translateY(100px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutDown {
        from {
            transform: translateY(0);
            opacity: 1;
        }
        to {
            transform: translateY(100px);
            opacity: 0;
        }
    }
    
    .task-menu-popup {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .menu-item {
        padding: 0.5rem 1rem;
        background: none;
        border: none;
        color: var(--text-primary);
        text-align: left;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 0.4rem;
        font-size: 0.9rem;
    }
    
    .menu-item:hover {
        background: rgba(102, 126, 234, 0.1);
        color: var(--highlight-color);
    }
`;
document.head.appendChild(style);

// ==================== Initialize ====================
document.addEventListener('DOMContentLoaded', () => {
    loadTasks();
    console.log('✨ Task Manager Backend Ready!');
});
