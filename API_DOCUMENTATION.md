# Task Manager API Documentation

## Overview
API endpoints untuk Task Manager yang sudah terintegrasi dengan database.

## Base URL
```
/api/tasks
```

## Authentication
Semua endpoint memerlukan CSRF token dalam header `X-CSRF-TOKEN` dan user yang sudah login (authenticate).

## Endpoints

### 1. Get All Tasks
**GET** `/api/tasks`

**Headers:**
```
X-CSRF-TOKEN: {csrf_token}
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "user_id": 1,
            "title": "Finish Project",
            "description": "Complete the project proposal",
            "category": "work",
            "priority": "high",
            "due_date": "2026-02-28",
            "status": "pending",
            "created_at": "2026-02-23T10:00:00",
            "updated_at": "2026-02-23T10:00:00"
        }
    ],
    "message": "Tasks retrieved successfully"
}
```

---

### 2. Create New Task
**POST** `/api/tasks`

**Headers:**
```
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}
```

**Body:**
```json
{
    "title": "Finish Project",
    "description": "Complete the project proposal",
    "category": "work",
    "priority": "high",
    "due_date": "2026-02-28"
}
```

**Response:** (201 Created)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 1,
        "title": "Finish Project",
        "description": "Complete the project proposal",
        "category": "work",
        "priority": "high",
        "due_date": "2026-02-28",
        "status": "pending",
        "created_at": "2026-02-23T10:00:00",
        "updated_at": "2026-02-23T10:00:00"
    },
    "message": "Task created successfully"
}
```

---

### 3. Update Task
**PUT** `/api/tasks/{id}`

**Headers:**
```
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}
```

**Body** (Send only fields you want to update):
```json
{
    "title": "Updated Title",
    "status": "completed",
    "priority": "medium"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 1,
        "title": "Updated Title",
        "description": "Complete the project proposal",
        "category": "work",
        "priority": "medium",
        "due_date": "2026-02-28",
        "status": "completed",
        "created_at": "2026-02-23T10:00:00",
        "updated_at": "2026-02-23T11:00:00"
    },
    "message": "Task updated successfully"
}
```

---

### 4. Delete Task
**DELETE** `/api/tasks/{id}`

**Headers:**
```
X-CSRF-TOKEN: {csrf_token}
```

**Response:**
```json
{
    "success": true,
    "message": "Task deleted successfully"
}
```

---

### 5. Get Task Statistics
**GET** `/api/tasks/stats`

**Headers:**
```
X-CSRF-TOKEN: {csrf_token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "total": 12,
        "completed": 7,
        "pending": 5,
        "percentage": 58
    }
}
```

---

## Field Specifications

### Categories
- `work` - Pekerjaan
- `personal` - Personal
- `learning` - Belajar
- `health` - Kesehatan

### Priority
- `low` - Rendah (🟢)
- `medium` - Sedang (🟡)
- `high` - Tinggi (🔴)

### Status
- `pending` - Belum selesai
- `completed` - Selesai

---

## Error Responses

### 400 Bad Request
```json
{
    "success": false,
    "message": "Validation error message"
}
```

### 403 Unauthorized
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

### 500 Server Error
```json
{
    "success": false,
    "message": "Internal server error"
}
```

---

## JavaScript Integration

Frontend sudah terintegrasi dengan API ini. Key functions:

- `loadTasks()` - Load semua tasks dari API
- `createTaskCard(taskData)` - Display task
- `updateTaskStatus(taskId, newStatus)` - Update task status
- `deleteTask(taskId)` - Delete task
- `updateStats()` - Fetch dan update stats

## Example API Call (dari JavaScript)

```javascript
// Get all tasks
const response = await fetch('/api/tasks', {
    headers: {
        'X-CSRF-TOKEN': csrfToken,
    }
});
const result = await response.json();

// Create task
const response = await fetch('/api/tasks', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
    },
    body: JSON.stringify({
        title: 'Task Title',
        description: 'Task description',
        category: 'work',
        priority: 'high',
        due_date: '2026-02-28'
    })
});
```

---

## Migration Command

Untuk setup database, jalankan:
```bash
php artisan migrate
```

Ini akan membuat table `tasks` dengan struktur yang sesuai.

---

## Notes

- UI tetap sama, hanya backend logic yang berubah
- CSRF protection aktif untuk semua POST, PUT, DELETE requests
- User authentication diperlukan untuk semua endpoints
- Data secara otomatis di-scope ke user yang login
