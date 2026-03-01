// ==================== API Configuration ====================
const API_STATS_URL = '/api/tasks/stats';

//Contoh penggunaan API untuk mendapatkan daftar proyek (jika diperlukan)
const API_PROJECT_URL = '/api/projects';

// Get CSRF token from meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

const StatProject = document.querySelector('.stat-project');
const StatTask = document.querySelector('.stat-task');
const StatCompleated = document.querySelector('.stat-completed');
const StatBoxDone = document.querySelector('.stat-box-value-done')

async function updateStats() {
    try {
        const [response, response1] = await Promise.all([
            fetch(API_STATS_URL, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                }
            }),
            fetch(API_PROJECT_URL, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                }
            })
        ]);
    

        const result = await response.json();
        const projects = await response1.json();

        if (result.success && projects.success) {
            const stats = result.data;
            document.querySelector('.stat-project').textContent = projects.data.length;
            document.querySelector('.stat-task').textContent = stats.total;
            document.querySelector('.stat-completed').textContent = stats.completed;
            StatBoxDone.textContent = stats.completed
        } else {
            console.error('Failed to fetch stats:', result.message);
        }
    } catch (error) {
        console.error('Error fetching stats:', error);
    }
}

// Call updateStats on page load
document.addEventListener('DOMContentLoaded', updateStats);
