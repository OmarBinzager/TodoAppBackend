<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Step;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Get overall dashboard statistics
     */
    public function getStats(): JsonResponse
    {
        $notStartedTasks = Task::where('status', 1)->where('user_id', Auth::user()->id)->count();

        $inProgressTasks = Task::where('status', 2)->where('user_id', Auth::user()->id)->count();

        $completedTasks = Task::where('status', 3)->where('user_id', Auth::user()->id)->count();

        $totalTasks = $notStartedTasks + $inProgressTasks + $completedTasks;

        return response()->json([
            'not_started' => $totalTasks > 0 ? round(($notStartedTasks / $totalTasks) * 100, 2) : 0
            ,
            'in_progress' => $totalTasks > 0 ? round(($inProgressTasks / $totalTasks) * 100, 2) : 0
            ,
            'completed' =>  $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0
            ,
            'total_tasks' => $totalTasks
        ]);
    }

    /**
     * Get recent tasks for the dashboard with their steps
     */
    public function getRecentTasks(): JsonResponse
    {
        // return response()->json(Auth::user()->id);
        $recentTasks = Task::leftJoin('priorities', 'tasks.priority', '=', 'priorities.id')
            ->leftJoin('categories', 'tasks.category', '=', 'categories.id')
            ->leftJoin('statuses', 'tasks.status', '=', 'statuses.id')
            ->select('tasks.*', 'priorities.name as priority_name', 'priorities.color as priority_color', 'categories.name as category_name', 'statuses.name as status_name', 'statuses.color as status_color')
            ->where('tasks.user_id', Auth::user()->id)
            ->whereNot('status', 3) // Exclude completed tasks
            ->latest()
            ->take(10)
            ->orderByDesc('completed_at')
            ->get();
        return response()->json($recentTasks);
    }

    /**
     * Get completed tasks with their steps
     */
    public function getCompletedTasks()
    {
        $completedTasks = Task::leftJoin('priorities', 'tasks.priority', '=', 'priorities.id')
            ->leftJoin('categories', 'tasks.category', '=', 'categories.id')
            ->leftJoin('statuses', 'tasks.status', '=', 'statuses.id')
            ->select('tasks.*', 'priorities.name as priority_name', 'priorities.color as priority_color', 'categories.name as category_name', 'statuses.name as status_name', 'statuses.color as status_color')
            ->where('tasks.user_id', Auth::user()->id)
            ->where('status', 3,)
            ->latest()
            ->take(5)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($completedTasks);
    }
} 