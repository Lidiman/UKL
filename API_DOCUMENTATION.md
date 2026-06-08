# Dokumentasi API - ProductivityFlow

Dokumen ini menyediakan spesifikasi lengkap untuk seluruh API endpoint yang tersedia pada aplikasi **ProductivityFlow**, baik API untuk pengguna biasa (*User*) maupun untuk administrator (*Admin*).

---

## Informasi Umum

### Base URL
Seluruh endpoint API dapat diakses melalui base URL berikut:
```http
/api
```

### Autentikasi & Keamanan
1. **Session-based Authentication**: Semua endpoint dilindungi oleh sistem autentikasi Laravel. Pengguna harus login terlebih dahulu melalui web panel sebelum dapat mengakses endpoint ini.
2. **CSRF Protection**: Semua request bermetode `POST`, `PUT`, dan `DELETE` wajib menyertakan CSRF Token yang dikirim melalui header `X-CSRF-TOKEN`.
3. **Idempotency Key (Opsional)**: Untuk mencegah terjadinya submisi ganda akibat latensi jaringan pada pembuatan tugas (*Task*) atau proyek (*Project*), Anda dapat mengirimkan header `X-Idempotency-Key` dengan value berupa UUID unik. Hasil dari request pertama akan disimpan di cache selama 24 jam.

---

## 1. API Pengguna (User API Endpoints)

Semua endpoint berikut dibungkus dalam middleware `web` dan `auth`. Data yang dikembalikan secara otomatis ter-scope hanya untuk pengguna yang sedang login.

### A. Endpoint Profil Pengguna

#### Get User Profile
Mengambil informasi profil user yang sedang login.
- **URL**: `/api/user`
- **Method**: `GET`
- **Headers**:
  ```http
  X-CSRF-TOKEN: [csrf_token]
  ```
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": {
          "id": 1,
          "name": "Jane Doe",
          "email": "jane@example.com",
          "is_admin": false,
          "created_at": "2026-06-08T12:00:00.000000Z",
          "updated_at": "2026-06-08T12:00:00.000000Z"
      }
  }
  ```

---

### B. Endpoint Tugas (Tasks API)

#### 1. Get All Tasks
Mengambil semua tugas milik user yang sedang aktif.
- **URL**: `/api/tasks`
- **Method**: `GET`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": [
          {
              "id": 12,
              "user_id": 1,
              "project_id": 3,
              "title": "Desain Wireframe Dashboard",
              "description": "Membuat wireframe UI/UX untuk dashboard utama",
              "category": "work",
              "priority": "high",
              "due_date": "2026-06-10T00:00:00.000000Z",
              "status": "pending",
              "is_single_task": false,
              "created_at": "2026-06-08T12:30:00.000000Z",
              "updated_at": "2026-06-08T12:30:00.000000Z"
          }
      ],
      "message": "Tasks retrieved successfully"
  }
  ```

#### 2. Create New Task
Membuat tugas baru.
- **URL**: `/api/tasks`
- **Method**: `POST`
- **Headers**:
  ```http
  Content-Type: application/json
  X-Idempotency-Key: [UUID_Unik] (Opsional)
  ```
- **Request Body**:
  | Field | Type | Required | Description |
  | :--- | :--- | :--- | :--- |
  | `title` | string | **Yes** | Judul tugas (maks. 255 karakter) |
  | `description` | string | No | Deskripsi atau catatan detail tugas |
  | `category` | string | **Yes** | Harus salah satu dari: `work`, `personal`, `learning`, `health` |
  | `priority` | string | **Yes** | Harus salah satu dari: `low`, `medium`, `high` |
  | `due_date` | date | **Yes** | Tanggal jatuh tempo format YYYY-MM-DD |
  | `project_id` | integer | No | ID Proyek yang valid (jika tugas dimasukkan ke dalam suatu proyek) |
  | `is_single_task` | boolean | No | `true` jika tugas mandiri, `false` jika bagian dari proyek. Otomatis `false` jika `project_id` diisi |

- **Example Body**:
  ```json
  {
      "title": "Membaca Buku Laravel 12",
      "description": "Membaca bab tentang routing dan middleware",
      "category": "learning",
      "priority": "medium",
      "due_date": "2026-06-15",
      "project_id": null,
      "is_single_task": true
  }
  ```
