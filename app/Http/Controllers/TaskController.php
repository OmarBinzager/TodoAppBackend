<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(){
        $task=Task::all();
        return response()->json($task);
    }

    public function store(StoreTaskRequest $request){
        $task=task::create($request->validated());
        return response()->json($task,201);
    }

    public function getAll() {
        return response()->json(Task::where('tasks.user_id', Auth::user()->id)->leftJoin('priorities', 'tasks.priority', '=', 'priorities.id')
        ->leftJoin('categories', 'tasks.category', '=', 'categories.id')
        ->leftJoin('statuses', 'tasks.status', '=', 'statuses.id')
        ->select('tasks.*', 'priorities.name as priority_name', 'priorities.color as priority_color', 'categories.name as category_name', 'statuses.name as status_name', 'statuses.color as status_color')
        ->get());
    }

    public function search(Request $request) {
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);
        // return response()->json($request->search);
        return response()->json(Task::where('tasks.user_id', Auth::user()->id)->leftJoin('priorities', 'tasks.priority', '=', 'priorities.id')
        ->leftJoin('categories', 'tasks.category', '=', 'categories.id')
        ->leftJoin('statuses', 'tasks.status', '=', 'statuses.id')
        ->select('tasks.*', 'priorities.name as priority_name', 'priorities.color as priority_color', 'categories.name as category_name', 'statuses.name as status_name', 'statuses.color as status_color')
        ->where('title', 'like', "%{$request->search}%")
        ->orWhere('description', 'like', "%{$request->search}%")
        ->get());
    }

    public function getId(Request $request) {
        $query = Task::query();
        foreach ($request->all() as $field => $value) {
            $query->where($field, $value);
        }
        $task = $query->first();
        if (!$task) return response()->json(['error' => 'Task not found'], 404);
        return response()->json($task);
    }

    public function add(Request $request) {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'picture' => 'nullable|image|max:4096',
            'category' => 'nullable|exists:categories,id',
            'priority' => 'nullable|exists:priorities,id',
            'status' => 'Required|exists:statuses,id',
            'created_at' => 'date|nullable',
            'completed_at' => 'date|nullable',
            'due_date' => 'required|date',
        ]);
        if($request->hasFile('picture')) {
            $path = $request->file('picture')->store('images', 'public');
        }
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'picture' => $path ?? null,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->status,
            'created_at' => $request->created_at,
            'completed_at' => $request->completed_at,
            'due_date' => $request->due_date,
            'user_id' => Auth::user()->id
        ]);
        return response()->json($task, 201);
    }

    public function update($id, Request $request) {
        $task = Task::find($id);
        if (!$task) return response()->json(['error' => 'Task not found'], 404);
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'picture' => 'nullable|image|max:4096',
            'category' => 'nullable|exists:categories,id',
            'priority' => 'nullable|exists:priorities,id',
            'status' => 'Required|exists:statuses,id',
            'created_at' => 'date|nullable',
            'completed_at' => 'date|nullable',
            'due_date' => 'required|date',
        ]);
        $path = $task->picture; // Default to current picture path
        if($request->hasFile('picture')) {
            // Delete old picture if exists
            if ($task->picture) {
                $oldPicturePath = public_path('storage/' . $task->picture);
                if (file_exists($oldPicturePath)) {
                    unlink($oldPicturePath);
                }
            }
            // Store new picture
            $path = $request->file('picture')->store('images', 'public');
        }
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'picture' => $path ?? null,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->status,
            'created_at' => $request->created_at,
            'completed_at' => $request->completed_at,
            'due_date' => $request->due_date,
            'user_id' => Auth::user()->id]);
        return response()->json($task);
    }

    public function delete($id) {
        $task = Task::find($id);
        if (!$task) return response()->json(['error' => 'Task not found'], 404);
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }

    public function getSteps($id) {
        $steps = Step::where('task_id', $id)->get();
        return response()->json($steps ?? []);
    }

    public function addStep($id, Request $request) {
        $task = Task::find($id);
        if (!$task) return response()->json(['error' => 'Task not found'], 404);
        $step = Step::create($request->all());
        return response()->json($step, 201);
    }

    public function addSteps($id, Request $request) {
        $request->validate([
            'steps' => 'nullable|array|',
        ]);
        $task = Task::find($id);
        if (!$task) return response()->json(['error' => 'Task not found'], 404);

        $steps = $request->input('steps', []);
        $created = [];
        foreach ($steps as $stepData) {
            $stepData['task_id'] = $id; // Ensure task_id is set
            $created[] = Step::create($stepData);
        }
            return response()->json($created, 201);
    }

    public function updateSteps($id, Request $request) {
        $task = Task::find($id);
        if (!$task) return response()->json(['error' => 'Task not found'], 404);
        $steps = $request->input('steps', []);
        foreach ($steps as $stepData) {
            if (isset($stepData['id'])) {
                $step = Step::find($stepData['id']);
                if ($step) $step->update($stepData);
            }
        }
        return response()->json(['message' => 'Steps updated']);
    }

    public function deleteStep($id) {
        $step = Step::find($id);
        if (!$step) return response()->json(['error' => 'Step not found'], 404);
        $step->delete();
        return response()->json(['message' => 'Step deleted']);
    }

    public function deleteSteps($id) {
        $task = Task::find($id);
        if (!$task) return response()->json(['error' => 'Task not found'], 404);
        Step::where('task_id', $id)->delete();
        return response()->json(['message' => 'Steps deleted']);
    }
}