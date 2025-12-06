<?php

namespace App\Http\Controllers\Overwatch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('overwatch.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            
            $user = Auth::user();

            if (! $user->is_admin) {
                Auth::logout();
                return back()->withErrors(['email' => 'You are not allowed to access admin panel.']);
            }

            return redirect()->route('overwatch.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('overwatch.login');
    }
}
