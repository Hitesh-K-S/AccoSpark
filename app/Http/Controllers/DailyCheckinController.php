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

        // prevent double submission
        if (DailyCheckin::where('user_id', $user->id)->where('date', $today)->exists()) {
            return redirect()->route('dashboard');
        }

        DailyCheckin::create([
            'user_id' => $user->id,
            'date' => $today,
            'checkin_type' => 'submitted',
            'summary_text' => $request->summary_text,
            'energy_level' => $request->energy_level,
            'mood_level' => $request->mood_level,
            'self_reported_done' => $request->self_reported_done,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Check-in submitted 🧠');
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
