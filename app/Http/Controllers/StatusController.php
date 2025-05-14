<?php

namespace App\Http\Controllers;

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
        $statuses = \App\Models\Status::all();
        return response()->json($statuses);
    }
}