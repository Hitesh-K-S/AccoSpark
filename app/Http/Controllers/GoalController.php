<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function index()
    {
        $goals = Goal::where('user_id', auth()->id())->get();

        return view('goals.index', compact('goals'));
    }

    public function create()
    {
        return view('goals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'target_date' => 'nullable|date',
        ]);

        Goal::create([
            'user_id'      => auth()->id(),
            'title'        => $validated['title'],
            'description'  => $validated['description'] ?? null,
            'target_date'  => $validated['target_date'] ?? null,
        ]);

        return redirect()->route('goals.index')->with('success', 'Goal created!');
    }
}
