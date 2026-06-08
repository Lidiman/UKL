<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FocusSession;
use Carbon\Carbon;

class FocusSessionController extends Controller
{
    /**
     * Get focus sessions for the current day.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();

        // Get today's sessions
        $sessions = FocusSession::where('user_id', $user->id)
            ->whereBetween('completed_at', [$startOfDay, $endOfDay])
            ->orderBy('completed_at', 'desc')
            ->take(10)
            ->get();

        // Calculate total duration for today
        $totalMinutes = FocusSession::where('user_id', $user->id)
            ->whereBetween('completed_at', [$startOfDay, $endOfDay])
            ->sum('duration');

        return response()->json([
            'success' => true,
            'data' => [
                'sessions' => $sessions,
                'total_minutes' => $totalMinutes,
            ],
            'message' => 'Focus sessions retrieved successfully.'
        ]);
    }

    /**
     * Store a new focus session.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
        ]);

        $session = FocusSession::create([
            'user_id' => auth()->id(),
            'task_name' => $validated['task_name'],
            'duration' => $validated['duration'],
            'completed_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $session,
            'message' => 'Focus session saved successfully.'
        ], 201);
    }
}
