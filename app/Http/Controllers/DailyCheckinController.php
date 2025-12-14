<?php

namespace App\Http\Controllers;

use App\Models\DailyCheckin;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DailyCheckinController extends Controller
{
    public function create(Request $request)
    {
        $today = Carbon::today();

        $existing = DailyCheckin::where('user_id', $request->user()->id)
            ->where('date', $today)
            ->first();

        return view('checkin.create', [
            'alreadyCheckedIn' => $existing !== null,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today();

        // Validate inputs (protect DB + AI)
        $validated = $request->validate([
            'summary_text' => ['nullable', 'string', 'max:5000'],
            'energy_level' => ['nullable', 'integer', 'between:1,5'],
            'mood_level' => ['nullable', 'integer', 'between:1,5'],
            'self_reported_done' => ['nullable', 'boolean'],
        ]);

        // Atomic write (NO race conditions)
        DailyCheckin::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => $today,
            ],
            array_merge(
                ['checkin_type' => 'submitted'],
                $validated
            )
        );

        // Redirect safely
        return redirect()
            ->route('dashboard')
            ->with('success', 'Check-in submitted successfully!');
    }



    public function history(Request $request)
    {
        $checkins = DailyCheckin::where('user_id', $request->user()->id)
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        return view('checkin.history', compact('checkins'));
    }
}
