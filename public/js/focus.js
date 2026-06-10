document.addEventListener('DOMContentLoaded', () => {
    // CSRF token for API requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Get User ID for scoping local storage
    const userIdMeta = document.querySelector('meta[name="user-id"]');
    const userId = userIdMeta ? userIdMeta.getAttribute('content') : 'guest';

    // Local Storage Keys
    const KEYS = {
        customTimes: `focusCustomTimes_${userId}`,
        timerState: `focusTimerState_${userId}`,
        totalMinutes: `focusTotalMinutes_${userId}`,
        currentTask: `focusCurrentTask_${userId}`,
        history: `focusHistory_${userId}`
    };

    // Load Custom Times
    const savedTimes = JSON.parse(localStorage.getItem(KEYS.customTimes)) || {};
    
    // Times in seconds
    const TIMES = {
        focus: (savedTimes.focus || 25) * 60,
        shortBreak: (savedTimes.shortBreak || 5) * 60,
        longBreak: (savedTimes.longBreak || 15) * 60
    };

    // Timer State
    let timerInterval;
    let isRunning = false;
    let currentMode = 'focus'; // focus, shortBreak, longBreak
    let timeLeft = TIMES[currentMode];
    let expectedEndTime = 0;
    
    let totalFocusMinutes = parseFloat(localStorage.getItem(KEYS.totalMinutes)) || 0;
    
    // DOM Elements
    const timeDisplay = document.getElementById('timeDisplay');
    const btnStartPause = document.getElementById('btnStartPause');
    const btnReset = document.getElementById('btnReset');
    const btnSettings = document.getElementById('btnSettings');
    const modeBtns = document.querySelectorAll('.mode-btn');
    const taskInput = document.getElementById('currentTaskInput');
    const dailyFocusHoursDisplay = document.getElementById('dailyFocusHoursDisplay');
    const rightSidebarActiveTask = document.getElementById('rightSidebarActiveTask');
    const rightSidebarActiveTaskName = document.getElementById('rightSidebarActiveTaskName');
    
    // Task Input UI Elements
    const taskInputWrapper = document.getElementById('taskInputWrapper');
    const activeTaskDisplay = document.getElementById('activeTaskDisplay');
    const activeTaskTitle = document.getElementById('activeTaskTitle');
    const clearTaskBtn = document.getElementById('clearTaskBtn');
    
    // Settings Modal Elements
    const timerSettingsModal = document.getElementById('timerSettingsModal');
    const closeSettingsModal = document.getElementById('closeSettingsModal');
    const saveSettingsBtn = document.getElementById('saveSettingsBtn');
    const settingPomodoro = document.getElementById('settingPomodoro');
    const settingShortBreak = document.getElementById('settingShortBreak');
    const settingLongBreak = document.getElementById('settingLongBreak');
    
    // Load persisted state if exists
    loadState();
    loadHistory();
    
    // Update display initially
    updateDisplay();
    updateStatsDisplay();

    // Event Listeners
    btnStartPause.addEventListener('click', toggleTimer);
    btnReset.addEventListener('click', resetTimer);
    
    modeBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (isRunning) {
                const confirmSwitch = confirm("Timer is running. Are you sure you want to switch modes?");
                if (!confirmSwitch) return;
            }
            switchMode(e.target.dataset.mode);
        });
    });
    
    // Handle Task Input Enter Key
    taskInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const val = taskInput.value.trim();
            if (val) {
                setActiveTask(val);
                updateStatsDisplay(); // Update task name on sidebar
            }
        }
    });
    
    // Also handle click away (blur)
    taskInput.addEventListener('blur', () => {
        const val = taskInput.value.trim();
        if (val) {
            setActiveTask(val);
            updateStatsDisplay();
        }
    });
    
    // Handle Clear Task
    clearTaskBtn.addEventListener('click', () => {
        clearActiveTask();
        updateStatsDisplay(); // Clear task name on sidebar
    });

    // --- Settings Modal Logic ---
    btnSettings.addEventListener('click', () => {
        // Populate current values
        settingPomodoro.value = TIMES.focus / 60;
        settingShortBreak.value = TIMES.shortBreak / 60;
        settingLongBreak.value = TIMES.longBreak / 60;
        timerSettingsModal.classList.add('active');
    });

    closeSettingsModal.addEventListener('click', () => {
        timerSettingsModal.classList.remove('active');
    });

    timerSettingsModal.addEventListener('click', (e) => {
        if (e.target === timerSettingsModal) {
            timerSettingsModal.classList.remove('active');
        }
    });

    saveSettingsBtn.addEventListener('click', () => {
        const focusVal = parseInt(settingPomodoro.value);
        const shortVal = parseInt(settingShortBreak.value);
        const longVal = parseInt(settingLongBreak.value);

        if (focusVal > 0 && shortVal > 0 && longVal > 0) {
            TIMES.focus = focusVal * 60;
            TIMES.shortBreak = shortVal * 60;
            TIMES.longBreak = longVal * 60;

            localStorage.setItem(KEYS.customTimes, JSON.stringify({
                focus: focusVal,
                shortBreak: shortVal,
                longBreak: longVal
            }));

            // Update timer if not running
            if (!isRunning) {
                timeLeft = TIMES[currentMode];
                updateDisplay();
                saveState();
            }

            timerSettingsModal.classList.remove('active');
        } else {
            alert("Please enter valid times greater than 0.");
        }
    });

    // Handle Tab Visibility Change (Background Continuity)
    document.addEventListener("visibilitychange", () => {
        if (document.visibilityState === 'visible' && isRunning) {
            // Recalculate time based on absolute expected end time
            const now = Date.now();
            const remainingSeconds = Math.round((expectedEndTime - now) / 1000);
            
            if (remainingSeconds <= 0) {
                timeLeft = 0;
                updateDisplay();
                completeSession();
            } else {
                timeLeft = remainingSeconds;
                updateDisplay();
                updateStatsDisplay(); // Make sure real-time stat updates
            }
        }
    });

    // Functions
    function toggleTimer() {
        if (isRunning) {
            pauseTimer();
        } else {
            startTimer();
        }
    }

    function startTimer() {
        isRunning = true;
        expectedEndTime = Date.now() + (timeLeft * 1000);
        saveState();
        updateStatsDisplay();
        
        btnStartPause.innerHTML = "<i class='bx bx-pause'></i>";
        btnStartPause.style.color = "#F43F5E"; // Red for pause
        
        timerInterval = setInterval(() => {
            const now = Date.now();
            const remainingSeconds = Math.round((expectedEndTime - now) / 1000);
            
            if (remainingSeconds <= 0) {
                timeLeft = 0;
                updateDisplay();
                completeSession();
            } else {
                timeLeft = remainingSeconds;
                updateDisplay();
                updateStatsDisplay(); // Real-time update for right sidebar
                saveState(); // Continually save state
            }
        }, 1000);
    }

    function pauseTimer() {
        isRunning = false;
        clearInterval(timerInterval);
        saveState();
        updateStatsDisplay();
        
        btnStartPause.innerHTML = "<i class='bx bx-play'></i>";
        btnStartPause.style.color = "var(--bg-primary)"; // Back to normal
    }

    function resetTimer() {
        pauseTimer();
        timeLeft = TIMES[currentMode];
        updateDisplay();
        updateStatsDisplay();
        saveState();
    }

    function switchMode(mode) {
        pauseTimer();
        currentMode = mode;
        timeLeft = TIMES[currentMode];
        saveState();
        updateStatsDisplay();
        
        // Update Active Button
        modeBtns.forEach(btn => {
            if (btn.dataset.mode === mode) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        
        updateDisplay();
    }

    function completeSession() {
        pauseTimer();
        
        // Play sound
        try {
            const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
            audio.play().catch(e => console.log('Audio autoplay blocked'));
        } catch (e) {}

        if (currentMode === 'focus') {
            const focusMinutes = TIMES.focus / 60;
            totalFocusMinutes += focusMinutes;
            localStorage.setItem(KEYS.totalMinutes, totalFocusMinutes);
            
            // Add to history
            let currentTaskName = activeTaskTitle.textContent !== 'My Task' ? activeTaskTitle.textContent : "Focus Session";
            if (currentTaskName === "Focus Session" && taskInput.value.trim() !== '') {
                currentTaskName = taskInput.value.trim();
            }
            addHistoryItem(currentTaskName, focusMinutes);
            
            // Show banner
            if (typeof showPomoBanner === 'function') {
                showPomoBanner("🎉 Sesi Pomodoro Selesai!", "Kerja bagus! Waktunya istirahat sejenak.");
            } else {
                alert("Focus session completed! Take a break.");
            }
            
            // Auto switch to short break
            switchMode('shortBreak');
        } else {
            // Show banner
            if (typeof showPomoBanner === 'function') {
                showPomoBanner("⏰ Waktu Istirahat Selesai!", "Istirahat selesai! Kembali fokus sekarang.");
            } else {
                alert("Break is over! Time to focus.");
            }
            
            switchMode('focus');
        }
        
        updateStatsDisplay();
    }

    function updateDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        const displayString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        timeDisplay.textContent = displayString;
        document.title = `${displayString} - ProductivityFlow`;
    }
    
    function updateStatsDisplay() {
    // This function now relies on server-provided total minutes; UI updates are handled after loading sessions.
    // No local aggregation needed here.
    if (rightSidebarActiveTask && rightSidebarActiveTaskName) {
        let taskName = activeTaskTitle.textContent !== 'My Task' ? activeTaskTitle.textContent : "Focus Session";
        if (taskName === "Focus Session" && taskInput.value.trim() !== '') {
            taskName = taskInput.value.trim();
        }
        if (isRunning && currentMode === 'focus') {
            rightSidebarActiveTaskName.textContent = taskName;
            rightSidebarActiveTask.style.display = 'flex';
        } else {
            rightSidebarActiveTask.style.display = 'none';
        }
    }
}
    
    function addHistoryItem(title, duration) {
    const historyList = document.getElementById('sessionHistoryList');
    if(!historyList) return;

    const now = new Date();
    const timeStr = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

    const historyItemData = {
        title: title,
        duration: duration,
        timeStr: timeStr
    };

    // Persist to server
    fetch('/api/focus-sessions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            task_name: title,
            duration: duration
        })
    })
    .then(res => res.json())
    .then(result => {
        if (!result.success) {
            console.error('Failed to save focus session');
        }
    })
    .catch(err => console.error('Error saving focus session', err));

    // Also keep local fallback for immediate UI update
    renderHistoryItem(historyItemData);
}

    function renderHistoryItem(data) {
        const historyList = document.getElementById('sessionHistoryList');
        if(!historyList) return;

        const item = document.createElement('div');
        item.className = 'history-item';
        item.innerHTML = `
            <div class="history-item-left">
                <i class='bx bx-check-circle history-item-icon'></i>
                <div class="history-item-info">
                    <div class="history-item-title">${data.title}</div>
                    <div class="stat-sub">${data.timeStr}</div>
                </div>
            </div>
            <div class="history-item-duration">${data.duration}m</div>
        `;

        // Remove empty state if exists
        if(historyList.children.length === 1 && historyList.children[0].innerText.includes('No sessions')) {
            historyList.innerHTML = '';
        }

        historyList.prepend(item);

        // Keep only top 4 visually
        if (historyList.children.length > 4) {
            historyList.removeChild(historyList.lastChild);
        }
    }

    function loadHistory() {
    const historyList = document.getElementById('sessionHistoryList');
    if(!historyList) return;

    // Fetch recent sessions from server
    fetch('/api/focus-sessions', {
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            const sessions = result.data.sessions || [];
            const totalMinutes = result.data.total_minutes || 0;
            // Update total focus display
            if (dailyFocusHoursDisplay) {
                const hrs = Math.floor(totalMinutes / 60);
                const mins = totalMinutes % 60;
                dailyFocusHoursDisplay.textContent = hrs > 0 ? `${hrs}h ${mins}m` : `${mins}m`;
            }
            // Render sessions (limit to 4 as UI expects)
            historyList.innerHTML = '';
            sessions.slice(0, 4).reverse().forEach(s => {
                const data = {
                    title: s.task_name,
                    duration: s.duration,
                    timeStr: new Date(s.completed_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                };
                renderHistoryItem(data);
            });
        } else {
            console.error('Failed to load focus sessions');
        }
    })
    .catch(err => console.error('Error loading focus sessions', err));
}
    
    // --- Task UI Logic ---
    function setActiveTask(taskName) {
        activeTaskTitle.textContent = taskName;
        taskInputWrapper.style.display = 'none';
        activeTaskDisplay.style.display = 'flex';
        localStorage.setItem(KEYS.currentTask, taskName);
    }
    
    function clearActiveTask() {
        taskInput.value = '';
        taskInputWrapper.style.display = 'flex';
        activeTaskDisplay.style.display = 'none';
        activeTaskTitle.textContent = 'My Task';
        localStorage.removeItem(KEYS.currentTask);
    }
    
    // --- State Persistence Logic ---
    function saveState() {
        const state = {
            isRunning,
            currentMode,
            timeLeft,
            expectedEndTime
        };
        localStorage.setItem(KEYS.timerState, JSON.stringify(state));
    }
    
    function loadState() {
        // Load Task
        const savedTask = localStorage.getItem(KEYS.currentTask);
        if (savedTask) {
            setActiveTask(savedTask);
        }
        
        // Load Timer State
        const stateStr = localStorage.getItem(KEYS.timerState);
        if (stateStr) {
            try {
                const state = JSON.parse(stateStr);
                currentMode = state.currentMode || 'focus';
                
                // Update active button visually
                modeBtns.forEach(btn => {
                    if (btn.dataset.mode === currentMode) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
                
                if (state.isRunning) {
                    // Calculate remaining time based on absolute expected end time
                    const now = Date.now();
                    const remainingSeconds = Math.round((state.expectedEndTime - now) / 1000);
                    
                    if (remainingSeconds > 0) {
                        timeLeft = remainingSeconds;
                        // Auto-start the timer since it was running
                        startTimer();
                    } else {
                        // Timer finished while away
                        timeLeft = 0;
                        updateDisplay();
                        completeSession();
                    }
                } else {
                    timeLeft = state.timeLeft !== undefined ? state.timeLeft : TIMES[currentMode];
                }
            } catch (e) {
                console.error("Error loading timer state:", e);
                timeLeft = TIMES[currentMode];
            }
        }
    }
});
