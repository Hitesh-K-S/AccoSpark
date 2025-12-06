<?php

namespace App\Http\Controllers\Overwatch;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('overwatch.users.index', compact('users'));
    }

    public function toggleBan(User $user)
    {
        // Toggle logic
        if ($user->banned_at) {
            $user->banned_at = null;
        } else {
            $user->banned_at = now();
        }

        $user->save();

        return redirect()->back()->with('status', 'User updated successfully.');
    }
}
