<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\FocusSession;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Get weekly stats for the analytics page.
     */
    public function stats(Request $request)
    {
        $userId = auth()->id();
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if (!$startDate || !$endDate) {
            $now = Carbon::now();
            $startDate = $now->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
            $endDate = $now->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();
        }

        // 1. Tasks Finished & Unfinished (based on due_date falling in this week)
        $tasks = Task::where('user_id', $userId)
            ->whereBetween('due_date', [$startDate, $endDate])
            ->get();

        $tasksFinished = $tasks->where('status', 'completed')->count();
        $tasksUnfinished = $tasks->where('status', 'pending')->count();

        // 2. Goals (We treat High Priority Tasks as Goals)
        $goals = $tasks->where('priority', 'high');
        $goalsCompleted = $goals->where('status', 'completed')->count();
        
        // Goals "terlewat" (missed) = pending and due_date < today
        $today = Carbon::today()->toDateString();
        $goalsMissed = $goals->where('status', 'pending')
            ->filter(function($task) use ($today) {
                return Carbon::parse($task->due_date)->toDateString() < $today;
            })->count();

        // 3. Focus Sessions
        $focusSessionsCount = FocusSession::where('user_id', $userId)
            ->whereBetween('completed_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        // 4. Productivity Score Calculation
        // Formula: perbandingan antara goals completed dan goals terlewat JUGA task finished dan un finished
        $totalGoalsForScore = $goalsCompleted + $goalsMissed;
        $goalRatio = $totalGoalsForScore > 0 ? $goalsCompleted / $totalGoalsForScore : 0;

        $totalTasksForScore = $tasksFinished + $tasksUnfinished;
        $taskRatio = $totalTasksForScore > 0 ? $tasksFinished / $totalTasksForScore : 0;

        if ($totalGoalsForScore > 0 && $totalTasksForScore > 0) {
            $productivityScore = (($goalRatio + $taskRatio) / 2) * 100;
        } elseif ($totalGoalsForScore > 0) {
            $productivityScore = $goalRatio * 100;
        } elseif ($totalTasksForScore > 0) {
            $productivityScore = $taskRatio * 100;
        } else {
            $productivityScore = 0;
        }

        // Ensure score is between 0-100
        $productivityScore = max(0, min(100, round($productivityScore)));

        return response()->json([
            'success' => true,
            'data' => [
                'goals_completed' => $goalsCompleted,
                'goals_missed' => $goalsMissed,
                'tasks_finished' => $tasksFinished,
                'tasks_unfinished' => $tasksUnfinished,
                'productivity_score' => $productivityScore,
                'focus_sessions' => $focusSessionsCount,
                'total_tasks' => $totalTasksForScore,
                'percentage' => $totalTasksForScore > 0 ? round(($tasksFinished / $totalTasksForScore) * 100) : 0,
                'daily_stats' => $this->getDailyStats($userId, $startDate, $endDate)
            ]
        ]);
    }

    /**
     * Get daily completion percentage for the bar chart.
     */
    private function getDailyStats($userId, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $dailyData = [];
        
        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            $dateStr = $date->toDateString();
            $dayTasks = Task::where('user_id', $userId)
                ->whereDate('due_date', $dateStr)
                ->get();
            
            $total = $dayTasks->count();
            $completed = $dayTasks->where('status', 'completed')->count();
            
            $dailyData[] = [
                'day' => $date->format('D'),
                'percentage' => $total > 0 ? round(($completed / $total) * 100) : 0
            ];
        }
        
        return $dailyData;
    }
}
