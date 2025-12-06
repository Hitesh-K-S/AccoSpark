<?php

namespace App\Http\Controllers\Overwatch;

use App\Http\Controllers\Controller;
use App\Models\User;

class OverwatchDashboardController extends Controller
{
    public function index()
    {
        return view('overwatch.dashboard', [
            'totalUsers'       => User::count(),
            'activeUsers'      => User::whereNull('banned_at')->count(),
            'bannedUsers'      => User::whereNotNull('banned_at')->count(),
            'newToday'         => User::whereDate('created_at', today())->count(),
        ]);
    }
}
