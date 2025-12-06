@extends('layouts.admin-comic')

@section('content')

<h1 class="text-4xl font-black mb-8 text-comic-dark">SYSTEM DASHBOARD</h1>

<div class="grid md:grid-cols-4 gap-8">

    <!-- Total Users -->
    <div class="bg-comic-blue text-white p-6 rounded-xl shadow-comic-pop-md comic-frame">
        <h2 class="text-2xl font-black">TOTAL USERS</h2>
        <p class="text-5xl font-black mt-4">{{ $totalUsers }}</p>
    </div>

    <!-- Active Users -->
    <div class="bg-comic-green text-white p-6 rounded-xl shadow-comic-pop-md comic-frame">
        <h2 class="text-2xl font-black">ACTIVE USERS</h2>
        <p class="text-5xl font-black mt-4">{{ $activeUsers }}</p>
    </div>

    <!-- Banned Users -->
    <div class="bg-comic-red text-white p-6 rounded-xl shadow-comic-pop-md comic-frame">
        <h2 class="text-2xl font-black">BANNED USERS</h2>
        <p class="text-5xl font-black mt-4">{{ $bannedUsers }}</p>
    </div>

    <!-- New Today -->
    <div class="bg-comic-yellow text-comic-dark p-6 rounded-xl shadow-comic-pop-md comic-frame">
        <h2 class="text-2xl font-black">NEW TODAY</h2>
        <p class="text-5xl font-black mt-4">{{ $newToday }}</p>
    </div>

</div>

<!-- System Pulse -->
<div class="mt-16 bg-white p-10 rounded-2xl comic-frame shadow-comic-pop-lg">

    <h2 class="text-3xl font-black mb-6 flex items-center">
        <svg class="w-8 h-8 text-comic-red mr-3 comic-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M4 12h4l2 8 4-16 2 8h4"></path>
        </svg>
        SYSTEM PULSE
    </h2>

    <p class="text-xl font-bold max-w-3xl">
        Everything looks stable, chief. The AI engines are running at optimal capacity. 
        Keep an eye on user behavior, persona performance, and system flags as we scale.
    </p>

    <div class="mt-8 flex space-x-4">
        <div class="comic-btn bg-comic-pink text-white px-6 py-3 rounded shadow-comic-button">
            View Logs
        </div>
        <div class="comic-btn bg-comic-blue text-white px-6 py-3 rounded shadow-comic-button">
            AI Events
        </div>
    </div>

</div>

@endsection