- **Response (201 Created)**:
  ```json
  {
      "success": true,
      "data": {
          "id": 13,
          "user_id": 1,
          "project_id": null,
          "title": "Membaca Buku Laravel 12",
          "description": "Membaca bab tentang routing dan middleware",
          "category": "learning",
          "priority": "medium",
          "due_date": "2026-06-15T00:00:00.000000Z",
          "status": "pending",
          "is_single_task": true,
          "created_at": "2026-06-08T12:40:00.000000Z",
          "updated_at": "2026-06-08T12:40:00.000000Z"
      },
      "message": "Task created successfully"
  }
  ```

#### 3. Update Task
Memperbarui detail tugas yang sudah ada.
- **URL**: `/api/tasks/{task_id}`
- **Method**: `PUT`
- **Request Body (Kirim field yang ingin diubah saja)**:
  ```json
  {
      "status": "completed",
      "priority": "high"
  }
  ```
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": {
          "id": 13,
          "user_id": 1,
          "project_id": null,
          "title": "Membaca Buku Laravel 12",
          "description": "Membaca bab tentang routing dan middleware",
          "category": "learning",
          "priority": "high",
          "due_date": "2026-06-15T00:00:00.000000Z",
          "status": "completed",
          "is_single_task": true,
          "created_at": "2026-06-08T12:40:00.000000Z",
          "updated_at": "2026-06-08T12:45:00.000000Z"
      },
      "message": "Task updated successfully"
  }
  ```

#### 4. Delete Task
Menghapus tugas berdasarkan ID.
- **URL**: `/api/tasks/{task_id}`
- **Method**: `DELETE`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "message": "Task deleted successfully"
  }
  ```

#### 5. Get Task Statistics
Mendapatkan metrik ringkasan tugas untuk digunakan pada Dashboard dan Analytics.
- **URL**: `/api/tasks/stats`
- **Method**: `GET`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": {
          "total": 10,
          "work_total": 4,
          "personal_total": 3,
          "learning_total": 2,
          "health_total": 1,
          "completed": 6,
          "pending": 4,
          "taskwork_percent": 40.00,
          "taskpersonal_percent": 30.00,
          "tasklearning_percent": 20.00,
          "taskhealth_percent": 10.00,
          "urgent_task": 2
      }
  }
  ```

---

### C. Endpoint Proyek (Projects API)

#### 1. Get All Projects
Mengambil daftar proyek beserta seluruh tugas yang berasosiasi di dalamnya (*eager-loaded tasks*).
- **URL**: `/api/projects`
- **Method**: `GET`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": [
          {
              "id": 3,
              "user_id": 1,
              "name": "Pengembangan Website UKL",
              "description": "Proyek akhir mata pelajaran produktif",
              "status": "active",
              "created_at": "2026-06-08T10:00:00.000000Z",
              "updated_at": "2026-06-08T10:00:00.000000Z",
              "tasks": [
                  {
                      "id": 12,
                      "project_id": 3,
                      "title": "Desain Wireframe Dashboard",
                      "status": "pending",
                      "priority": "high",
                      "due_date": "2026-06-10"
                  }
              ]
          }
      ],
      "total": 1,
      "message": "Projects retrieved successfully"
  }
  ```

#### 2. Create New Project
Membuat proyek baru.
- **URL**: `/api/projects`
- **Method**: `POST`
- **Request Body**:
  | Field | Type | Required | Description |
  | :--- | :--- | :--- | :--- |
  | `name` | string | **Yes** | Nama proyek (maks. 255 karakter) |
  | `description` | string | No | Penjelasan deskriptif mengenai proyek |
  | `status` | string | No | Pilihan: `active`, `completed`, `archived` (Default: `active`) |

- **Example Body**:
  ```json
  {
      "name": "Belajar React Native",
      "description": "Membuat aplikasi mobile Android & iOS",
      "status": "active"
  }
  ```
- **Response (201 Created)**:
  ```json
  {
      "success": true,
      "data": {
          "id": 4,
          "user_id": 1,
          "name": "Belajar React Native",
          "description": "Membuat aplikasi mobile Android & iOS",
          "status": "active",
          "created_at": "2026-06-08T12:50:00.000000Z",
          "updated_at": "2026-06-08T12:50:00.000000Z"
      },
      "message": "Project created successfully"
  }
  ```

