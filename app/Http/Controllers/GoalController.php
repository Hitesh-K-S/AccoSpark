<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use App\Services\AI\GoalTaskLLMService;
use App\Services\AI\TaskValidator;
use App\Services\Calendar\CalendarEventScheduler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GoalController extends Controller
{
    public function index()
    {
        $goals = Goal::where('user_id', auth()->id())
            ->withCount('tasks')
            ->get();

        return view('goals.index', compact('goals'));
    }

    public function create()
    {
        return view('goals.create');
    }

    public function show(Goal $goal)
    {
        // Ensure user owns this goal
        abort_if($goal->user_id !== auth()->id(), 403);

        // Load tasks count for display
        $goal->loadCount('tasks');

        return view('goals.show', compact('goal'));
    }

    /**
     * Show preview of AI-generated plan before creating calendar events
     */
    public function preview(Goal $goal)
    {
        abort_if($goal->user_id !== auth()->id(), 403);

        if (!$goal->ai_plan || !isset($goal->ai_plan['tasks'])) {
            return redirect()
                ->route('goals.show', $goal)
                ->withErrors(['error' => 'No plan available to preview.']);
        }

        $goal->loadCount('tasks');
        
        return view('goals.preview', compact('goal'));
    }

    /**
     * Confirm and create calendar events after preview
     */
    public function confirm(
        Goal $goal,
        CalendarEventScheduler $scheduler
    ) {
        abort_if($goal->user_id !== auth()->id(), 403);

        if (!$goal->ai_plan || !isset($goal->ai_plan['tasks'])) {
            return redirect()
                ->route('goals.show', $goal)
                ->withErrors(['error' => 'No plan available.']);
        }

        // Check if events already created
        if ($goal->tasks()->count() > 0) {
            return redirect()
                ->route('goals.show', $goal)
                ->with('info', 'Calendar events have already been created for this goal.');
        }

        try {
            $taskData = $goal->ai_plan;
            
            // Schedule calendar events
            $scheduler->scheduleEvents(
                $goal,
                $taskData['tasks'],
                (int) $taskData['duration_days']
            );

            Log::info('Calendar events created after preview confirmation', [
                'goal_id' => $goal->id,
                'tasks_created' => $goal->tasks()->count(),
            ]);

            return redirect()
                ->route('goals.show', $goal)
                ->with('success', 'Calendar events created successfully! Check your Google Calendar.');
        } catch (\Exception $e) {
            Log::error('Failed to create calendar events after confirmation', [
                'goal_id' => $goal->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('goals.preview', $goal)
                ->withErrors(['error' => 'Failed to create calendar events. Please try again.']);
        }
    }

    public function store(
        Request $request,
        GoalTaskLLMService $llmService,
        TaskValidator $validator,
        CalendarEventScheduler $scheduler
    ) {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'target_date' => 'nullable|date',
            'goal_details' => 'nullable|string',
        ]);

        // Check for duplicate goal creation (idempotency guard)
        // Prevent duplicate submissions within 5 seconds
        $recentGoal = Goal::where('user_id', auth()->id())
            ->where('title', $validated['title'])
            ->where('created_at', '>', now()->subSeconds(5))
            ->first();

        if ($recentGoal) {
            Log::warning('Duplicate goal creation attempt prevented', [
                'user_id' => auth()->id(),
                'title' => $validated['title'],
            ]);
            return redirect()
                ->route('goals.show', $recentGoal)
                ->with('info', 'Goal already created! Redirecting to your goal.');
        }

        // Wrap entire creation in transaction for atomicity
        return DB::transaction(function () use ($validated, $llmService, $validator, $scheduler) {
            // Create goal record
            $goal = Goal::create([
                'user_id'     => auth()->id(),
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
                'target_date' => $validated['target_date'] ?? null,
            ]);

            Log::info('Goal created', [
                'goal_id' => $goal->id,
                'user_id' => auth()->id(),
                'title' => $goal->title,
            ]);

            try {
                // Generate tasks using LLM
                $taskData = $this->generateTasksWithRetry(
                    $llmService,
                    $validator,
                    $goal,
                    $validated['goal_details'] ?? null
                );

                Log::info('Tasks generated by LLM', [
                    'goal_id' => $goal->id,
                    'task_count' => count($taskData['tasks'] ?? []),
                ]);

            // Store the plan data in ai_plan for viewing later
            $goal->ai_plan = $taskData;
            $goal->save();

            Log::info('Goal and plan created, redirecting to preview', [
                'goal_id' => $goal->id,
            ]);

            // Redirect to preview page instead of creating events immediately
            return redirect()
                ->route('goals.preview', $goal)
                ->with('info', 'AI has generated your plan! Review it below and confirm to add events to your calendar.');
            } catch (\RuntimeException $e) {
                Log::error('Goal creation failed', [
                    'goal_id' => $goal->id,
                    'user_id' => auth()->id(),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                // Transaction will rollback automatically, but delete goal explicitly for clarity
                $goal->delete();

                return back()
                    ->withInput()
                    ->withErrors([
                        'error' => 'Failed to generate your plan. The AI service may be temporarily unavailable. Please try again in a moment.'
                    ]);
            }
        });
    }

    /**
     * Generate tasks with LLM, validate, and retry once if needed
     *
     * @param GoalTaskLLMService $llmService
     * @param TaskValidator $validator
     * @param Goal $goal
     * @param string|null $goalDetails
     * @return array Validated task data
     * @throws \RuntimeException
     */
    protected function generateTasksWithRetry(
        GoalTaskLLMService $llmService,
        TaskValidator $validator,
        Goal $goal,
        ?string $goalDetails
    ): array {
        $maxRetries = 1;
        $attempt = 0;

        while ($attempt <= $maxRetries) {
            try {
                // Call LLM to generate tasks
                $taskData = $llmService->generateTasks($goal, $goalDetails);

                // Validate the response
                $validation = $validator->validate($taskData);

                if ($validation['isValid']) {
                    return $taskData;
                }

                // Validation failed
                $errors = implode(', ', $validation['errors']);
                Log::warning('LLM task generation validation failed', [
                    'goal_id' => $goal->id,
                    'attempt' => $attempt + 1,
                    'errors' => $validation['errors'],
                ]);

                if ($attempt < $maxRetries) {
                    $attempt++;
                    continue; // Retry
                }

                // Max retries reached
                throw new \RuntimeException(
                    'Failed to generate valid tasks. ' . $errors
                );
            } catch (\RuntimeException $e) {
                if ($attempt < $maxRetries) {
                    $attempt++;
                    continue; // Retry
                }
                throw $e; // Re-throw if max retries reached
            }
        }

        // Should never reach here, but just in case
        throw new \RuntimeException('Failed to generate tasks after retries');
    }

    /**
     * Delete a goal and clean up associated calendar events
     */
    public function destroy(Goal $goal)
    {
        // Ensure user owns this goal
        abort_if($goal->user_id !== auth()->id(), 403);

        DB::transaction(function () use ($goal) {
            // Delete all calendar events first
            $tasks = $goal->tasks()->whereNotNull('calendar_event_id')->get();
            
            foreach ($tasks as $task) {
                if ($task->calendar_event_id) {
                    try {
                        $calendarService = app(\App\Services\Calendar\GoogleCalendarSyncService::class);
                        // Use reflection or create a public method to access removeEvent
                        // For now, we'll delete via Google Calendar API directly
                        $this->deleteCalendarEvent($goal->user_id, $task->calendar_event_id);
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete calendar event during goal deletion', [
                            'task_id' => $task->id,
                            'event_id' => $task->calendar_event_id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

            Log::info('Goal deleted', [
                'goal_id' => $goal->id,
                'user_id' => auth()->id(),
                'tasks_deleted' => $goal->tasks()->count(),
            ]);

            // Delete goal (cascade will delete tasks)
            $goal->delete();
        });

        return redirect()
            ->route('goals.index')
            ->with('success', 'Goal deleted successfully. All calendar events have been removed.');
    }

    /**
     * Pause a goal (stop generating new calendar events)
     */
    public function pause(Goal $goal)
    {
        abort_if($goal->user_id !== auth()->id(), 403);

        $goal->update(['status' => 'paused']);

        Log::info('Goal paused', [
            'goal_id' => $goal->id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Goal paused. No new calendar events will be created.');
    }

    /**
     * Resume a paused goal
     */
    public function resume(Goal $goal)
    {
        abort_if($goal->user_id !== auth()->id(), 403);

        $goal->update(['status' => 'active']);

        Log::info('Goal resumed', [
            'goal_id' => $goal->id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Goal resumed.');
    }

    /**
     * Delete a calendar event from Google Calendar
     */
    protected function deleteCalendarEvent(int $userId, string $eventId): void
    {
        try {
            $token = \App\Models\GoogleToken::where('user_id', $userId)->first();
            if (!$token) {
                return;
            }

            $client = new \Google\Client();
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
            ]);

            if ($client->isAccessTokenExpired() && $client->getRefreshToken()) {
                $newToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $client->setAccessToken($newToken);
            }

            $calendar = new \Google\Service\Calendar($client);
            $calendar->events->delete('primary', $eventId);
        } catch (\Exception $e) {
            Log::error('Failed to delete calendar event', [
                'user_id' => $userId,
                'event_id' => $eventId,
                'error' => $e->getMessage(),
            ]);
            // Don't throw - allow goal deletion to proceed even if calendar cleanup fails
        }
    }
}
