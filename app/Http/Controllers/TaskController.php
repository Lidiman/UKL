<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    // Get all tasks for authenticated user
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tasks,
            'message' => 'Tasks retrieved successfully'
        ]);
    }

    // Create new task
    public function store(Request $request)
    {
        // Idempotency key validation
        $idempotencyKey = $request->header('X-Idempotency-Key');
        
        if ($idempotencyKey) {
            $cacheKey = 'idempotency_' . $idempotencyKey;
            
            // Check if this request has already been processed
            if (Cache::has($cacheKey)) {
                // Return the cached response (previous successful result)
                return response()->json([
                    'success' => true,
                    'data' => Cache::get($cacheKey),
                    'message' => 'Task already created (idempotent response)'
                ], 201);
            }
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:work,personal,learning,health',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date',
        ]);

        $task = Task::create([
            'user_id' => auth()->id(),
            ...$validated,
            'status' => 'pending'
        ]);

        // Store successful response in cache with 24-hour expiration
        if ($idempotencyKey) {
            Cache::put($cacheKey, $task->toArray(), now()->addHours(24));
        }

        return response()->json([
            'success' => true,
            'data' => $task,
            'message' => 'Task created successfully'
        ], 201);
    }

    // Update task
    public function update(Request $request, Task $task)
    {
        // Check if user owns this task
        if ($task->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'category' => 'in:work,personal,learning,health',
            'priority' => 'in:low,medium,high',
            'due_date' => 'date',
            'status' => 'in:pending,completed',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'data' => $task,
            'message' => 'Task updated successfully'
        ]);
    }

    // Delete task
    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }

    // Get task stats
    public function stats()
{
    $userId = auth()->id();

    $tasks = Task::where('user_id', $userId)->get();
    $totalTasks = $tasks->count();

    $work = $tasks->where('category', 'work')->count();
    $personal = $tasks->where('category', 'personal')->count();
    $learning = $tasks->where('category', 'learning')->count();
    $health = $tasks->where('category', 'health')->count();

    $completedTasks = $tasks->where('status', 'completed')->count();
    $pendingTasks = $totalTasks - $completedTasks;

    $calculatePercent = function ($value) use ($totalTasks) {
        return $totalTasks > 0 ? round(($value / $totalTasks) * 100, 2) : 0;
    };

    return response()->json([
        'success' => true,
        'data' => [
            'total' => $totalTasks,

            'work_total' => $work,
            'personal_total' => $personal,
            'learning_total' => $learning,
            'health_total' => $health,

            'completed' => $completedTasks,
            'pending' => $pendingTasks,

            'taskwork_percent' => $calculatePercent($work),
            'taskpersonal_percent' => $calculatePercent($personal),
            'tasklearning_percent' => $calculatePercent($learning),
            'taskhealth_percent' => $calculatePercent($health),
        ]
    ]);
}
}