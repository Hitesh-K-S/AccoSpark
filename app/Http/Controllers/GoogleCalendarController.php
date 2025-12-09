<?php

namespace App\Http\Controllers;

use App\Models\GoogleToken;
use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Auth;
use Google\Service\Calendar;


class GoogleCalendarController extends Controller
{
    private function googleClient()
    {
        $client = new GoogleClient();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(route('calendar.callback'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setScopes([
            "https://www.googleapis.com/auth/calendar",
            "https://www.googleapis.com/auth/calendar.events"
        ]);

        return $client;
    }

    // STEP 1: Redirect user to Google
    public function connect()
    {
        $client = $this->googleClient();
        $authUrl = $client->createAuthUrl();

        return redirect($authUrl);
    }

    // STEP 2: Handle callback
    public function callback(Request $request)
    {
        if (!$request->has('code')) {
            return redirect('/profile')->with('error', 'Google authentication failed.');
        }

        $client = $this->googleClient();
        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        if (isset($token['error'])) {
            return redirect('/profile')->with('error', 'Google login error.');
        }

        $user = Auth::user();

        GoogleToken::updateOrCreate(
            ['user_id' => $user->id],
            [
                'provider' => 'google',
                'access_token' => encrypt($token['access_token']),
                'refresh_token' => isset($token['refresh_token']) ? encrypt($token['refresh_token']) : null,
                'expires_at' => now()->addSeconds($token['expires_in']),
            ]
        );

        return redirect('/profile')->with('success', 'Google Calendar connected!');
    }

    // Disconnect Calendar
    public function disconnect(Request $request)
    {
        $userId = Auth::id();

        GoogleToken::where('user_id', $userId)->delete();

        return redirect('/profile')->with('success', 'Google Calendar disconnected.');
    }
}
