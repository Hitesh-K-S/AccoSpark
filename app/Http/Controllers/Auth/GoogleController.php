<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['google' => 'Google login failed.']);
        }

        // Match user by email FIRST (this is critical)
        $user = User::where('email', $googleUser->getEmail())->first();

        // If user exists → check ban
        if ($user && $user->banned_at !== null) {
            return redirect('/login')->withErrors([
                'email' => 'Your account has been banned.'
            ]);
        }

        // If no existing user → create a new one
        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(str()->random(16)),
            ]);
        }

        // Now safe to login (user is not banned)
        Auth::login($user);

        return redirect('/dashboard');
    }
}
