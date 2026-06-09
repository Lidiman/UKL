<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // ── User CRUD ─────────────────────────────────────────────────────────────

    /** List all users with optional ?search= by ID or name/email */
    public function usersIndex(Request $request)
    {
        $query = User::withCount(['tasks', 'projects'])->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            if (is_numeric($s)) {
                $query->where('id', $s);
            } else {
                $query->where(function ($q) use ($s) {
                    $q->where('name', 'like', "%{$s}%")
                      ->orWhere('email', 'like', "%{$s}%");
                });
            }
        }

        return response()->json([
            'success' => true,
            'data'    => $query->get(),
            'message' => 'Users retrieved successfully',
        ]);
    }

    /** Full detail of one user: profile + tasks (with status) + projects (with %) */
    public function userDetail(User $user)
    {
        $tasks = Task::where('user_id', $user->id)
            ->orderBy('due_date', 'asc')
            ->get(['id', 'title', 'category', 'priority', 'status', 'due_date', 'is_single_task', 'project_id', 'created_at']);

        $taskStats = [
            'total'           => $tasks->count(),
            'completed'       => $tasks->where('status', 'completed')->count(),
            'in_progress'     => $tasks->where('status', 'in-progress')->count(),
            'pending'         => $tasks->where('status', 'pending')->count(),
            'single'          => $tasks->where('is_single_task', true)->count(),
            'in_project'      => $tasks->where('is_single_task', false)->count(),
        ];
        $taskStats['completion_rate'] = $taskStats['total'] > 0
            ? round(($taskStats['completed'] / $taskStats['total']) * 100)
            : 0;

        $projects = Project::where('user_id', $user->id)
            ->withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => fn ($q) => $q->where('status', 'completed'),
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($p) {
                $p->completion_rate = $p->total_tasks > 0
                    ? round(($p->completed_tasks / $p->total_tasks) * 100)
                    : 0;
                return $p;
            });

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id'         => $user->id,
                    'name'       => $user->name,
                    'email'      => $user->email,
                    'is_admin'   => $user->is_admin,
                    'created_at' => $user->created_at->format('d M Y'),
                    'updated_at' => $user->updated_at->diffForHumans(),
                ],
                'task_stats' => $taskStats,
                'tasks'      => $tasks,
                'projects'   => $projects,
            ],
            'message' => 'User detail retrieved successfully',
        ]);
    }

    public function usersStore(Request $request)
    {
        $v = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'is_admin' => 'boolean',
        ]);

        $user = User::create([
            'name'     => $v['name'],
            'email'    => $v['email'],
            'password' => Hash::make($v['password']),
            'is_admin' => $v['is_admin'] ?? false,
        ]);

        return response()->json(['success' => true, 'data' => $user, 'message' => 'User created successfully'], 201);
    }

    public function usersShow(User $user)
    {
        return response()->json(['success' => true, 'data' => $user, 'message' => 'User retrieved successfully']);
    }

    public function usersUpdate(Request $request, User $user)
    {
        $v = $request->validate([
            'name'     => 'string|max:255',
            'email'    => 'string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'is_admin' => 'boolean',
        ]);

        $data = [
            'name'     => $v['name']     ?? $user->name,
            'email'    => $v['email']    ?? $user->email,
            'is_admin' => $v['is_admin'] ?? $user->is_admin,
        ];
        if (!empty($v['password'])) $data['password'] = Hash::make($v['password']);

        $user->update($data);
        return response()->json(['success' => true, 'data' => $user, 'message' => 'User updated successfully']);
    }

    public function usersDestroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete your own account'], 400);
        }
        Task::where('user_id', $user->id)->delete();
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User and associated tasks deleted successfully']);
    }

    // ── Task CRUD ─────────────────────────────────────────────────────────────

    public function tasksIndex()
    {
        $tasks = Task::with('user')->orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $tasks, 'message' => 'All tasks retrieved successfully']);
    }

    public function tasksStore(Request $request)
    {
        $v = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'required|in:work,personal,learning,health',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'required|date',
            'status'      => 'in:pending,in-progress,completed',
        ]);

        $task = Task::create([...$v, 'status' => $v['status'] ?? 'pending']);
        $task->load('user');
        return response()->json(['success' => true, 'data' => $task, 'message' => 'Task created successfully'], 201);
    }

    public function tasksShow(Task $task)
    {
        $task->load('user');
        return response()->json(['success' => true, 'data' => $task, 'message' => 'Task retrieved successfully']);
    }

    public function tasksUpdate(Request $request, Task $task)
    {
        $v = $request->validate([
            'user_id'     => 'exists:users,id',
            'title'       => 'string|max:255',
            'description' => 'nullable|string',
            'category'    => 'in:work,personal,learning,health',
            'priority'    => 'in:low,medium,high',
            'due_date'    => 'date',
            'status'      => 'in:pending,in-progress,completed',
        ]);
        $task->update($v);
        $task->load('user');
        return response()->json(['success' => true, 'data' => $task, 'message' => 'Task updated successfully']);
    }

    public function tasksDestroy(Task $task)
    {
        $task->delete();
        return response()->json(['success' => true, 'message' => 'Task deleted successfully']);
    }

    // ── Global Stats ──────────────────────────────────────────────────────────

    public function stats()
    {
        $total     = Task::count();
        $completed = Task::where('status', 'completed')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_users'     => User::count(),
                'total_tasks'     => $total,
                'total_projects'  => Project::count(),
                'completed_tasks' => $completed,
                'pending_tasks'   => Task::where('status', 'pending')->count(),
                'completion_rate' => $total > 0 ? round(($completed / $total) * 100) : 0,
                'recent_users'    => User::orderBy('created_at', 'desc')->limit(5)->get(),
                'recent_tasks'    => Task::with('user')->orderBy('created_at', 'desc')->limit(5)->get(),
            ],
            'message' => 'Admin stats retrieved successfully',
        ]);
    }
}
