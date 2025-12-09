<?php

namespace App\Services;

use App\Models\GoogleToken;
use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Illuminate\Support\Facades\Auth;

class GoogleCalendarService
{
    /**
     * Return a fully authenticated Google Calendar API client
     */
    public function getClientForUser($user = null)
    {
        $user = $user ?: Auth::user();
        $tokenRow = GoogleToken::where('user_id', $user->id)->first();

        if (!$tokenRow) {
            return null; // user has not connected Google Calendar
        }

        $client = new GoogleClient();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(route('calendar.callback'));
        $client->setAccessType('offline');

        // Load tokens
        $client->setAccessToken([
            'access_token' => decrypt($tokenRow->access_token),
            'refresh_token' => $tokenRow->refresh_token ? decrypt($tokenRow->refresh_token) : null,
            'expires_in' => 3600,
            'created' => now()->subHour()->timestamp, // force refresh logic to work properly
        ]);

        // Refresh token if expired
        if ($client->isAccessTokenExpired()) {
            $newToken = $client->fetchAccessTokenWithRefreshToken(
                $client->getRefreshToken()
            );

            if (!isset($newToken['access_token'])) {
                // Token is invalid → disconnect automatically
                $tokenRow->delete();
                return null;
            }

            // Save refreshed token
            $tokenRow->update([
                'access_token' => encrypt($newToken['access_token']),
                'expires_at' => now()->addSeconds($newToken['expires_in'] ?? 3600),
            ]);

            $client->setAccessToken($newToken);
        }

        return new Calendar($client);
    }
}
