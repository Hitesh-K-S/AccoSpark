@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">

    <h1 class="text-4xl font-black mb-8">📆 Check-In History</h1>

    @if($checkins->isEmpty())
        <div class="bg-comic-yellow p-6 rounded-xl comic-frame shadow-comic-pop-md">
            <p class="font-bold text-lg">No check-ins yet.</p>
        </div>
    @else

    <div class="space-y-6">
        @foreach($checkins as $checkin)
            <div class="bg-white p-6 rounded-xl comic-frame shadow-comic-pop-md">

                <div class="flex justify-between items-center mb-2">
                    <span class="font-black text-lg">
                        {{ $checkin->date->format('d M Y') }}
                    </span>

                    @if($checkin->checkin_type === 'submitted')
                        <span class="bg-comic-green text-white px-3 py-1 rounded font-bold">
                            SUBMITTED
                        </span>
                    @else
                        <span class="bg-comic-red text-white px-3 py-1 rounded font-bold">
                            MISSED
                        </span>
                    @endif
                </div>

                @if($checkin->summary_text)
                    <p class="mt-3 text-gray-800 font-medium">
                        “{{ $checkin->summary_text }}”
                    </p>
                @endif

                <div class="flex space-x-6 mt-4 font-bold text-sm">
                    @if($checkin->energy_level)
                        <span>⚡ Energy: {{ $checkin->energy_level }}/5</span>
                    @endif
                    @if($checkin->mood_level)
                        <span>🧠 Mood: {{ $checkin->mood_level }}/5</span>
                    @endif
                </div>

            </div>
        @endforeach
    </div>

    @endif
</div>
@endsection
