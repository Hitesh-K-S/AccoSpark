@extends('layouts.admin-comic')

@section('content')
<div class="max-w-3xl mx-auto py-10">

    <!-- Header -->
    <h1 class="text-4xl font-black text-comic-dark mb-8">
        Edit Persona: {{ $persona->name }}
    </h1>

    <div class="bg-white comic-frame p-8 rounded-xl shadow-comic-pop-lg">

        <form action="{{ route('overwatch.personas.update', $persona->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label class="block font-black text-lg mb-2">Persona Name</label>
                <input type="text" name="name"
                       class="comic-input w-full px-4 py-3 rounded-md"
                       value="{{ $persona->name }}" required>
            </div>

            <!-- Tone -->
            <div>
                <label class="block font-black text-lg mb-2">Tone</label>
                <input type="text" name="tone"
                       class="comic-input w-full px-4 py-3 rounded-md"
                       value="{{ $persona->tone }}">
            </div>

            <!-- System Prompt -->
            <div>
                <label class="block font-black text-lg mb-2">System Prompt</label>
                <textarea name="system_prompt"
                          rows="7"
                          class="comic-input w-full px-4 py-3 rounded-md"
                          required>{{ $persona->system_prompt }}</textarea>
            </div>

            <!-- Is Active -->
            <div class="flex items-center space-x-3">
                <input type="checkbox" name="is_active"
                       class="w-5 h-5"
                       @if($persona->is_active) checked @endif>
                <label class="font-bold text-lg">Active</label>
            </div>

            <!-- Prompt Strength Slider -->
            <div>
                <label class="block font-black text-lg mb-3">Persona Strength</label>
            
                <input type="range"
                       name="prompt_strength"
                       min="1"
                       max="10"
                       value="{{ $persona->prompt_strength ?? 7 }}"
                       class="w-full h-3 rounded-lg cursor-pointer accent-comic-blue"
                       oninput="document.getElementById('strength_val').textContent = this.value">
            
                <p class="mt-2 font-bold text-comic-dark">
                    Strength: 
                    <span id="strength_val" class="font-black text-comic-blue">
                        {{ $persona->prompt_strength ?? 7 }}
                    </span>/10
                </p>
            </div>


            <!-- Submit -->
            <button class="comic-btn bg-comic-blue text-white px-8 py-4 rounded-lg shadow-comic-button hover:bg-blue-600 text-xl font-black">
                UPDATE PERSONA
            </button>
        </form>

    </div>

</div>
@endsection
