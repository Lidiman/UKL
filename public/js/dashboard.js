// ==================== Dropdown Handlers ====================

document.addEventListener('DOMContentLoaded', function() {
    // Profile Dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationsMenu = document.getElementById('notificationsMenu');
    const closeNotifications = document.getElementById('closeNotifications');
    const StatBoxValueDone = document.querySelector('.stat-box-value-done');
    const OverviewUrgent = document.querySelector('.overview-value-urgent');
    const OverviewPending = document.querySelector('.overview-value-pending');
    const OverviewCompleted = document.querySelector('.overview-value-completed');

    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            profileBtn.classList.toggle('active');
            profileMenu.classList.toggle('active');
            
            // Tutup notification menu jika terbuka
            if (notificationsMenu) {
                notificationsMenu.classList.remove('active');
            }
        });
    }

    if (notificationBtn && notificationsMenu) {
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationsMenu.classList.toggle('active');
            
            // Tutup profile menu jika terbuka
            if (profileBtn && profileMenu) {
                profileBtn.classList.remove('active');
                profileMenu.classList.remove('active');
            }
        });
    }

    if (closeNotifications && notificationsMenu) {
        closeNotifications.addEventListener('click', function() {
            notificationsMenu.classList.remove('active');
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        if (profileBtn && profileMenu) {
            profileBtn.classList.remove('active');
            profileMenu.classList.remove('active');
        }
        if (notificationsMenu) {
            notificationsMenu.classList.remove('active');
        }
    });

    // Active menu item based on current page
    const currentPath = window.location.pathname;
    document.querySelectorAll('.menu-item').forEach(item => {
        item.classList.remove('active');
        if (item.getAttribute('href') === currentPath) {
            item.classList.add('active');
        }
    });
});

// ==================== API Configuration ====================
const API_STATS_URL = '/api/tasks/stats';
const API_PROJECT_URL = '/api/projects';
const API_NOTIFICATIONS_URL = '/api/notifications';


    // CSRF token for API requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Inject CSS for hover mark-as-read button
    if (!document.getElementById('mark-read-styles')) {
        const style = document.createElement('style');
        style.id = 'mark-read-styles';
        style.textContent = `
            .mark-read-btn {font-size: 0.75rem; color: #6366F1; cursor: pointer; margin-left: 8px; opacity: 0; transition: opacity 0.2s; text-decoration: underline;}
            .notification-item:hover .mark-read-btn, .alert-item:hover .mark-read-btn {opacity: 1;}
        `;
        document.head.appendChild(style);
    }


