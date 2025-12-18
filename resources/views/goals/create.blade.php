@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    <div class="bg-comic-blue text-white p-8 rounded-2xl comic-frame shadow-comic-pop-lg mb-8">
        <h1 class="text-4xl font-black">📝 CREATE A NEW GOAL</h1>
        <p class="font-bold text-lg mt-2">Your quest begins here, hero!</p>
    </div>

    <div class="bg-white p-8 rounded-2xl comic-frame shadow-comic-pop-md">

        <form method="POST" action="{{ route('goals.store') }}">
            @csrf

            <!-- Title -->
            <label class="block font-black text-xl mb-2">Goal Title</label>
            <input type="text" name="title" 
                class="comic-input w-full p-3 rounded mb-6" 
                placeholder="Ex: Build a SaaS, Learn React">

            <!-- Description -->
            <label class="block font-black text-xl mb-2">Description</label>
            <textarea name="description" rows="4"
                class="comic-input w-full p-3 rounded mb-6"
                placeholder="What exactly do you want to achieve?"></textarea>

            <!-- Target Date -->
            <label class="block font-black text-xl mb-2">Target Date</label>
            <input type="date" name="target_date"
                class="comic-input w-full p-3 rounded mb-6">

            <!-- Goal Details (Free-form) -->
            <label class="block font-black text-xl mb-2">Goal Details <span class="text-sm font-normal text-gray-600">(Optional - helps AI generate better tasks)</span></label>
            <textarea name="goal_details" rows="4"
                class="comic-input w-full p-3 rounded mb-6"
                placeholder="Example: I want to study DSA consistently for the next 30 days. I can give 1 hour per day."></textarea>
            <p class="text-sm text-gray-600 mb-6">Provide additional context about your goal, timeline, and available time. This helps our AI create more accurate daily tasks.</p>

            <button class="comic-btn bg-comic-red text-white text-xl px-8 py-3 rounded-lg shadow-comic-button hover:bg-red-500">
                CREATE GOAL
            </button>
        </form>

    </div>

</div>
@endsection
