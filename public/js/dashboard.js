// ==================== Dropdown Handlers ====================

document.addEventListener('DOMContentLoaded', function() {
    // Profile Dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationsMenu = document.getElementById('notificationsMenu');
    const closeNotifications = document.getElementById('closeNotifications');
    const StatBoxValueDone = document.querySelector('.stat-box-value-done');

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

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

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
            if (notifications.length > 0) {
                document.querySelector('.notification-badge').textContent = notifications.length;
                document.querySelector('.notification-badge').style.display = 'flex';
            } else {
               document.querySelector('.notification-badge').style.display = 'none';
            }
            //document.querySelector('.notification-badge').textContent = notifications.length;
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
            
            notifications.forEach(notification => {
                const notificationItem = document.createElement('div');
                notificationItem.className = 'notification-item';
                notificationItem.innerHTML = `
                    <div class="notification-icon">
                        <i class='bx bx-bell'></i>
                    </div>
                    <div class="notification-content">
                        <p class="notification-title">${notification.data.message}</p>
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

// Call updateStats on page load
document.addEventListener('DOMContentLoaded', updateStats);
document.addEventListener('DOMContentLoaded', updateNotifications);

// Optionally, you can set an interval to refresh stats and notifications every few minutes
setInterval(updateStats, 5 * 60 * 1000); // Refresh every 5 minutes
setInterval(updateNotifications, 5 * 60 * 1000); // Refresh every 5 minutes
