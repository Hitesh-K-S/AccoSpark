@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    
    @if(session('success'))
        <div class="bg-comic-green text-white p-4 rounded-xl comic-frame shadow-comic-pop-md mb-6">
            <p class="font-bold">{{ session('success') }}</p>
        </div>
    @endif

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
    <div class="bg-comic-blue text-white p-8 rounded-2xl comic-frame shadow-comic-pop-lg mb-8 animate-slide-in">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-5xl">🎯</span>
                    <h1 class="text-4xl font-black">{{ $goal->title }}</h1>
                </div>
                @if($goal->description)
                    <p class="text-lg font-semibold opacity-90 ml-16">{{ $goal->description }}</p>
                @endif
            </div>
            <div class="flex gap-3 items-center">
                <a href="{{ route('goals.index') }}" 
                   class="comic-btn bg-white text-comic-blue text-lg px-6 py-2 rounded-lg shadow-comic-button hover:bg-gray-100 hover:scale-105 transition-transform">
                    ← Back
                </a>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="mt-4 pt-4 border-t-2 border-white border-opacity-30 flex flex-wrap gap-3">
            @if($goal->status === 'paused')
                <form action="{{ route('goals.resume', $goal) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="comic-btn bg-comic-green text-white text-base px-5 py-2 rounded-lg shadow-comic-button hover:bg-green-600 hover:scale-105 transition-transform">
                        ▶️ Resume Goal
                    </button>
                </form>
            @elseif($goal->status === 'active')
                <form action="{{ route('goals.pause', $goal) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="comic-btn bg-comic-yellow text-comic-dark text-base px-5 py-2 rounded-lg shadow-comic-button hover:bg-yellow-400 hover:scale-105 transition-transform">
                        ⏸️ Pause Goal
                    </button>
                </form>
            @endif
            
            <form action="{{ route('goals.destroy', $goal) }}" method="POST" class="inline" 
                  onsubmit="return confirm('Are you sure you want to delete this goal? This will also remove all calendar events. This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="comic-btn bg-comic-red text-white text-base px-5 py-2 rounded-lg shadow-comic-button hover:bg-red-600 hover:scale-105 transition-transform">
                    🗑️ Delete Goal
                </button>
            </form>
            
            @if($goal->status === 'paused')
                <span class="bg-comic-yellow text-comic-dark px-4 py-2 rounded-lg comic-frame font-bold text-sm">
                    ⏸️ Paused
                </span>
            @elseif($goal->status === 'completed')
                <span class="bg-comic-green text-white px-4 py-2 rounded-lg comic-frame font-bold text-sm">
                    ✅ Completed
                </span>
            @elseif($goal->status === 'abandoned')
                <span class="bg-gray-500 text-white px-4 py-2 rounded-lg comic-frame font-bold text-sm">
                    ❌ Abandoned
                </span>
            @endif
        </div>
        
        @if($goal->target_date)
            <div class="mt-4 pt-4 border-t-2 border-white border-opacity-30 flex items-center gap-2">
                <span class="text-2xl">📅</span>
                <span class="font-bold text-lg">Target Date: </span>
                <span class="text-xl">{{ $goal->target_date->format('F d, Y') }}</span>
            </div>
        @endif
    </div>

    @if(!$goal->ai_plan || !isset($goal->ai_plan['tasks']))
        <div class="bg-comic-pink text-white p-8 rounded-2xl comic-frame shadow-comic-pop-lg text-center">
            <h2 class="text-3xl font-black mb-2">No Plan Generated Yet!</h2>
            <p class="font-semibold">The AI plan is being generated. Please check back soon.</p>
        </div>
    @else
        @php
            $plan = $goal->ai_plan;
            $durationDays = $plan['duration_days'] ?? 30;
            $tasks = $plan['tasks'] ?? [];
        @endphp

        <!-- Plan Overview -->
        <div class="bg-comic-yellow p-6 rounded-xl comic-frame shadow-comic-pop-md mb-8 animate-slide-in">
            <div class="flex items-center gap-4">
                <div class="text-5xl">🎯</div>
                <div>
                    <h2 class="text-2xl font-black text-comic-dark mb-1">Your AI-Generated Plan</h2>
                    <p class="font-bold text-comic-dark">
                        <span class="text-3xl">{{ $durationDays }}</span> days • 
                        <span class="text-3xl">{{ count($tasks) }}</span> task types
                    </p>
                </div>
            </div>
        </div>

        <!-- Tasks Grid -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            @foreach($tasks as $index => $task)
                <div class="task-card bg-white p-6 rounded-xl comic-frame shadow-comic-pop-md hover:shadow-comic-pop-lg transition-all duration-300 hover:-translate-y-1 cursor-pointer"
                     style="animation-delay: {{ $index * 100 }}ms">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-comic-blue text-white rounded-full flex items-center justify-center font-black text-xl comic-frame task-number pulse-on-hover">
                                {{ $index + 1 }}
                            </div>
                            <h3 class="text-xl font-black task-title">{{ $task['title'] }}</h3>
                        </div>
                    </div>
                    
                    <p class="text-gray-700 mb-4 leading-relaxed task-description">{{ $task['description'] }}</p>
                    
                    <div class="flex gap-4 flex-wrap">
                        <div class="badge bg-comic-green text-white px-4 py-2 rounded-lg comic-frame font-bold flex items-center gap-2 hover:scale-110 transition-transform">
                            <span>⏱️</span>
                            <span>{{ $task['estimated_minutes'] }} min</span>
                        </div>
                        <div class="badge bg-comic-pink text-white px-4 py-2 rounded-lg comic-frame font-bold flex items-center gap-2 hover:scale-110 transition-transform">
                            <span>📅</span>
                            <span>{{ $task['frequency_per_week'] }}x/week</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Stats Card -->
        <div class="bg-white p-6 rounded-xl comic-frame shadow-comic-pop-md mb-8">
            <h3 class="text-2xl font-black mb-4">📊 Plan Statistics</h3>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="bg-comic-yellow p-4 rounded-lg comic-frame text-center">
                    <div class="text-3xl font-black text-comic-dark">{{ $goal->tasks_count ?? 0 }}</div>
                    <div class="text-sm font-bold text-comic-dark mt-1">Total Events</div>
                </div>
                <div class="bg-comic-blue text-white p-4 rounded-lg comic-frame text-center">
                    <div class="text-3xl font-black">
                        @php
                            $totalMinutes = array_sum(array_column($tasks, 'estimated_minutes'));
                            $avgMinutes = count($tasks) > 0 ? round($totalMinutes / count($tasks)) : 0;
                        @endphp
                        {{ $avgMinutes }}
                    </div>
                    <div class="text-sm font-bold mt-1">Avg Minutes</div>
                </div>
                <div class="bg-comic-green text-white p-4 rounded-lg comic-frame text-center">
                    <div class="text-3xl font-black">
                        @php
                            $totalWeekly = array_sum(array_column($tasks, 'frequency_per_week'));
                        @endphp
                        {{ $totalWeekly }}
                    </div>
                    <div class="text-sm font-bold mt-1">Weekly Sessions</div>
                </div>
            </div>
        </div>

        <!-- Create Calendar Events Button (if not created yet) -->
        @if($goal->tasks_count == 0)
            <div class="bg-comic-yellow p-6 rounded-xl comic-frame shadow-comic-pop-md text-center">
                <h3 class="text-xl font-black text-comic-dark mb-2">📅 Calendar Events Not Created Yet</h3>
                <p class="text-comic-dark font-semibold mb-4">Add these tasks to your Google Calendar to get reminders and stay on track!</p>
                <form action="{{ route('goals.confirm', $goal) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="comic-btn bg-comic-green text-white text-lg px-8 py-3 rounded-lg shadow-comic-button hover:bg-green-600 hover:scale-105 transition-transform">
                        ✅ Create Calendar Events
                    </button>
                </form>
            </div>
        @endif
    @endif

