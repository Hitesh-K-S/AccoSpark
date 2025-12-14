@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    {{-- Persona-aware heading --}}
    <div class="bg-comic-blue text-white p-6 rounded-xl comic-frame shadow-comic-pop-md mb-8">
        <h1 class="text-3xl font-black">
            {{ match($persona->tone) {
                'friendly' => '🌱 How did today go?',
                'strict' => '🎯 Daily Report',
                'chaotic' => '🔥 Alright. What happened?',
                default => 'Daily Check-In',
            } }}
        </h1>

        <p class="mt-2 font-bold opacity-90">
            Be honest. This isn’t grading you — it’s helping future you.
        </p>
    </div>

    @if($alreadyCheckedIn)
        <div class="bg-comic-yellow p-4 rounded comic-frame font-bold">
            You’ve already checked in today.
        </div>
    @else

    <form method="POST" action="{{ url('/checkin') }}" class="space-y-8">
        @csrf

        {{-- TASKS --}}
        <div class="bg-white p-6 rounded-xl comic-frame shadow-comic-pop-md">
            <h2 class="text-2xl font-black mb-4">📋 Today’s Tasks</h2>

            @forelse($tasks as $task)
                <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                    <span class="font-bold">{{ $task->title }}</span>

                    <div class="flex space-x-4">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox"
                                   name="completed_task_ids[]"
                                   value="{{ $task->id }}"
                                   class="accent-green-600">
                            <span class="text-sm font-bold">Done</span>
                        </label>

                        <label class="flex items-center space-x-2">
                            <input type="checkbox"
                                   name="skipped_task_ids[]"
                                   value="{{ $task->id }}"
                                   class="accent-red-600">
                            <span class="text-sm font-bold">Skipped</span>
                        </label>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 font-bold">
                    No tasks planned for today.
                </p>
            @endforelse
        </div>

        {{-- CONTEXT --}}
        <div class="bg-white p-6 rounded-xl comic-frame shadow-comic-pop-md">
            <h2 class="text-2xl font-black mb-4">📝 Notes (optional)</h2>

            <textarea
                name="summary_text"
                rows="4"
                class="w-full comic-input rounded-md p-3"
                placeholder="Anything worth noting? Low energy, distractions, wins…"></textarea>
        </div>

        {{-- OPTIONAL SIGNALS --}}
        <div class="bg-white p-6 rounded-xl comic-frame shadow-comic-pop-md">
            <h2 class="text-2xl font-black mb-4">⚡ Energy & Mood (optional)</h2>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="font-bold block mb-1">Energy (1–5)</label>
                    <input type="number" min="1" max="5" name="energy_level"
                           class="comic-input w-full rounded-md p-2">
                </div>

                <div>
                    <label class="font-bold block mb-1">Mood (1–5)</label>
                    <input type="number" min="1" max="5" name="mood_level"
                           class="comic-input w-full rounded-md p-2">
                </div>
            </div>
        </div>

        {{-- SUBMIT --}}
        <div class="text-center">
            <button type="submit"
                class="comic-btn bg-comic-red text-white text-xl px-10 py-4 rounded-lg shadow-comic-button">
                Submit Check-In
            </button>
        </div>

    </form>
    @endif

</div>
@endsection
