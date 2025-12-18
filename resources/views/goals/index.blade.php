@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-black text-comic-dark">🎯 YOUR GOALS</h1>

        <a href="{{ route('goals.create') }}"
           class="comic-btn bg-comic-yellow text-comic-dark text-xl px-6 py-3 rounded-lg shadow-comic-button">
           + Add Goal
        </a>
    </div>

    @if($goals->isEmpty())
        <div class="bg-comic-pink text-white p-8 rounded-2xl comic-frame shadow-comic-pop-lg text-center">
            <h2 class="text-3xl font-black mb-2">No Goals Yet!</h2>
            <p class="font-semibold">Time to set your first mission, legend.</p>
        </div>
    @else

        <div class="grid md:grid-cols-2 gap-8">

            @foreach($goals as $goal)
                <a href="{{ route('goals.show', $goal) }}" 
                   class="goal-card bg-white p-6 rounded-xl comic-frame shadow-comic-pop-md transition-all duration-200 hover:shadow-comic-pop-lg hover:-translate-y-1 cursor-pointer block group relative overflow-hidden">
                    @if($goal->ai_plan && isset($goal->ai_plan['tasks']))
                        <div class="absolute top-2 right-2 w-3 h-3 bg-comic-green rounded-full comic-frame animate-pulse"></div>
                    @endif
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-2xl font-black group-hover:text-comic-blue transition-colors">{{ $goal->title }}</h3>
                    </div>
                    <p class="text-gray-700 mb-3">{{ $goal->description }}</p>

                    <div class="flex justify-between items-center">
                        <div class="font-bold">
                            Target:
                            <span class="text-comic-blue">
                                {{ $goal->target_date ? $goal->target_date->format('M d, Y') : 'No deadline' }}
                            </span>
                        </div>
                        @if($goal->tasks_count > 0)
                            <span class="bg-comic-green text-white px-3 py-1 rounded-full text-sm font-bold comic-frame group-hover:scale-110 transition-transform inline-block">
                                {{ $goal->tasks_count }} events
                            </span>
                        @elseif($goal->ai_plan && isset($goal->ai_plan['tasks']))
                            <span class="bg-comic-blue text-white px-3 py-1 rounded-full text-sm font-bold comic-frame group-hover:scale-110 transition-transform inline-block">
                                View Plan →
                            </span>
                        @else
                            <span class="bg-comic-yellow text-comic-dark px-3 py-1 rounded-full text-sm font-bold comic-frame group-hover:scale-110 transition-transform inline-block">
                                Generating...
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach

        </div>

    @endif

</div>
@endsection
