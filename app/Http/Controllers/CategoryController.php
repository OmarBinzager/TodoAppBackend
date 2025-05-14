<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getAll() {
        return response()->json(\App\Models\Category::all());
    }

    public function add(Request $request) {
        $category = \App\Models\Category::create($request->all());
        return response()->json($category, 201);
    }

    public function update($id, Request $request) {
        $category = \App\Models\Category::find($id);
        if (!$category) return response()->json(['error' => 'Category not found'], 404);
        $category->update($request->all());
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