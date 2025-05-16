<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function getAll() {
        return response()->json(\App\Models\Category::where('user_id', Auth::user()->id)->get());
    }

    public function add(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $category = \App\Models\Category::create([...$request->all(), 'user_id' => Auth::user()->id]);
        return response()->json($category, 201);
    }

    public function update($id, Request $request) {
        $category = \App\Models\Category::find($id);
        if (!$category) return response()->json(['error' => 'Category not found'], 404);
        $category->update([...$request->all(), 'user_id' => Auth::user()->id]);
        return response()->json($category);
    }

    public function getId(Request $request) {
        $query = \App\Models\Category::query();
        foreach ($request->all() as $field => $value) {
            $query->where($field, $value);
        }
        $category = $query->first();
        if (!$category) return response()->json(['error' => 'Category not found'], 404);
        return response()->json($category);
    }

    public function delete($id) {
        $category = \App\Models\Category::find($id);
        if (!$category) return response()->json(['error' => 'Category not found'], 404);
        $category->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}