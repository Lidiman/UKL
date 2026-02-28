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
        $totalTasks = Task::where('user_id', $userId)->count();
        $belajar = Task::where('user_id', $userId)
                ->where('category', 'learning')->count();
        $taskbelajar = $totalTasks > 0 ? ($belajar / $totalTasks) : 0;
        $olahraga = Task::where('user_id', $userId)
                ->where('category', 'olahraga')->count();
        $taskolahraga = $totalTasks > 0 ? ($olahraga / $totalTasks) : 0;
        $personal = Task::where('user_id', $userId)
                ->where('category', 'personal')->count();
        $taskpersonal = $totalTasks > 0 ? ($personal / $totalTasks) : 0;

        $work = Task::where('user_id', $userId)
                ->where('category', 'work')->count();
        $taskwork = $totalTasks > 0 ? ($work / $totalTasks) : 0;
        $completedTasks = Task::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
        $pendingTasks = $totalTasks - $completedTasks;

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $totalTasks,
                'work_total' => $work,
                'personal_total' => $personal,
                'learning_total' => $belajar,
                'olahraga_total' => $olahraga,
                'completed' => $completedTasks,
                'pending' => $pendingTasks,
                'taskbelajar_percent' => $taskbelajar,
                'taskolahraga_percent' => $taskolahraga,
                'taskpersonal_percent' => $taskpersonal,
                'taskwork_percent' => $taskwork,
            ]
        ]);
    }
}