async function updateStats() {
    try {
        const [response, response1, response3] = await Promise.all([
            fetch(API_STATS_URL, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                }
            }),
            fetch(API_PROJECT_URL, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                }
            }),
            fetch(API_NOTIFICATIONS_URL, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                }
            })
        ]);
    

        const result = await response.json();
        const projects = await response1.json();
        const notification = await response3.json();

        if (result.success && projects.success && notification.success) {
            const stats = result.data;
            const notifications = notification.data;
            
            document.querySelector('.stat-box-value-done').textContent = stats.completed;
            document.querySelector('.stat-box-value-total').textContent = stats.total;
            document.querySelector('.stat-value').textContent = stats.pending;
            document.querySelector('.overview-value-urgent').textContent = stats.urgent_task;
            document.querySelector('.overview-value-pending').textContent = stats.pending;
            document.querySelector('.overview-value-completed').textContent = stats.completed;

            // ===== Update Task Overview Chart Bars =====
            const barUrgent    = document.getElementById('chart-bar-urgent');
            const barInProg    = document.getElementById('chart-bar-inprogress');
            const barCompleted = document.getElementById('chart-bar-completed');

            const urgent    = stats.urgent_task || 0;
            const inProg    = stats.pending     || 0;
            const completed = stats.completed   || 0;
            const maxVal    = Math.max(urgent, inProg, completed, 1);

            const toHeight = (val) => Math.max((val / maxVal) * 100, val > 0 ? 10 : 5) + '%';

            if (barUrgent)    { barUrgent.style.height    = toHeight(urgent);    barUrgent.title    = 'Urgent: '      + urgent; }
            if (barInProg)    { barInProg.style.height    = toHeight(inProg);    barInProg.title    = 'In Progress: ' + inProg; }
            if (barCompleted) { barCompleted.style.height = toHeight(completed); barCompleted.title = 'Completed: '   + completed; }
            // ============================================

            // ===== Update Project Progress =====
            const projectsList = document.querySelector('.projects-list');
            if (projectsList && projects.data) {
                const topProjects = projects.data.slice(0, 3);
                
                if (topProjects.length === 0) {
                    projectsList.innerHTML = '<p style="color:var(--text-secondary);text-align:center;padding:1rem;">Belum ada proyek</p>';
                } else {
                    projectsList.innerHTML = '';
                    topProjects.forEach(proj => {
                        const tasks = proj.tasks || [];
                        const totalTasks = tasks.length;
                        const completedTasks = tasks.filter(t => t.status === 'completed').length;
                        const progress = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;
                        
                        const item = document.createElement('div');
                        item.className = 'project-item';
                        item.innerHTML = `
                            <div class="project-name">${proj.name}</div>
                            <div class="project-bar">
                                <div class="project-progress-fill" style="width: ${progress}%"></div>
                            </div>
                            <span class="project-percentage">${progress}%</span>
                        `;
                        projectsList.appendChild(item);
                    });
                }
            }
            // ============================================

            if (notifications.length > 0) {
                document.querySelector('.notification-badge').textContent = notifications.length;
                document.querySelector('.notification-badge').style.display = 'flex';
            } else {
               document.querySelector('.notification-badge').style.display = 'none';
            }
            console.log('Stats updated:', stats);
            console.log('Projects:', projects);
            console.log('Notifications:', notifications);
        } else {
            console.error('Failed to fetch stats:', result.message);
        }
    } catch (error) {
        console.error('Error fetching stats:', error);
    }
}

// ==================== CSS Injection for Mark Read ====================
if (!document.getElementById('mark-read-styles')) {
    const style = document.createElement('style');
    style.id = 'mark-read-styles';
    style.textContent = `
        .mark-read-btn {
            font-size: 0.75rem;
            color: #6366F1;
            cursor: pointer;
            margin-left: 8px;
            opacity: 0;
            transition: opacity 0.2s;
            text-decoration: underline;
        }
        .alert-item:hover .mark-read-btn,
        .notification-item:hover .mark-read-btn {
            opacity: 1;
        }
        .notification-content, .alert-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .notification-title, .alert-message {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
    `;
    document.head.appendChild(style);
}

// ==================== Mark As Read ====================
async function markAsRead(id, event) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }
    try {
        const response = await fetch(`/api/notifications/${id}/mark-read`, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        const result = await response.json();
        if (result.success) {
            // Find the containing notification or alert item and remove it after marking as read
            const container = event.target.closest('.notification-item') || event.target.closest('.alert-item');
            if (container) {
                container.remove();
            }
            // Refresh counts and badge
            updateStats();
            updateNotifications();
            updateRecentAlerts();
        }
    } catch (error) {
        console.error('Failed to mark notification as read:', error);
    }
}

async function updateNotifications() {
    const NotificationWrapper = document.getElementById('Notification-Wrapper');
    const seeAllLink = NotificationWrapper.querySelector('.see-all-notifications');

    // Helper function to format time as "X minutes ago" or "X hours ago"
    const formatTimeAgo = (dateString) => {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) {
            return 'Baru saja';
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            return minutes + ' menit lalu';
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            return hours + ' jam lalu';
        } else if (diffInSeconds < 604800) {
            const days = Math.floor(diffInSeconds / 86400);
            return days + ' hari lalu';
        } else {
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        }
    };

    try {
        const response = await fetch(API_NOTIFICATIONS_URL, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            }
        });
        const result = await response.json();
        if (result.success) {
            const notifications = result.data;
            
            // Remove existing notification items but keep the see-all link
            const existingItems = NotificationWrapper.querySelectorAll('.notification-item');
            existingItems.forEach(item => item.remove());
            
                        // Update badge count based on unread notifications
            const unreadCount = notifications.filter(n => !n.read_at).length;
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }

                notifications.forEach(notification => {
    const notificationItem = document.createElement('div');
                notificationItem.className = 'notification-item';
                notificationItem.innerHTML = `
                    <div class="notification-icon">
                        <i class='bx bx-bell'></i>
                    </div>
                    <div class="notification-content">
                        <p class="notification-title">
                            ${notification.data.message}
                            ${!notification.read_at ? `<span style="width:8px;height:8px;border-radius:50%;background:#6366F1;display:inline-block;margin-left:6px;"></span> <span class="mark-read-btn" onclick="markAsRead('${notification.id}', event)">Mark as read</span>` : ''}
                        </p>
                        <span class="notification-time">${formatTimeAgo(notification.created_at)}</span>
                    </div>
                `;
                // Insert before the see-all-notifications link
                if (seeAllLink) {
                    NotificationWrapper.insertBefore(notificationItem, seeAllLink);
                } else {
                    NotificationWrapper.appendChild(notificationItem);
                }
            });
        } else {
            console.error('Failed to fetch notifications:', result.message);
        }
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
}

