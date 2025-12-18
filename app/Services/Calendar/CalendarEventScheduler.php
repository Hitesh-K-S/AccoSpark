<?php

namespace App\Services\Calendar;

use App\Models\Goal;
use App\Models\Task;
use App\Models\GoogleToken;
use Carbon\Carbon;
use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CalendarEventScheduler
{
    protected ?Calendar $calendar = null;

    /**
     * Schedule calendar events for all tasks in a goal
     *
     * @param Goal $goal Goal with tasks
     * @param array $taskData Array of task data from LLM
     * @param int $durationDays Duration in days
     * @return void
     */
    public function scheduleEvents(Goal $goal, array $taskData, int $durationDays): void
    {
        $user = $goal->user;

        // Check if user has Google Calendar connected
        $hasCalendar = GoogleToken::where('user_id', $user->id)->exists();

        if (!$hasCalendar) {
            Log::info('User does not have Google Calendar connected, skipping event creation', [
                'user_id' => $user->id,
                'goal_id' => $goal->id,
            ]);
            return;
        }

        // Initialize calendar client
        try {
            $this->calendar = $this->buildCalendarClient($user);
        } catch (\Exception $e) {
            Log::error('Failed to initialize Google Calendar client', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return;
        }

        $startDate = now()->startOfDay();
        $endDate = $goal->target_date 
            ? $goal->target_date->startOfDay() 
            : now()->addDays($durationDays)->startOfDay();

        // Create task instances and calendar events for each task template
        foreach ($taskData as $taskTemplate) {
            $this->scheduleTaskEvents(
                $goal,
                $taskTemplate,
                $startDate,
                $endDate
            );
        }
    }

    /**
     * Schedule events for a single task template
     *
     * @param Goal $goal Goal model
     * @param array $taskTemplate Task data from LLM
     * @param Carbon $startDate Start date
     * @param Carbon $endDate End date
     * @return void
     */
    protected function scheduleTaskEvents(
        Goal $goal,
        array $taskTemplate,
        Carbon $startDate,
        Carbon $endDate
    ): void {
        $frequency = (int) $taskTemplate['frequency_per_week'];
        $estimatedMinutes = (int) $taskTemplate['estimated_minutes'];

        // Calculate event dates based on frequency
        $eventDates = $this->calculateEventDates(
            $startDate,
            $endDate,
            $frequency
        );

        // Create a task record and calendar event for each date
        foreach ($eventDates as $eventDate) {
            // Create task record
            $task = Task::create([
                'goal_id' => $goal->id,
                'user_id' => $goal->user_id,
                'title' => $taskTemplate['title'],
                'description' => $taskTemplate['description'],
                'due_date' => $eventDate,
                'status' => 'pending',
                'type' => 'ai_generated',
                'ai_generated' => true,
                'estimated_minutes' => $estimatedMinutes,
                'frequency_per_week' => $frequency,
            ]);

            // Create calendar event
            $this->createCalendarEvent($task, $eventDate, $estimatedMinutes);
        }
    }

    /**
     * Calculate event dates based on frequency
     *
     * @param Carbon $startDate Start date
     * @param Carbon $endDate End date
     * @param int $frequencyPerWeek Frequency per week (1-7)
     * @return array Array of Carbon dates
     */
    protected function calculateEventDates(
        Carbon $startDate,
        Carbon $endDate,
        int $frequencyPerWeek
    ): array {
        $dates = [];
        $current = $startDate->copy();

        if ($frequencyPerWeek === 7) {
            // Daily: every day
            while ($current->lte($endDate)) {
                $dates[] = $current->copy();
                $current->addDay();
            }
        } else {
            // Distribute evenly across the week
            $dayOffsets = $this->getDayOffsets($frequencyPerWeek);
            
            while ($current->lte($endDate)) {
                foreach ($dayOffsets as $offset) {
                    $eventDate = $current->copy()->addDays($offset);
                    if ($eventDate->lte($endDate)) {
                        $dates[] = $eventDate->copy();
                    }
                }
                $current->addWeek();
            }
        }

        return $dates;
    }

    /**
     * Get day offsets for frequency distribution
     *
     * @param int $frequencyPerWeek Frequency per week
     * @return array Array of day offsets (0-6)
     */
    protected function getDayOffsets(int $frequencyPerWeek): array
    {
        // Distribute evenly across the week
        // Examples:
        // 1x/week: Monday (0)
        // 2x/week: Monday (0), Thursday (3)
        // 3x/week: Monday (0), Wednesday (2), Friday (4)
        // 4x/week: Monday (0), Tuesday (1), Thursday (3), Saturday (5)
        // 5x/week: Monday (0), Tuesday (1), Wednesday (2), Thursday (3), Friday (4)
        // 6x/week: Monday-Saturday (0-5)

        $offsets = [];
        $step = 7 / $frequencyPerWeek;

        for ($i = 0; $i < $frequencyPerWeek; $i++) {
            $offsets[] = (int) floor($i * $step);
        }

        return $offsets;
    }

    /**
     * Create a calendar event for a task
     *
     * @param Task $task Task model
     * @param Carbon $eventDate Event date
     * @param int $estimatedMinutes Duration in minutes
     * @return void
     */
    protected function createCalendarEvent(
        Task $task,
        Carbon $eventDate,
        int $estimatedMinutes
    ): void {
        if (!$this->calendar) {
            return;
        }

        // Get user timezone - use user preference or default to UTC
        // TODO: Add timezone field to users table for proper timezone support
        $userTimezone = $this->getUserTimezone($task->user_id);

        $startTime = $eventDate->copy()
            ->setTimezone($userTimezone)
            ->setTime(9, 0); // Default 9 AM in user's timezone
        $endTime = $startTime->copy()->addMinutes($estimatedMinutes);

        $event = new Event([
            'summary' => "📘 {$task->title} ({$estimatedMinutes} min)",
            'description' => $this->buildEventDescription($task),
            'start' => [
                'dateTime' => $startTime->toRfc3339String(),
                'timeZone' => $userTimezone,
            ],
            'end' => [
                'dateTime' => $endTime->toRfc3339String(),
                'timeZone' => $userTimezone,
            ],
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'popup', 'minutes' => 10],
                ],
            ],
        ]);

        try {
            $created = $this->calendar->events->insert('primary', $event);

            $task->update([
                'calendar_event_id' => $created->id,
                'calendar_synced' => true,
            ]);

            Log::info('Calendar event created', [
                'task_id' => $task->id,
                'event_id' => $created->id,
                'date' => $eventDate->toDateString(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create calendar event', [
                'task_id' => $task->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Build event description
     *
     * @param Task $task Task model
     * @return string Description text
     */
    protected function buildEventDescription(Task $task): string
    {
        return implode("\n", [
            "Focus on consistency.",
            "",
            "Task: {$task->description}",
            "",
            "Goal: {$task->goal->title}",
        ]);
    }

    /**
     * Build Google Calendar client
     *
     * @param \App\Models\User $user User model
     * @return Calendar Calendar service instance
     */
    protected function buildCalendarClient($user): Calendar
    {
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

        return new Calendar($client);
    }

    /**
     * Get user timezone
     * 
     * @param int $userId User ID
     * @return string Timezone string (e.g., 'America/New_York', 'Asia/Kolkata')
     */
    protected function getUserTimezone(int $userId): string
    {
        // TODO: Add timezone column to users table
        // For now, try to detect from Google Calendar or use sensible default
        // Default to UTC to avoid timezone bugs
        return config('app.timezone', 'UTC');
    }
}

