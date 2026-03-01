<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Middleware to check if user is admin
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->is_admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }
            return $next($request);
        });
    }

    // ==================== User CRUD ====================
    
    // Get all users
    public function usersIndex()
    {
        $users = User::withCount('tasks')->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Users retrieved successfully'
        ]);
    }

    // Create new user
    public function usersStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'is_admin' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $validated['is_admin'] ?? false,
        ]);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User created successfully'
        ], 201);
    }

    // Get single user
    public function usersShow(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User retrieved successfully'
        ]);
    }

    
    public function usersUpdate(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'is_admin' => 'boolean',
        ]);

        $updateData = [
            'name' => $validated['name'] ?? $user->name,
            'email' => $validated['email'] ?? $user->email,
            'is_admin' => $validated['is_admin'] ?? $user->is_admin,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User updated successfully'
        ]);
    }

    // Delete user
    public function usersDestroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete your own account'
            ], 400);
        }

        // Delete all tasks associated with the user
        Task::where('user_id', $user->id)->delete();
        
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User and associated tasks deleted successfully'
        ]);
    }

    // ==================== Task CRUD ====================

    // Get all tasks (admin sees all)
    public function tasksIndex()
    {
        $tasks = Task::with('user')->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $tasks,
            'message' => 'All tasks retrieved successfully'
        ]);
    }

    // Create new task for any user
    public function tasksStore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:work,personal,learning,health',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date',
            'status' => 'in:pending,completed',
        ]);

        $task = Task::create([
            ...$validated,
            'status' => $validated['status'] ?? 'pending'
        ]);

        $task->load('user');

        return response()->json([
            'success' => true,
            'data' => $task,
            'message' => 'Task created successfully'
        ], 201);
    }

    // Get single task
    public function tasksShow(Task $task)
    {
        $task->load('user');
        
        return response()->json([
            'success' => true,
            'data' => $task,
            'message' => 'Task retrieved successfully'
        ]);
    }

    // Update any task
    public function tasksUpdate(Request $request, Task $task)
    {
        $validated = $request->validate([
            'user_id' => 'exists:users,id',
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'category' => 'in:work,personal,learning,health',
            'priority' => 'in:low,medium,high',
            'due_date' => 'date',
            'status' => 'in:pending,completed',
        ]);

        $task->update($validated);
        $task->load('user');

        return response()->json([
            'success' => true,
            'data' => $task,
            'message' => 'Task updated successfully'
        ]);
    }

    // Delete any task
    public function tasksDestroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }

    // Admin stats dashboard
    public function stats()
    {
        $totalUsers = User::count();
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'completed')->count();
        $pendingTasks = Task::where('status', 'pending')->count();
        
        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
        $recentTasks = Task::with('user')->orderBy('created_at', 'desc')->limit(5)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'pending_tasks' => $pendingTasks,
                'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0,
                'recent_users' => $recentUsers,
                'recent_tasks' => $recentTasks,
            ],
            'message' => 'Admin stats retrieved successfully'
        ]);
    }
}

