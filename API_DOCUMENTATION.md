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
            "project_id": 1,
            "title": "Finish Project",
            "description": "Complete the project proposal",
            "category": "work",
            "priority": "high",
            "due_date": "2026-02-28",
            "status": "pending",
            "is_single_task": false,
            "created_at": "2026-02-23T10:00:00",
            "updated_at": "2026-02-23T10:00:00"
        },
        {
            "id": 2,
            "user_id": 1,
            "project_id": null,
            "title": "Personal Task",
            "description": "Personal task description",
            "category": "personal",
            "priority": "low",
            "due_date": "2026-03-01",
            "status": "pending",
            "is_single_task": true,
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
    "due_date": "2026-02-28",
    "project_id": 1,
    "is_single_task": false
}
```

> **⚠️ IMPORTANT - Project Assignment Requirements:**
> - When adding a task to a project, you MUST set:
>   - `is_single_task` to `false`
>   - `project_id` must be a valid project ID that exists in the `projects` table
> - If you don't set `project_id`, the task will be created as a single task (standalone task)
> - The API will automatically set `is_single_task` to `false` if you provide a `project_id`

**Response:** (201 Created)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 1,
        "project_id": 1,
        "title": "Finish Project",
        "description": "Complete the project proposal",
        "category": "work",
        "priority": "high",
        "due_date": "2026-02-28",
        "status": "pending",
        "is_single_task": false,
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
    "priority": "medium",
    "project_id": 1,
    "is_single_task": false
}
```

> **⚠️ IMPORTANT - Project Assignment Requirements:**
> - When assigning a task to a project, you MUST set:
>   - `is_single_task` to `false`
>   - `project_id` must be a valid project ID that exists in the `projects` table
> - To remove a task from a project, set `project_id` to `null` and `is_single_task` to `true`
> - The API will automatically set `is_single_task` to `false` if you provide a `project_id`

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 1,
        "project_id": 1,
        "title": "Updated Title",
        "description": "Complete the project proposal",
        "category": "work",
        "priority": "medium",
        "due_date": "2026-02-28",
        "status": "completed",
        "is_single_task": false,
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

### Project ID (project_id)
- `project_id` (integer, nullable) - The ID of the project this task belongs to
- Must be a valid project ID from the `projects` table
- Set to `null` if the task is not part of any project

### Is Single Task (is_single_task)
- `is_single_task` (boolean) - Indicates if the task is a standalone task
- `true` - Task is a single/standalone task (not part of a project)
- `false` - Task is part of a project
- **REQUIRED**: When adding a task to a project, you MUST set this to `false`
- The API will automatically set this to `false` if you provide a valid `project_id`

---

## Projects API

Base URL: `/api/projects`

### 1. Get All Projects
**GET** `/api/projects`

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
            "name": "My Project",
            "description": "Project description",
            "status": "active",
            "created_at": "2026-02-23T10:00:00",
            "updated_at": "2026-02-23T10:00:00"
        }
    ],
    "message": "Projects retrieved successfully"
}
```

### 2. Create New Project
**POST** `/api/projects`

**Headers:**
```
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}
```

**Body:**
```json
{
    "name": "My New Project",
    "description": "Project description",
    "status": "active"
}
```

**Response:** (201 Created)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 1,
        "name": "My New Project",
        "description": "Project description",
        "status": "active",
        "created_at": "2026-02-23T10:00:00",
        "updated_at": "2026-02-23T10:00:00"
    },
    "message": "Project created successfully"
}
```

### 3. Get Single Project
**GET** `/api/projects/{id}`

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 1,
        "name": "My Project",
        "description": "Project description",
        "status": "active",
        "created_at": "2026-02-23T10:00:00",
        "updated_at": "2026-02-23T10:00:00"
    },
    "message": "Project retrieved successfully"
}
```

### 4. Update Project
**PUT** `/api/projects/{id}`

**Body:**
```json
{
    "name": "Updated Project Name",
    "status": "completed"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 1,
        "name": "Updated Project Name",
        "description": "Project description",
        "status": "completed",
        "created_at": "2026-02-23T10:00:00",
        "updated_at": "2026-02-23T11:00:00"
    },
    "message": "Project updated successfully"
}
```

### 5. Delete Project
**DELETE** `/api/projects/{id}`

**Response:**
```json
{
    "success": true,
    "message": "Project deleted successfully"
}
```

### Project Status Values
- `active` - Project is ongoing
- `completed` - Project is finished
- `archived` - Project is archived

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

// Create standalone task (not part of a project)
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

// Create task WITHIN a project (IMPORTANT: must set is_single_task to false)
const response = await fetch('/api/tasks', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
    },
    body: JSON.stringify({
        title: 'Project Task Title',
        description: 'Task description',
        category: 'work',
        priority: 'high',
        due_date: '2026-02-28',
        project_id: 1,  // Must be a valid project ID from the projects table
        is_single_task: false  // REQUIRED: must be false when adding to a project
    })
});

// Get all projects (to find valid project IDs)
const response = await fetch('/api/projects', {
    headers: {
        'X-CSRF-TOKEN': csrfToken,
    }
});
const result = await response.json();
// result.data contains array of projects with their IDs
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

---

## Notification API (Tugas Frontend)

Base URL: `/api/notifications`

### Endpoint yang perlu diimplementasikan di Frontend:

#### 1. Get All Notifications
**GET** `/api/notifications`

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
            "type": "deadline_reminder",
            "title": "Deadline Reminder",
            "message": "Task 'Finish Project' is due tomorrow!",
            "is_read": false,
            "created_at": "2026-03-01T10:00:00",
            "updated_at": "2026-03-01T10:00:00"
        }
    ],
    "total": 1,
    "message": "Notifications retrieved successfully"
}
```

#### 2. Get Single Notification
**GET** `/api/notifications/{id}`

**Headers:**
```
X-CSRF-TOKEN: {csrf_token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 1,
        "type": "deadline_reminder",
        "title": "Deadline Reminder",
        "message": "Task 'Finish Project' is due tomorrow!",
        "is_read": false,
        "created_at": "2026-03-01T10:00:00",
        "updated_at": "2026-03-01T10:00:00"
    },
    "message": "Notification retrieved successfully"
}
```

#### 3. Delete Notification
**DELETE** `/api/notifications/{id}`

**Headers:**
```
X-CSRF-TOKEN: {csrf_token}
```

**Response:**
```json
{
    "success": true,
    "message": "Notification deleted successfully"
}
```

### Struktur Data Notification

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Unique ID |
| user_id | integer | Owner of notification |
| type | string | Type: deadline_reminder, dll |
| title | string | Judul notification |
| message | string | Isi pesan notification |
| is_read | boolean | Sudah dibaca atau belum |
| created_at | datetime | Waktu dibuat |
| updated_at | datetime | Waktu diperbarui |

### Tugas Frontend:
1. Tampilkan semua notification user dengan memanggil GET /api/notifications
2. Tampilkan detail notification dengan memanggil GET /api/notifications/{id}
3. Hapus notification dengan memanggil DELETE /api/notifications/{id}
4. Tambahkan indikator untuk notification yang belum dibaca (is_read: false)
5. Saat notification dibaca, bisa langsung hapus atau bisa ditambahkan fitur mark as read (hubungi backend jika perlu fitur ini)
