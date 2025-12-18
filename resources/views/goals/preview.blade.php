@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    
    @if(session('info'))
        <div class="bg-comic-blue text-white p-4 rounded-xl comic-frame shadow-comic-pop-md mb-6">
            <p class="font-bold">{{ session('info') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-comic-red text-white p-4 rounded-xl comic-frame shadow-comic-pop-md mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li class="font-bold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <!-- Header -->
    <div class="bg-comic-yellow text-comic-dark p-8 rounded-2xl comic-frame shadow-comic-pop-lg mb-8">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-5xl">👀</span>
                    <h1 class="text-4xl font-black">Preview Your Plan</h1>
                </div>
                <p class="text-lg font-semibold ml-16">Review the AI-generated plan before adding events to your calendar</p>
            </div>
            <a href="{{ route('goals.show', $goal) }}" 
               class="comic-btn bg-white text-comic-dark text-lg px-6 py-2 rounded-lg shadow-comic-button hover:bg-gray-100 hover:scale-105 transition-transform">
                ← Back to Goal
            </a>
        </div>
    </div>

    @if(!$goal->ai_plan || !isset($goal->ai_plan['tasks']))
        <div class="bg-comic-pink text-white p-8 rounded-2xl comic-frame shadow-comic-pop-lg text-center">
            <h2 class="text-3xl font-black mb-2">No Plan Available!</h2>
            <p class="font-semibold">Unable to generate a plan. Please try creating the goal again.</p>
        </div>
    @else
        @php
            $plan = $goal->ai_plan;
            $durationDays = $plan['duration_days'] ?? 30;
            $tasks = $plan['tasks'] ?? [];
        @endphp

        <!-- Goal Info -->
        <div class="bg-comic-blue text-white p-6 rounded-xl comic-frame shadow-comic-pop-md mb-8">
            <h2 class="text-2xl font-black mb-2">{{ $goal->title }}</h2>
            @if($goal->description)
                <p class="opacity-90 mb-2">{{ $goal->description }}</p>
            @endif
            @if($goal->target_date)
                <p class="font-bold">Target: {{ $goal->target_date->format('F d, Y') }}</p>
            @endif
        </div>

        <!-- Plan Overview -->
        <div class="bg-white p-6 rounded-xl comic-frame shadow-comic-pop-md mb-8">
            <h2 class="text-2xl font-black mb-4">📋 Plan Summary</h2>
            <div class="grid md:grid-cols-3 gap-4 mb-6">
                <div class="bg-comic-yellow p-4 rounded-lg comic-frame text-center">
                    <div class="text-3xl font-black text-comic-dark">{{ $durationDays }}</div>
                    <div class="text-sm font-bold text-comic-dark mt-1">Days</div>
                </div>
                <div class="bg-comic-blue text-white p-4 rounded-lg comic-frame text-center">
                    <div class="text-3xl font-black">{{ count($tasks) }}</div>
                    <div class="text-sm font-bold mt-1">Task Types</div>
                </div>
                <div class="bg-comic-green text-white p-4 rounded-lg comic-frame text-center">
                    <div class="text-3xl font-black">
                        @php
                            $totalWeekly = array_sum(array_column($tasks, 'frequency_per_week'));
                            $totalEvents = 0;
                            foreach ($tasks as $task) {
                                $totalEvents += $task['frequency_per_week'] * ceil($durationDays / 7);
                            }
                        @endphp
                        {{ $totalEvents }}
                    </div>
                    <div class="text-sm font-bold mt-1">Total Events</div>
                </div>
            </div>
        </div>

        <!-- Tasks Preview -->
        <div class="bg-white p-6 rounded-xl comic-frame shadow-comic-pop-md mb-8">
            <h2 class="text-2xl font-black mb-4">📝 Tasks Overview</h2>
            <div class="space-y-4">
                @foreach($tasks as $index => $task)
                    <div class="bg-gray-50 p-4 rounded-lg comic-frame border-2 border-gray-200">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-comic-blue text-white rounded-full flex items-center justify-center font-black comic-frame">
                                    {{ $index + 1 }}
                                </div>
                                <h3 class="text-xl font-black">{{ $task['title'] }}</h3>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-3 ml-13">{{ $task['description'] }}</p>
                        <div class="flex gap-3 ml-13">
                            <span class="bg-comic-green text-white px-3 py-1 rounded-lg comic-frame font-bold text-sm">
                                ⏱️ {{ $task['estimated_minutes'] }} min
                            </span>
                            <span class="bg-comic-pink text-white px-3 py-1 rounded-lg comic-frame font-bold text-sm">
                                📅 {{ $task['frequency_per_week'] }}x/week
                            </span>
                            <span class="bg-comic-blue text-white px-3 py-1 rounded-lg comic-frame font-bold text-sm">
                                ~{{ $task['frequency_per_week'] * ceil($durationDays / 7) }} events
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Calendar Info -->
        <div class="bg-comic-yellow p-6 rounded-xl comic-frame shadow-comic-pop-md mb-8">
            <h3 class="text-xl font-black text-comic-dark mb-2">📅 Calendar Events</h3>
            <p class="text-comic-dark font-semibold mb-4">
                Events will be created in your Google Calendar at 9:00 AM (your timezone) on the scheduled days.
            </p>
            <ul class="list-disc list-inside text-comic-dark space-y-1 ml-4">
                <li>Each task will have its own calendar events based on frequency</li>
                <li>Events will span from today until your target date (or {{ $durationDays }} days)</li>
                <li>You can edit or delete events directly in Google Calendar</li>
                <li>You can pause or delete this goal anytime from the goal page</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 justify-center">
            <form action="{{ route('goals.confirm', $goal) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="comic-btn bg-comic-green text-white text-xl px-10 py-4 rounded-lg shadow-comic-button hover:bg-green-600 hover:scale-105 transition-transform">
                    ✅ Confirm & Create Calendar Events
                </button>
            </form>
            
            <a href="{{ route('goals.show', $goal) }}" 
               class="comic-btn bg-gray-300 text-comic-dark text-xl px-10 py-4 rounded-lg shadow-comic-button hover:bg-gray-400 hover:scale-105 transition-transform">
                Skip for Now
            </a>
        </div>

        <p class="text-center text-gray-600 mt-4 text-sm">
            You can always create calendar events later from the goal page
        </p>
    @endif

</div>
@endsection

