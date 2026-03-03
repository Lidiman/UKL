# TODO - Notification API Frontend Implementation

Based on API_DOCUMENTATION.md (NotificationAPI branch)

## Notification Features

- [ ] Implement GET /api/notifications - Display all user notifications
- [ ] Implement GET /api/notifications/{id} - Display notification details
- [ ] Implement DELETE /api/notifications/{id} - Delete notification
- [ ] Add visual indicator for unread notifications (is_read: false)
- [ ] Consider implementing "mark as read" feature (coordinate with backend if needed)

## API Details

Base URL: `/api/notifications`

### Required Headers
```
X-CSRF-TOKEN: {csrf_token}
```

### Notification Data Structure
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Unique ID |
| user_id | integer | Owner of notification |
| type | string | Type: deadline_reminder, etc |
| title | string | Notification title |
| message | string | Notification message |
| is_read | boolean | Read status |
| created_at | datetime | Creation timestamp |
| updated_at | datetime | Last update timestamp |
