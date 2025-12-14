@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">

    
    <div class="bg-comic-blue text-white p-8 rounded-2xl comic-frame shadow-comic-pop-lg">

        <h1 class="text-4xl font-black mb-4">📝 Daily Check-In</h1>

        @if ($alreadyCheckedIn)
            <p class="text-xl font-bold">
                You already checked in today.  
                Go live your life 😎
            </p>
        @else

        <form method="POST" action="{{ route('checkin.store') }}" class="space-y-6">
            @csrf

            <!-- Summary -->
            <div>
                <label class="font-black text-lg">What actually happened today?</label>
                <textarea
                    name="summary_text"
                    class="w-full mt-2 p-3 rounded comic-input text-black"
                    rows="4"
                    placeholder="No judgment. Just facts."
                ></textarea>
            </div>

            <!-- Sliders -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="font-black">Energy ⚡ (1–5)</label>
                    <input type="range" min="1" max="5" name="energy_level" class="w-full">
                </div>

                <div>
                    <label class="font-black">Mood 🧠 (1–5)</label>
                    <input type="range" min="1" max="5" name="mood_level" class="w-full">
                </div>
            </div>

            <!-- Done toggle -->
            <div class="flex items-center space-x-3">
                <input type="checkbox" name="self_reported_done" value="1">
                <span class="font-bold">Did you do what you planned?</span>
            </div>

            <button
                class="comic-btn bg-comic-yellow text-comic-dark px-8 py-4 rounded-lg shadow-comic-button text-xl">
                SUBMIT CHECK-IN
            </button>

        </form>
        @endif
    </div>
</div>
@endsection
