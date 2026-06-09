# API Documentation - ProductivityFlow

Welcome to the comprehensive API documentation for **ProductivityFlow**. This document outlines all available endpoints, required authentication headers, request payloads, and expected responses for both standard users and administrators.

---

## General Information

### Base URL
All API requests should be prefixed with the base URL of your application:
```http
http://your-domain.com/api
```

### Authentication (Bearer Token)
The API is secured using **Laravel Sanctum**. To access any protected endpoint, you must include a Bearer Token in the `Authorization` header of your HTTP request. 

```http
Authorization: Bearer {your_api_token_here}
Accept: application/json
```

> **Note for Web Clients (SPA):** If you are accessing the API from the official ProductivityFlow web dashboard on the same domain, session cookies are automatically used. You do not need to manually pass a Bearer token in the frontend code.

#### Testing Tokens (Sandbox)
For development and testing purposes, you can use the following pre-generated Bearer tokens:

**1. Regular User Token**
- **Email:** `user@gmail.com`
- **Password:** `password`
- **Token:** `4|H6rVhtxAqBu7TPruqXo267ytTXBrvJhvbKpaLihwb54b9f0a`
- **Access Level:** Standard User endpoints (`/api/tasks`, `/api/projects`, etc.)

**2. Administrator Token**
- **Email:** `admin@gmail.com`
- **Password:** `admin`
- **Token:** `5|AxgNz8IZezk1HMdhjlG3762Dh8yMllL9w0qogTfg67cce2f7`
- **Access Level:** All User endpoints + Admin endpoints (`/api/admin/users`, `/api/admin/tasks`, etc.)

---

## 1. Authentication API

These endpoints are used to generate and revoke API tokens.

### A. Login & Generate Token
Authenticates a user and returns a plain-text API token.
- **URL**: `/api/login`
- **Method**: `POST`
- **Headers**: `Accept: application/json`
- **Request Body**:
  ```json
  {
      "email": "user@example.com",
      "password": "yourpassword",
      "device_name": "mobile_app" 
  }
  ```
  *(Note: `device_name` is optional but recommended for tracking active sessions)*
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": {
          "user": {
              "id": 1,
              "name": "Jane Doe",
              "email": "jane@example.com"
          },
          "token": "1|GetZI1JRFOFWhmChW163dtJmniVTMdUIA2qC258Rcfea50e2",
          "token_type": "Bearer"
      },
      "message": "Login berhasil"
  }
  ```

### B. Logout & Revoke Token
Revokes the currently used token.
- **URL**: `/api/logout`
- **Method**: `POST`
- **Headers**: 
  ```http
  Authorization: Bearer {token}
  Accept: application/json
  ```
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "message": "Logout berhasil, token telah dihapus"
  }
  ```

---

## 2. User API Endpoints

The following endpoints require a valid Bearer Token. Data returned is automatically scoped to the authenticated user.

### A. User Profile
Get the currently authenticated user's profile.
- **URL**: `/api/user`
- **Method**: `GET`
- **Response (200 OK)**:
  ```json
  {
      "success": true,
      "data": {
          "id": 1,
          "name": "Jane Doe",
          "email": "jane@example.com",
          "is_admin": false,
          "created_at": "2026-06-08T12:00:00.000000Z"
      }
  }
  ```

### B. Tasks API

#### 1. Get All Tasks
- **URL**: `/api/tasks`
- **Method**: `GET`
- **Response (200 OK)**:
  Returns an array of all active tasks belonging to the user.

#### 2. Create Task
- **URL**: `/api/tasks`
- **Method**: `POST`
- **Headers**:
  ```http
  X-Idempotency-Key: [UUID] (Optional to prevent double submission)
  ```
- **Request Body**:
  | Field | Type | Required | Notes |
  | :--- | :--- | :--- | :--- |
  | `title` | string | **Yes** | Max 255 chars |
  | `category` | string | **Yes** | `work`, `personal`, `learning`, or `health` |
  | `priority` | string | **Yes** | `low`, `medium`, or `high` |
  | `due_date` | date | **Yes** | Format: YYYY-MM-DD |
  | `project_id` | integer | No | Must be a valid project ID |

#### 3. Update Task
- **URL**: `/api/tasks/{task_id}`
- **Method**: `PUT`
- **Request Body (Partial updates allowed)**:
  ```json
  {
      "status": "completed"
  }
  ```

#### 4. Delete Task
- **URL**: `/api/tasks/{task_id}`
- **Method**: `DELETE`

#### 5. Get Task Statistics
- **URL**: `/api/tasks/stats`
- **Method**: `GET`
- **Response (200 OK)**:
  Returns task metrics (completed count, pending count, percentage by category, urgent tasks) for analytics displays.

### C. Projects API

#### 1. Get All Projects
- **URL**: `/api/projects`
- **Method**: `GET`
- **Response (200 OK)**:
  Returns all projects and eager-loads the tasks associated with them.

#### 2. Create Project
- **URL**: `/api/projects`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
      "name": "Mobile App Development",
      "description": "Building the React Native app",
      "status": "active"
  }
  ```

#### 3. Update Project
- **URL**: `/api/projects/{project_id}`
- **Method**: `PUT`

#### 4. Delete Project
- **URL**: `/api/projects/{project_id}`
- **Method**: `DELETE`
  *Note: Deleting a project will not delete its tasks; tasks will simply be unassigned (`project_id` becomes `null`).*

### D. Focus Sessions API

#### 1. Get Focus Sessions
- **URL**: `/api/focus-sessions`
- **Method**: `GET`

#### 2. Log Focus Session
- **URL**: `/api/focus-sessions`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
      "task_name": "Reading Laravel Documentation",
      "duration": 25
  }
  ```
  *(Duration is in minutes)*

### E. Notifications API

- **Get All Notifications:** `GET /api/notifications`
- **Mark All as Read:** `POST /api/notifications/mark-all-read`
- **Mark Single as Read:** `PUT /api/notifications/{id}/mark-read`
- **Delete Notification:** `DELETE /api/notifications/{id}`
- **Generate Deadline Alerts:** `POST /api/notifications/generate-deadline-reminders`

---

## 3. Administrator API Endpoints

These endpoints require the user's Bearer token **AND** the user must have `is_admin = true`. Standard users will receive a `403 Forbidden` response.

### A. Manage Users
- **List Users:** `GET /api/admin/users` (Returns users + their task counts)
- **Create User:** `POST /api/admin/users`
- **Update User:** `PUT /api/admin/users/{user_id}`
- **Delete User:** `DELETE /api/admin/users/{user_id}` (Deletes user and cascades to delete all their tasks/projects)

### B. Global Task Management
- **List All Tasks (Cross-User):** `GET /api/admin/tasks`
- **Create Task for Specific User:** `POST /api/admin/tasks` (Requires `user_id` in body)
- **Update Any Task:** `PUT /api/admin/tasks/{task_id}`
- **Delete Any Task:** `DELETE /api/admin/tasks/{task_id}`

### C. System Analytics
- **Get Global Stats:** `GET /api/admin/stats`
  Returns aggregate metrics: total users, total global tasks, global completion rate, and recent activity.

---

## 4. Error Responses

The API uses standard HTTP status codes combined with structured JSON error messages.

### 400 / 422 Bad Request / Validation Error
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

### 401 Unauthenticated
Thrown if the Bearer Token is missing, invalid, or expired.
```json
{
    "message": "Unauthenticated."
}
```

### 403 Forbidden
Thrown if trying to access another user's resource or an admin endpoint without privileges.
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

### 404 Not Found
```json
{
    "success": false,
    "message": "Record not found."
}
```
