<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
    // Get all projects for authenticated user
    public function index()
    {
        $projects = Project::where('user_id', auth()->id())
            ->with('tasks')
            ->orderBy('created_at', 'desc')
            ->get();

        $count = $projects->count();

        return response()->json([
            'success' => true,
            'data' => $projects,
            'total' => $count,
            'message' => 'Projects retrieved successfully'
        ]);
    }

    // Create new project
    public function store(Request $request)
    {
        $idempotencyKey = $request->header('X-Idempotency-Key');
        
        if ($idempotencyKey) {
            $cacheKey = 'idempotency_' . $idempotencyKey;
            
            if (Cache::has($cacheKey)) {
                return response()->json([
                    'success' => true,
                    'data' => Cache::get($cacheKey),
                    'message' => 'Project already created (idempotent response)'
                ], 201);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,completed,archived',
        ]);

        $project = Project::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        if ($idempotencyKey) {
            Cache::put($cacheKey, $project->toArray(), now()->addHours(24));
        }

        return response()->json([
            'success' => true,
            'data' => $project,
            'message' => 'Project created successfully'
        ], 201);
    }

    // Update project
    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:active,completed,archived',
        ]);

        $project->update($validated);

        return response()->json([
            'success' => true,
            'data' => $project,
            'message' => 'Project updated successfully'
        ]);
    }

    // Delete project
    public function destroy(Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully'
        ]);
    }

    // Get single project with tasks
    public function show(Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $project->load('tasks');

        return response()->json([
            'success' => true,
            'data' => $project,
            'message' => 'Project retrieved successfully'
        ]);
    }
}

