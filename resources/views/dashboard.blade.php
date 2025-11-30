@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">

    <!-- Comic Header -->
    <div class="bg-comic-blue text-white p-8 rounded-2xl comic-frame shadow-comic-pop-lg mb-10">
        <h1 class="text-4xl font-black flex items-center space-x-3">
            <span>🔥 HERO HQ</span>
        </h1>
        <p class="text-xl mt-2 font-bold">
            Welcome back, {{ auth()->user()->name }}!
        </p>

        <div class="flex space-x-6 mt-6 font-extrabold">
            <div class="bg-white text-comic-dark px-4 py-2 rounded comic-frame shadow-comic-pop-md">
                Streak: <span class="text-comic-red">0 days</span>
            </div>

            <div class="bg-white text-comic-dark px-4 py-2 rounded comic-frame shadow-comic-pop-md">
                XP: <span class="text-comic-blue">0</span>
            </div>

            <div class="bg-white text-comic-dark px-4 py-2 rounded comic-frame shadow-comic-pop-md">
                Level: <span class="text-comic-yellow">Rookie</span>
            </div>
        </div>
    </div>

    <!-- Today's Mission -->
    <div class="bg-comic-yellow p-8 rounded-2xl comic-frame shadow-comic-pop-lg mb-10 rotate-[-1deg]">
        <h2 class="text-3xl font-black text-comic-dark mb-4">🚀 TODAY'S MISSION</h2>
        <p class="text-comic-dark font-bold text-xl mb-6">
            You have no goals yet.
        </p>

        <a href="#" 
           class="comic-btn bg-comic-red text-white text-xl px-8 py-4 rounded-lg shadow-comic-button hover:bg-red-500 inline-block">
            CREATE YOUR FIRST GOAL
        </a>
    </div>

    <!-- Main Panels -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Goals Panel -->
        <div class="bg-white p-6 rounded-xl comic-frame shadow-comic-pop-md">
            <h3 class="text-2xl font-black mb-3">📘 Your Goals</h3>
            <p class="text-gray-700">You haven't created any goals yet.</p>
        </div>

        <!-- Insights -->
        <div class="bg-comic-blue text-white p-6 rounded-xl comic-frame shadow-comic-pop-md">
            <h3 class="text-2xl font-black mb-3">🧠 AI Insights</h3>
            <p class="font-semibold">Coming soon.</p>
        </div>

        <!-- Weekly Stats -->
        <div class="bg-comic-pink text-white p-6 rounded-xl comic-frame shadow-comic-pop-md">
            <h3 class="text-2xl font-black mb-3">📊 Weekly Progress</h3>
            <p>No data yet.</p>
        </div>

    </div>

</div>
@endsection
