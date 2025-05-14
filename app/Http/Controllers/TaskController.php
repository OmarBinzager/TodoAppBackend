<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Models\Step;
use Illuminate\Http\Request;

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
        return response()->json(Task::join('priorities', 'tasks.priority', '=', 'priorities.id')
        ->join('categories', 'tasks.category', '=', 'categories.id')
        ->join('statuses', 'tasks.status', '=', 'statuses.id')
        ->select('tasks.*', 'priorities.name as priority_name', 'priorities.color as priority_color', 'categories.name as category_name', 'statuses.name as status_name', 'statuses.color as status_color')
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
        $task = Task::create($request->all());
        return response()->json($task, 201);
    }

    public function update($id, Request $request) {
        $task = Task::find($id);
        if (!$task) return response()->json(['error' => 'Task not found'], 404);
        $task->update($request->all());
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
                $step = $task->steps()->find($stepData['id']);
                if ($step) $step->update($stepData);
            }
        }
        return response()->json(['message' => 'Steps updated']);
    }

    public function deleteStep($id, $stepId) {
        $task = Task::find($id);
        if (!$task) return response()->json(['error' => 'Task not found'], 404);
        $step = $task->steps()->find($stepId);
        if (!$step) return response()->json(['error' => 'Step not found'], 404);
        $step->delete();
        return response()->json(['message' => 'Step deleted']);
    }

    public function deleteSteps($id, Request $request) {
        $task = Task::find($id);
        if (!$task) return response()->json(['error' => 'Task not found'], 404);
        $stepIds = $request->input('step_ids', []);
        $task->steps()->whereIn('id', $stepIds)->delete();
        return response()->json(['message' => 'Steps deleted']);
    }
}