// ==================== Generate Deadline Reminders ====================
async function generateDeadlineReminders() {
    try {
        await fetch('/api/notifications/generate-deadline-reminders', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
        });
    } catch (e) {
        console.warn('Could not generate deadline reminders:', e);
    }
}

// ==================== Recent Alerts Section ====================
async function updateRecentAlerts() {
    const alertsList = document.querySelector('.alerts-list');
    if (!alertsList) return;

    const formatTimeAgo = (dateString) => {
        const date = new Date(dateString);
        const now  = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        if (diffInSeconds < 60)   return 'Baru saja';
        if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' menit lalu';
        if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' jam lalu';
        return Math.floor(diffInSeconds / 86400) + ' hari lalu';
    };

    const priorityClass = (priority) => {
        if (priority === 'high')   return 'priority-high';
        if (priority === 'medium') return 'priority-medium';
        return 'priority-low';
    };

    const iconFor = (notifData) => {
        if (!notifData) return 'bx-bell';
        if (notifData.type === 'deadline_reminder') return 'bx-error-circle';
        return 'bx-bell';
    };

    try {
        const response = await fetch('/api/notifications', {
            headers: { 'X-CSRF-TOKEN': csrfToken },
        });
        const result = await response.json();
        if (!result.success) return;

        const notifications = result.data.slice(0, 5); // show max 5 recent alerts

        alertsList.innerHTML = '';

        if (notifications.length === 0) {
            alertsList.innerHTML = '<p style="color:var(--text-secondary);text-align:center;padding:1rem;">Tidak ada notifikasi</p>';
            return;
        }

        notifications.forEach(n => {
            const data     = n.data || {};
            const pClass   = priorityClass(data.priority);
            const icon     = iconFor(data);
            const message  = data.message || 'Notifikasi baru';
            const timeAgo  = formatTimeAgo(n.created_at);
            const unreadDot = !n.read_at ? '<span style="width:8px;height:8px;border-radius:50%;background:#6366F1;display:inline-block;margin-left:6px;vertical-align:middle;"></span>' : '';

            const item = document.createElement('div');
            item.className = `alert-item ${pClass}`;
            item.dataset.id = n.id;
            item.innerHTML = `
                <div class="alert-icon">
                    <i class='bx ${icon}'></i>
                </div>
                <div class="alert-content">
                    <p class="alert-message">
                        ${message}
                        ${!n.read_at ? `<span style="width:8px;height:8px;border-radius:50%;background:#6366F1;display:inline-block;margin-left:6px;"></span> <span class="mark-read-btn" onclick="markAsRead('${n.id}', event)">Mark as read</span>` : ''}
                    </p>
                    <span class="alert-time">${timeAgo}</span>
                </div>
            `;
            alertsList.appendChild(item);
        });
    } catch (e) {
        console.error('Failed to load recent alerts:', e);
    }
}

// Call updateStats on page load
document.addEventListener('DOMContentLoaded', async () => {
    await generateDeadlineReminders(); // check & create deadline notifications first
    updateStats();
    updateNotifications();
    updateRecentAlerts();
});

// Refresh every 5 minutes
setInterval(updateStats, 5 * 60 * 1000);
setInterval(updateNotifications, 5 * 60 * 1000);
setInterval(updateRecentAlerts, 5 * 60 * 1000);
