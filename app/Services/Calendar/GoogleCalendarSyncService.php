<?php

namespace App\Services\Calendar;

use App\Models\Task;
use App\Models\GoogleToken;
use Carbon\Carbon;
use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Auth;

class GoogleCalendarSyncService
{
    protected Calendar $calendar;

    public function __construct()
    {
        $client = $this->buildClient();
        $this->calendar = new Calendar($client);
    }

    /**
     * Build Google Client from stored user token
     */
    protected function buildClient(): GoogleClient
    {
        $user = Auth::user();

        $token = GoogleToken::where('user_id', $user->id)->firstOrFail();

        $client = new GoogleClient();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessType('offline');
        $client->setScopes([
            'https://www.googleapis.com/auth/calendar',
            'https://www.googleapis.com/auth/calendar.events',
        ]);

        $client->setAccessToken([
            'access_token' => decrypt($token->access_token),
            'refresh_token' => $token->refresh_token ? decrypt($token->refresh_token) : null,
            'expires_at' => optional($token->expires_at)->timestamp,
        ]);

        // Refresh token if expired
        if ($client->isAccessTokenExpired() && $client->getRefreshToken()) {
            $newToken = $client->fetchAccessTokenWithRefreshToken(
                $client->getRefreshToken()
            );

            $token->update([
                'access_token' => encrypt($newToken['access_token']),
                'expires_at' => now()->addSeconds($newToken['expires_in']),
            ]);

            $client->setAccessToken($newToken);
        }

        return $client;
    }

    /**
     * Public entry point
     */
    public function sync(Task $task): void
    {
        // Only sync pending tasks
        if ($task->status !== 'pending') {
            $this->removeEvent($task);
            return;
        }

        if ($task->calendar_event_id) {
            $this->updateEvent($task);
        } else {
            $this->createEvent($task);
        }
    }

    /**
     * Create a new calendar event
     */
    protected function createEvent(Task $task): void
    {
        $start = $this->startTime($task);
        $end   = $this->endTime($task);

        $event = new Event([
            'summary' => "🎯 [AccoSpark] {$task->title}",
            'description' => $this->description($task),
            'start' => [
                'dateTime' => $start->toRfc3339String(),
            ],
            'end' => [
                'dateTime' => $end->toRfc3339String(),
            ],
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'popup', 'minutes' => 10],
                ],
            ],
        ]);

        $created = $this->calendar->events->insert('primary', $event);

        $task->update([
            'calendar_event_id' => $created->id,
            'calendar_synced' => true,
        ]);
    }

    /**
     * Update an existing calendar event
     */
    protected function updateEvent(Task $task): void
    {
        try {
            $event = $this->calendar->events->get(
                'primary',
                $task->calendar_event_id
            );
        } catch (\Exception $e) {
            // Event deleted manually → recreate
            $task->update([
                'calendar_event_id' => null,
                'calendar_synced' => false,
            ]);

            $this->createEvent($task);
            return;
        }

        $start = $this->startTime($task);
        $end   = $this->endTime($task);

        $event->setSummary("🎯 [AccoSpark] {$task->title}");
        $event->setDescription($this->description($task));
        $event->setStart(['dateTime' => $start->toRfc3339String()]);
        $event->setEnd(['dateTime' => $end->toRfc3339String()]);

        $this->calendar->events->update(
            'primary',
            $event->getId(),
            $event
        );
    }

    /**
     * Remove calendar event (pause / recovery / ban)
     */
    protected function removeEvent(Task $task): void
    {
        if (!$task->calendar_event_id) {
            return;
        }

        try {
            $this->calendar->events->delete(
                'primary',
                $task->calendar_event_id
            );
        } catch (\Exception $e) {
            // Event already gone — ignore
        }

        $task->update([
            'calendar_event_id' => null,
            'calendar_synced' => false,
        ]);
    }

    /**
     * Helpers
     */
    protected function startTime(Task $task): Carbon
    {
        return Carbon::parse($task->due_date)
            ->setHour(9)
            ->setMinute(0);
    }

    protected function endTime(Task $task): Carbon
    {
        return $this->startTime($task)
            ->addMinutes($task->estimated_minutes ?? 30);
    }

    protected function description(Task $task): string
    {
        return implode("\n", [
            "Goal: {$task->goal->title}",
            "",
            "Focus:",
            "• One task",
            "• No multitasking",
            "• Progress > perfection",
            "",
            "— AccoSpark",
        ]);
    }
}
