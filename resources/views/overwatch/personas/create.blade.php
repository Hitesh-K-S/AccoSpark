@extends('layouts.admin-comic')

@section('content')
<div class="max-w-3xl mx-auto py-10">

    <!-- Header -->
    <h1 class="text-4xl font-black text-comic-dark mb-8">
        Create New Persona
    </h1>

    <div class="bg-white comic-frame p-8 rounded-xl shadow-comic-pop-lg">

        <form action="{{ route('overwatch.personas.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label class="block font-black text-lg mb-2">Persona Name</label>
                <input type="text" name="name"
                       class="comic-input w-full px-4 py-3 rounded-md"
                       placeholder="CHAOS_GREMLIN" required>
            </div>

            <!-- Tone -->
            <div>
                <label class="block font-black text-lg mb-2">Tone (optional)</label>
                <input type="text" name="tone"
                       class="comic-input w-full px-4 py-3 rounded-md"
                       placeholder="e.g., sarcastic, friendly, strict">
            </div>

            <!-- System Prompt -->
            <div>
                <label class="block font-black text-lg mb-2">System Prompt</label>
                <textarea name="system_prompt"
                          rows="7"
                          class="comic-input w-full px-4 py-3 rounded-md"
                          placeholder="Describe how this persona behaves..."
                          required></textarea>
            </div>

            <!-- Is Active -->
            <div class="flex items-center space-x-3">
                <input type="checkbox" name="is_active" checked class="w-5 h-5">
                <label class="font-bold text-lg">Active</label>
            </div>

            <!-- Prompt Strength Slider -->
            <div>
                <label class="block font-black text-lg mb-3">Persona Strength</label>
            
                <input type="range"
                       name="prompt_strength"
                       min="1"
                       max="10"
                       value="7"
                       class="w-full h-3 rounded-lg cursor-pointer accent-comic-blue"
                       oninput="document.getElementById('strength_val').textContent = this.value">
            
                <p class="mt-2 font-bold text-comic-dark">
                    Strength: <span id="strength_val" class="font-black text-comic-blue">7</span>/10
                </p>
            </div>


            <!-- Submit -->
            <button class="comic-btn bg-comic-yellow text-comic-dark px-8 py-4 rounded-lg shadow-comic-button hover:bg-yellow-400 text-xl font-black">
                SAVE PERSONA
            </button>
        </form>

    </div>

</div>
@endsection