#### 3. Get Single Project
Mendapatkan rincian satu proyek secara spesifik beserta daftar tugasnya.
- **URL**: `/api/projects/{project_id}`
- **Method**: `GET`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": {
          "id": 4,
          "user_id": 1,
          "name": "Belajar React Native",
          "description": "Membuat aplikasi mobile Android & iOS",
          "status": "active",
          "created_at": "2026-06-08T12:50:00.000000Z",
          "updated_at": "2026-06-08T12:50:00.000000Z",
          "tasks": []
      },
      "message": "Project retrieved successfully"
  }
  ```

#### 4. Update Project
Mengubah nama, deskripsi, atau status proyek.
- **URL**: `/api/projects/{project_id}`
- **Method**: `PUT`
- **Request Body**:
  ```json
  {
      "status": "completed"
  }
  ```
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": {
          "id": 4,
          "user_id": 1,
          "name": "Belajar React Native",
          "description": "Membuat aplikasi mobile Android & iOS",
          "status": "completed",
          "created_at": "2026-06-08T12:50:00.000000Z",
          "updated_at": "2026-06-08T12:55:00.000000Z"
      },
      "message": "Project updated successfully"
  }
  ```

#### 5. Delete Project
Menghapus proyek. Tugas-tugas di dalamnya tidak akan terhapus, melainkan kehilangan relasinya terhadap proyek (`project_id` diset menjadi `null` dan `is_single_task` menjadi `true`).
- **URL**: `/api/projects/{project_id}`
- **Method**: `DELETE`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "message": "Project deleted successfully"
  }
  ```

---

### D. Endpoint Notifikasi (Notifications API)

#### 1. Get All Notifications
Mendapatkan semua notifikasi yang ditujukan kepada pengguna aktif.
- **URL**: `/api/notifications`
- **Method**: `GET`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": [
          {
              "id": 1,
              "user_id": 1,
              "type": "deadline_reminder",
              "title": "Deadline Mendekat!",
              "message": "Tugas 'Desain Wireframe Dashboard' akan jatuh tempo besok.",
              "is_read": false,
              "created_at": "2026-06-08T08:00:00.000000Z",
              "updated_at": "2026-06-08T08:00:00.000000Z"
          }
      ],
      "total": 1,
      "message": "Notifications retrieved successfully"
  }
  ```

#### 2. Delete Notification
Menghapus atau membuang notifikasi dari daftar pengguna.
- **URL**: `/api/notifications/{notification_id}`
- **Method**: `DELETE`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "message": "Notification deleted successfully"
  }
  ```

---

## 2. API Administrator (Admin API Endpoints)

Seluruh endpoint berikut dibungkus di bawah middleware `web`, `auth` (auth default session), dan middleware khusus `admin` yang memvalidasi apakah pengguna yang login memiliki nilai `is_admin = true`. Jika bukan admin, server akan melempar error `403 Forbidden`.

### A. Endpoint Kelola Pengguna (Admin Users CRUD)

#### 1. Get All Users
Mengambil daftar seluruh pengguna terdaftar beserta jumlah tugas yang mereka miliki.
- **URL**: `/api/admin/users`
- **Method**: `GET`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": [
          {
              "id": 1,
              "name": "Jane Doe",
              "email": "jane@example.com",
              "is_admin": true,
              "created_at": "2026-06-08T12:00:00.000000Z",
              "updated_at": "2026-06-08T12:00:00.000000Z",
              "tasks_count": 10
          },
          {
              "id": 2,
              "name": "John Smith",
              "email": "john@example.com",
              "is_admin": false,
              "created_at": "2026-06-08T12:15:00.000000Z",
              "updated_at": "2026-06-08T12:15:00.000000Z",
              "tasks_count": 3
          }
      ],
      "message": "Users retrieved successfully"
  }
  ```

#### 2. Create New User
Membuat akun pengguna baru dari panel admin.
- **URL**: `/api/admin/users`
- **Method**: `POST`
- **Request Body**:
  | Field | Type | Required | Description |
  | :--- | :--- | :--- | :--- |
  | `name` | string | **Yes** | Nama lengkap user |
  | `email` | string | **Yes** | Email unik user (belum terdaftar) |
  | `password` | string | **Yes** | Password minimal 6 karakter |
  | `is_admin` | boolean | No | Menentukan apakah user adalah admin (Default: `false`) |

