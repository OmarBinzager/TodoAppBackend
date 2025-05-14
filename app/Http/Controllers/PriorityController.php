<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriorityController extends Controller
{
    public function getAll() {
        return response()->json(\App\Models\Priority::all());
    }

    public function add(Request $request) {
        $priority = \App\Models\Priority::create($request->all());
        return response()->json($priority, 201);
    }

    public function update($id, Request $request) {
        $priority = \App\Models\Priority::find($id);
        if (!$priority) return response()->json(['error' => 'Priority not found'], 404);
        $priority->update($request->all());
        return response()->json($priority);
    }

    public function getId(Request $request) {
        $query = \App\Models\Priority::query();
        foreach ($request->all() as $field => $value) {
            $query->where($field, $value);
        }
        $priority = $query->first();
        if (!$priority) return response()->json(['error' => 'Priority not found'], 404);
        return response()->json($priority);
    }

    public function delete($id) {
        $priority = \App\Models\Priority::find($id);
        if (!$priority) return response()->json(['error' => 'Priority not found'], 404);
        $priority->delete();
        return response()->json(['message' => 'Priority deleted']);
    }
}