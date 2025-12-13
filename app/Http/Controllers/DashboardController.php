<?php

namespace App\Http\Controllers;

use App\Models\DailyCheckin;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today();

        $todayCheckin = DailyCheckin::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        return view('dashboard', compact('todayCheckin'));
    }
}