- **Example Body**:
  ```json
  {
      "name": "Budi Santoso",
      "email": "budi@example.com",
      "password": "secretpassword",
      "is_admin": false
  }
  ```
- **Response (201 Created)**:
  ```json
  {
      "success": true,
      "data": {
          "id": 3,
          "name": "Budi Santoso",
          "email": "budi@example.com",
          "is_admin": false,
          "updated_at": "2026-06-08T13:00:00.000000Z",
          "created_at": "2026-06-08T13:00:00.000000Z"
      },
      "message": "User created successfully"
  }
  ```

#### 3. Get Single User Details
Mendapatkan detail satu user spesifik berdasarkan ID.
- **URL**: `/api/admin/users/{user_id}`
- **Method**: `GET`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": {
          "id": 2,
          "name": "John Smith",
          "email": "john@example.com",
          "is_admin": false,
          "created_at": "2026-06-08T12:15:00.000000Z",
          "updated_at": "2026-06-08T12:15:00.000000Z"
      },
      "message": "User retrieved successfully"
  }
  ```

#### 4. Update User Details
Memperbarui informasi user (Nama, Email, Password, atau hak akses Admin).
- **URL**: `/api/admin/users/{user_id}`
- **Method**: `PUT`
- **Request Body**:
  ```json
  {
      "name": "John Smith Updated",
      "is_admin": true
  }
  ```
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": {
          "id": 2,
          "name": "John Smith Updated",
          "email": "john@example.com",
          "is_admin": true,
          "created_at": "2026-06-08T12:15:00.000000Z",
          "updated_at": "2026-06-08T13:05:00.000000Z"
      },
      "message": "User updated successfully"
  }
  ```

#### 5. Delete User
Menghapus user. Menghapus user secara otomatis akan menghapus seluruh tugas (`tasks`) yang terkait dengan user tersebut di database (*Cascade Delete*). Admin tidak diperbolehkan menghapus akun mereka sendiri yang sedang aktif digunakan untuk login.
- **URL**: `/api/admin/users/{user_id}`
- **Method**: `DELETE`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "message": "User and associated tasks deleted successfully"
  }
  ```
- **Response Error (jika menghapus akun sendiri - 400 Bad Request)**:
  ```json
  {
      "success": false,
      "message": "Cannot delete your own account"
  }
  ```

---

### B. Endpoint Kelola Tugas Global (Admin Tasks CRUD)

#### 1. Get All Global Tasks
Mengambil seluruh tugas yang ada dalam aplikasi lintas pengguna (*cross-user*) beserta detail user pemilik tugas tersebut.
- **URL**: `/api/admin/tasks`
- **Method**: `GET`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": [
          {
              "id": 12,
              "user_id": 1,
              "project_id": 3,
              "title": "Desain Wireframe Dashboard",
              "description": "Membuat wireframe UI/UX untuk dashboard utama",
              "category": "work",
              "priority": "high",
              "due_date": "2026-06-10",
              "status": "pending",
              "is_single_task": false,
              "created_at": "2026-06-08T12:30:00.000000Z",
              "updated_at": "2026-06-08T12:30:00.000000Z",
              "user": {
                  "id": 1,
                  "name": "Jane Doe",
                  "email": "jane@example.com"
              }
          }
      ],
      "message": "All tasks retrieved successfully"
  }
  ```

#### 2. Create Task for User
Membuat tugas baru untuk user tertentu yang dipilih secara manual oleh admin.
- **URL**: `/api/admin/tasks`
- **Method**: `POST`
- **Request Body**:
  | Field | Type | Required | Description |
  | :--- | :--- | :--- | :--- |
  | `user_id` | integer | **Yes** | ID User pemilik tugas (harus ada di tabel `users`) |
  | `title` | string | **Yes** | Judul tugas |
  | `description` | string | No | Rincian tugas |
  | `category` | string | **Yes** | `work`, `personal`, `learning`, or `health` |
  | `priority` | string | **Yes** | `low`, `medium`, or `high` |
  | `due_date` | date | **Yes** | Tanggal jatuh tempo (YYYY-MM-DD) |
  | `status` | string | No | `pending` atau `completed` (Default: `pending`) |

