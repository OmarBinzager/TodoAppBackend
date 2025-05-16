<?php

namespace App\Http\Controllers;
use App\Models\Status;

use Illuminate\Http\Request;

class StatusController extends Controller
{
    //
    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        $status = new \App\Models\Status();
        $status->name = $request->input('name');
        $status->color = $request->input('color');
        $status->save();

        return response()->json(['message' => 'Status added successfully'], 201);
        
    }

    public function getAll()
    {
        $statuses = Status::all();
        return response()->json($statuses);
    }

    public function getId(Request $request) {
        $query = Status::query();
        foreach ($request->all() as $field => $value) {
            $query->where($field, $value);
        }
        $status = $query->first();
        if (!$status) return response()->json(['error' => 'Status not found'], 404);
        return response()->json($status);
    }

    public function delete($id) {
        $status = Status::find($id);
        if (!$status) return response()->json(['error' => 'Status not found'], 404);
        $status->delete();
        return response()->json(['message' => 'Status deleted']);
    }
}