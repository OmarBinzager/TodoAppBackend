<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Step;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Get overall dashboard statistics
     */
    public function getStats(): JsonResponse
    {
        $notStartedTasks = Task::where('status', 1)->count();

        $inProgressTasks = Task::where('status', 2)->count();

        $completedTasks = Task::where('status', 3)->count();

        $totalTasks = $notStartedTasks + $inProgressTasks + $completedTasks;

        return response()->json([
            'not_started' => [
                'count' => $notStartedTasks,
                'percentage' => $totalTasks > 0 ? round(($notStartedTasks / $totalTasks) * 100, 2) : 0
            ],
            'in_progress' => [
                'count' => $inProgressTasks,
                'percentage' => $totalTasks > 0 ? round(($inProgressTasks / $totalTasks) * 100, 2) : 0
            ],
            'completed' => [
                'count' => $completedTasks,
                'percentage' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0
            ],
            'total_tasks' => $totalTasks
        ]);
    }

    /**
     * Get recent tasks for the dashboard with their steps
     */
    public function getRecentTasks(): JsonResponse
    {
        $recentTasks = Task::with(['category', 'priority', 'status'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function($task) {
                $steps = Step::where('task_id', $task->id)
                    ->orderBy('step_index', 'asc')
                    ->get()
                    ->map(function($step) {
                        return [
                            'id' => $step->id,
                            'step' => $step->step,
                            'step_index' => $step->step_index,
                        ];
                    });

                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'picture' => $task->picture,
                    'due_date' => $task->due_date,
                    'category' => $task->category,
                    'priority' => $task->priority,
                    'status' => $task->status,
                    'steps' => $steps
                ];
            });
        return response()->json($recentTasks);
    }

    /**
     * Get completed tasks with their steps
     */
    public function getCompletedTasks(): JsonResponse
    {
        $completedTasks = Task::with(['category', 'priority', 'status'])
            ->where('status', 3)
            ->latest()
            ->take(5)
            ->get()
            ->map(function($task) {
                $steps = Step::where('task_id', $task->id)
                    ->orderBy('step_index', 'asc')
                    ->get()
                    ->map(function($step) {
                        return [
                            'id' => $step->id,
                            'step' => $step->step,
                            'step_index' => $step->step_index,
                        ];
                    });

                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'picture' => $task->picture,
                    'due_date' => $task->due_date,
                    'category' => $task->category,
                    'priority' => $task->priority,
                    'status' => $task->status,
                    'steps' => $steps
                ];
            });

        return response()->json($completedTasks);
    }
} 