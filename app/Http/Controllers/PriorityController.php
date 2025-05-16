<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriorityController extends Controller
{
    public function getAll() {
        return response()->json(\App\Models\Priority::where('user_id', Auth::user()->id)->get());
    }

    public function add(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:10',
        ]);
        $priority = \App\Models\Priority::create([...$request->all(), 'user_id' => Auth::user()->id]);
        return response()->json($priority, 201);
    }

    public function update($id, Request $request) {
        $priority = \App\Models\Priority::find($id);
        if (!$priority) return response()->json(['error' => 'Priority not found'], 404);
        $priority->update([...$request->all(), 'user_id' => Auth::user()->id]);
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