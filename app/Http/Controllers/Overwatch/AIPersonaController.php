<?php

namespace App\Http\Controllers\Overwatch;

use App\Http\Controllers\Controller;
use App\Models\AIPersona;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AIPersonaController extends Controller
{
    public function index()
    {
        $personas = AIPersona::orderBy('id', 'DESC')->get();
        return view('overwatch.personas.index', compact('personas'));
    }

    public function create()
    {
        return view('overwatch.personas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:80',
            'tone' => 'nullable|string|max:120',
            'system_prompt' => 'required|string',
        ]);

        AIPersona::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'tone' => $request->tone,
            'prompt_strength' => $request->prompt_strength ?? null,
            'system_prompt' => $request->system_prompt,
            'is_active' => $request->has('is_active'),
        ]);


        return redirect()
            ->route('overwatch.personas.index')
            ->with('success', 'Persona created!');
    }

    public function edit(AIPersona $persona)
    {
        return view('overwatch.personas.edit', compact('persona'));
    }

    public function update(Request $request, AIPersona $persona)
    {
        $request->validate([
            'name' => 'required|string|max:80',
            'tone' => 'nullable|string|max:120',
            'system_prompt' => 'required|string',
        ]);

        $persona->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'tone' => $request->tone,
            'prompt_strength' => $request->prompt_strength ?? null,
            'system_prompt' => $request->system_prompt,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('overwatch.personas.index')
            ->with('success', 'Persona updated!');
    }

    public function destroy(AIPersona $persona)
    {
        $persona->delete();

        return redirect()
            ->route('overwatch.personas.index')
            ->with('success', 'Persona deleted!');
    }

    public function toggle(AIPersona $persona)
    {
        $persona->update([
            'is_active' => ! $persona->is_active
        ]);

        return redirect()
            ->route('overwatch.personas.index')
            ->with('success', 'Persona status updated!');
    }
}