- **Response (201 Created)**:
  ```json
  {
      "success": true,
      "data": {
          "id": 14,
          "user_id": 2,
          "title": "Tugas dari Admin",
          "description": "Dibuatkan oleh admin untuk diselesaikan",
          "category": "work",
          "priority": "high",
          "due_date": "2026-06-12T00:00:00.000000Z",
          "status": "pending",
          "updated_at": "2026-06-08T13:10:00.000000Z",
          "created_at": "2026-06-08T13:10:00.000000Z",
          "user": {
              "id": 2,
              "name": "John Smith",
              "email": "john@example.com"
          }
      },
      "message": "Task created successfully"
  }
  ```

#### 3. Get Single Task Details
Mengambil rincian tugas spesifik beserta data usernya.
- **URL**: `/api/admin/tasks/{task_id}`
- **Method**: `GET`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": {
          "id": 14,
          "user_id": 2,
          "title": "Tugas dari Admin",
          "description": "Dibuatkan oleh admin untuk diselesaikan",
          "category": "work",
          "priority": "high",
          "due_date": "2026-06-12",
          "status": "pending",
          "user": {
              "id": 2,
              "name": "John Smith"
          }
      },
      "message": "Task retrieved successfully"
  }
  ```

#### 4. Update Task details
Memperbarui properti tugas apa saja yang ada di sistem.
- **URL**: `/api/admin/tasks/{task_id}`
- **Method**: `PUT`
- **Request Body (Opsional/Parsial)**:
  ```json
  {
      "status": "completed",
      "priority": "low"
  }
  ```
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": {
          "id": 14,
          "user_id": 2,
          "title": "Tugas dari Admin",
          "description": "Dibuatkan oleh admin untuk diselesaikan",
          "category": "work",
          "priority": "low",
          "due_date": "2026-06-12",
          "status": "completed",
          "user": {
              "id": 2,
              "name": "John Smith"
          }
      },
      "message": "Task updated successfully"
  }
  ```

#### 5. Delete Task
Menghapus tugas apa saja dari sistem.
- **URL**: `/api/admin/tasks/{task_id}`
- **Method**: `DELETE`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "message": "Task deleted successfully"
  }
  ```

---

### C. Endpoint Dashboard Admin (Admin Stats)

#### Get Admin Stats
Mendapatkan metrik agregat seluruh sistem untuk dashboard admin utama.
- **URL**: `/api/admin/stats`
- **Method**: `GET`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": {
          "total_users": 15,
          "total_tasks": 128,
          "completed_tasks": 84,
          "pending_tasks": 44,
          "completion_rate": 66,
          "recent_users": [
              {
                  "id": 3,
                  "name": "Budi Santoso",
                  "email": "budi@example.com",
                  "created_at": "2026-06-08T13:00:00.000000Z"
              }
          ],
          "recent_tasks": [
              {
                  "id": 14,
                  "title": "Tugas dari Admin",
                  "status": "completed",
                  "created_at": "2026-06-08T13:10:00.000000Z",
                  "user": {
                      "id": 2,
                      "name": "John Smith"
                  }
              }
          ]
      },
      "message": "Admin stats retrieved successfully"
  }
  ```

---

## 3. Respon Kesalahan (Error Responses)

Jika terjadi masalah pada server atau validasi parameter input, API akan memberikan respon dengan status HTTP yang sesuai beserta format JSON standar berikut:

### 400 Bad Request
Dilemparkan apabila terjadi kesalahan input client yang tidak memenuhi kriteria validasi.
```json
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "title": [
            "The title field is required."
        ],
        "category": [
            "The selected category is invalid."
        ]
    }
}
```

### 403 Forbidden / Unauthorized
Dilemparkan apabila user mencoba mengakses data atau endpoint yang bukan hak miliknya (misal: mengakses endpoint admin tanpa akun admin).
```json
{
    "success": false,
    "message": "Unauthorized. Admin access required."
}
```

### 404 Not Found
Dilemparkan apabila data/model berdasarkan ID yang dikirimkan tidak ditemukan di database.
```json
{
    "success": false,
    "message": "Record not found."
}
```

### 500 Internal Server Error
Dilemparkan apabila terjadi kegagalan sistem atau bug di sisi backend.
```json
{
    "success": false,
    "message": "Internal server error"
}
```
