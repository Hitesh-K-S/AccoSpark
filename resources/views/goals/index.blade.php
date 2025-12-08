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
                <div class="bg-white p-6 rounded-xl comic-frame shadow-comic-pop-md">
                    <h3 class="text-2xl font-black mb-3">{{ $goal->title }}</h3>
                    <p class="text-gray-700 mb-3">{{ $goal->description }}</p>

                    <div class="font-bold">
                        Target:
                        <span class="text-comic-blue">
                            {{ $goal->target_date ?? 'No deadline' }}
                        </span>
                    </div>
                </div>
            @endforeach

        </div>

    @endif

</div>
@endsection