</div>

<style>
    @keyframes slide-in {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pop-in {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    @keyframes bounce-gentle {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-3px);
        }
    }

    @keyframes spin-slow {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .animate-slide-in {
        animation: slide-in 0.5s ease-out;
    }

    .task-card {
        animation: pop-in 0.5s ease-out backwards;
        position: relative;
        overflow: hidden;
    }

    .task-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }

    .task-card:hover::before {
        left: 100%;
    }

    .task-number {
        transition: transform 0.3s ease;
    }

    .task-card:hover .task-number {
        transform: scale(1.15) rotate(10deg);
        animation: pulse 1s ease-in-out infinite;
    }

    .pulse-on-hover:hover {
        animation: pulse 0.6s ease-in-out;
    }

    .task-title {
        transition: color 0.2s ease;
    }

    .task-card:hover .task-title {
        color: #3B82F6;
    }

    .task-description {
        transition: transform 0.2s ease;
    }

    .task-card:hover .task-description {
        transform: translateX(4px);
    }

    .badge {
        transition: all 0.2s ease;
    }

    .badge:hover {
        transform: scale(1.1);
        box-shadow: 4px 4px 0px 0px #1F2937;
    }

    .animate-spin-slow {
        animation: spin-slow 3s linear infinite;
        display: inline-block;
    }

    .bounce-gentle {
        animation: bounce-gentle 2s ease-in-out infinite;
        display: inline-block;
    }

    .goal-card {
        text-decoration: none;
        color: inherit;
    }

    .goal-card:hover {
        text-decoration: none;
    }

    .goal-card:active {
        transform: translate(2px, 2px);
        box-shadow: 2px 2px 0px 0px #1F2937 !important;
    }
</style>
@endsection

