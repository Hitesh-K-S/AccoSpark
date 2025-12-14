<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use App\Services\AI\GoalAIPlannerService;



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

    public function store(Request $request, GoalAIPlannerService $planner)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'target_date' => 'nullable|date',
        ]);

        $goal = Goal::create([
            'user_id'     => auth()->id(),
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'target_date' => $validated['target_date'] ?? null,
        ]);

        //  Generate roadmap
        $roadmap = $planner->generateRoadmap($goal);

        $goal->ai_plan = $roadmap;
        $goal->save();

        return redirect()
            ->route('goals.index')
            ->with('success', 'Goal created with AI roadmap!');
    }

}
