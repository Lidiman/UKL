<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Notifications\DeadlineReminder;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * GET /api/notifications
     * List all notifications for the authenticated user (newest first).
     */
    public function index()
    {
        $notifications = auth()->user()
            ->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($n) {
                return [
                    'id'         => $n->id,
                    'type'       => $n->type,
                    'data'       => is_string($n->data) ? json_decode($n->data, true) : $n->data,
                    'read_at'    => $n->read_at,
                    'created_at' => $n->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $notifications,
            'total'   => $notifications->count(),
            'message' => 'Notifications retrieved successfully',
        ]);
    }

    /**
     * POST /api/notifications/generate-deadline-reminders
     * Checks the authenticated user's pending tasks that are due within 24 hours
     * and creates a database notification for each one (if not already notified today).
     */
    public function generateDeadlineNotifications()
    {
        $user = auth()->user();

        // Find pending tasks due within the next 24 hours (today or tomorrow)
        $upcomingTasks = Task::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereBetween('due_date', [
                Carbon::now()->startOfDay(),
                Carbon::now()->addDay()->endOfDay(),
            ])
            ->get();

        $sent = 0;

        foreach ($upcomingTasks as $task) {
            // Avoid duplicate notifications: skip if already notified today for this task
            $alreadyNotified = $user->notifications()
                ->where('type', DeadlineReminder::class)
                ->whereDate('created_at', Carbon::today())
                ->get()
                ->contains(function ($n) use ($task) {
                    $data = is_string($n->data) ? json_decode($n->data, true) : $n->data;
                    return isset($data['task_id']) && $data['task_id'] === $task->id;
                });

            if (!$alreadyNotified) {
                $user->notify(new DeadlineReminder($task));
                $sent++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => $sent > 0
                ? "{$sent} deadline reminder(s) generated."
                : 'No new deadline reminders to generate.',
            'notifications_sent' => $sent,
        ]);
    }

    /**
     * POST /api/notifications/mark-all-read
     * Marks all notifications as read for the authenticated user.
     */
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }

    /**
     * PUT /api/notifications/{id}/mark-read
     * Marks a specific notification as read.
     */
    public function markRead(string $id)
    {
        $notification = auth()->user()->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }

    /**
     * DELETE /api/notifications/{id}
     * Delete a specific notification.
     */
    public function destroy(string $id)
    {
        $notification = auth()->user()->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully',
        ]);
    }
}
