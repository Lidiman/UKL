<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

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
        $completedTasks = Task::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
        $pendingTasks = $totalTasks - $completedTasks;

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $totalTasks,
                'completed' => $completedTasks,
                'pending' => $pendingTasks,
                'percentage' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0
            ]
        ]);
    }
}
