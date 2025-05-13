<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
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
}
