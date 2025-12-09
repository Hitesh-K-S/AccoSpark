@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10 space-y-12">

    <!-- PROFILE HEADER -->
    <div class="bg-comic-blue text-white p-8 rounded-2xl comic-frame shadow-comic-pop-lg">
        <h1 class="text-4xl font-black">🧑‍🚀 YOUR CONTROL PANEL</h1>
        <p class="text-xl font-bold mt-2">Manage your identity, connections, and AI experience.</p>
    </div>


    <!-- PROFILE INFORMATION -->
    <div class="bg-white p-8 rounded-2xl comic-frame shadow-comic-pop-md">
        <h2 class="text-3xl font-black mb-6">📛 Profile Information</h2>

        @if (session('status') === 'profile-updated')
            <p class="text-comic-green font-bold mb-4">Profile updated successfully!</p>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- NAME -->
            <div>
                <label class="font-black text-lg">Name</label>
                <input type="text" name="name"
                       class="comic-input w-full p-3 rounded mt-1 text-black"
                       value="{{ old('name', $user->name) }}" required>
                @error('name') <p class="text-comic-red font-bold">{{ $message }}</p> @enderror
            </div>

            <!-- EMAIL -->
            <div>
                <label class="font-black text-lg">Email</label>
                <input type="email" name="email"
                       class="comic-input w-full p-3 rounded mt-1 text-black"
                       value="{{ old('email', $user->email) }}" required>
                @error('email') <p class="text-comic-red font-bold">{{ $message }}</p> @enderror
            </div>

            <button class="comic-btn bg-comic-yellow text-comic-dark px-6 py-3 rounded shadow-comic-button text-xl">
                SAVE CHANGES
            </button>
        </form>
    </div>

    <!-- AI PERSONA SELECTION -->
    <div class="bg-comic-yellow p-8 rounded-2xl comic-frame shadow-comic-pop-md">
        <h2 class="text-3xl font-black mb-6 text-comic-dark">🤖 Choose Your AI Persona</h2>

        <form action="{{ route('profile.persona.update') }}" method="POST" class="space-y-4">
            @csrf

            <select name="persona_id" class="comic-input w-full p-3 rounded text-black font-bold">
                @foreach($personas as $persona)
                    <option value="{{ $persona->id }}"
                            @if($user->persona_id == $persona->id) selected @endif>
                        {{ $persona->name }} ({{ $persona->tone ?? 'neutral' }})
                    </option>
                @endforeach
            </select>

            <button class="comic-btn bg-comic-blue text-white px-6 py-3 rounded shadow-comic-button text-xl">
                UPDATE PERSONA
            </button>
        </form>
    </div>


    <!-- GOOGLE CALENDAR CONNECTION -->
    <div class="mt-10 bg-white p-6 rounded-2xl comic-frame shadow-comic-pop-md">

        <h2 class="text-3xl font-black mb-4 flex items-center space-x-3">
            <span>📅 Google Calendar</span>
        </h2>

        @php
            $googleConnected = auth()->user()->googleToken()->exists();
        @endphp

        @if ($googleConnected)
            <!-- If CONNECTED -->
            <div class="p-4 bg-comic-green text-white font-bold rounded-xl comic-frame shadow-comic-pop-md">
                Connected to Google Calendar ✔
            </div>

            <form action="{{ route('calendar.disconnect') }}" method="POST" class="mt-4 flex justify-end">
                @csrf
                <button class="comic-btn bg-comic-red text-white px-6 py-3 rounded-lg shadow-comic-button hover:bg-red-600">
                    Disconnect Calendar
                </button>
            </form>

        @else
            <!-- If NOT CONNECTED -->
            <div class="p-4 bg-comic-yellow text-comic-dark font-bold rounded-xl comic-frame shadow-comic-pop-md">
                Calendar not connected.
            </div>

            <a href="{{ route('calendar.connect') }}"
            class="items-end comic-btn bg-comic-blue text-white px-6 py-3 mt-4 inline-block rounded-lg shadow-comic-button hover:bg-blue-600">
                Connect Google Calendar
            </a>
        @endif
    </div>


 
    <!-- DANGER ZONE -->
    <div class="bg-comic-red text-white p-8 rounded-2xl comic-frame shadow-comic-pop-md">
        <h2 class="text-3xl font-black mb-4">⚠️ Danger Zone</h2>
        <p class="font-bold mb-6">Deleting your account is permanent. All data will be gone forever.</p>

        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')

            <input type="password" name="password"
                   placeholder="Confirm your password"
                   class="comic-input w-full p-3 rounded mt-1 text-black mb-4" required>

            <button class="comic-btn bg-white text-comic-dark px-6 py-3 rounded shadow-comic-button text-xl">
                DELETE ACCOUNT
            </button>
        </form>
    </div>

</div>
@endsection
