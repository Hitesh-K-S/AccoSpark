<?php

namespace App\Services\AI;

use App\Models\Goal;
use Illuminate\Support\Facades\Log;

class GoalAIPlannerService
{
    public function generateRoadmap(Goal $goal, array $context = []): array
    {
        $schema = $this->roadmapSchema();

        $systemPrompt = <<<PROMPT
You are an expert productivity and behavior-design AI.

Your job is to design a realistic, human-centered roadmap to achieve a goal.
You do NOT generate daily tasks.
You ONLY produce a roadmap plan in strict JSON format.

The user may miss days.
The plan must be flexible and forgiving.
Never include explanations or markdown.
Return ONLY valid JSON.
PROMPT;

        $userPrompt = <<<PROMPT
Goal title: {$goal->title}
Goal description: {$goal->description}
Target date: {$goal->target_date?->toDateString()}

Constraints:
- No session timers
- Tasks go to Google Calendar
- User checks in once per day max
- Plan must survive missed days

Roadmap JSON schema:
{$schema}
PROMPT;

        // 🔵 Call AI (mock for now)
        $response = $this->callLLM($systemPrompt, $userPrompt);

        // 🔎 Debug visibility (remove later if you want)
        Log::info('AI roadmap raw response', [
            'goal_id' => $goal->id,
            'response' => $response,
        ]);

        $json = json_decode($response, true);

        if (!is_array($json)) {
            throw new \RuntimeException('AI returned invalid JSON roadmap');
        }

        return $json;
    }

    /**
     * Temporary mock LLM response
     * Replace with OpenAI / Anthropic / OpenRouter later
     */
    protected function callLLM(string $system, string $user): string
    {
        return json_encode([
            "goal_summary" => "User-defined goal roadmap",
            "strategy" => "Progressive execution with recovery buffers",
            "confidence_level" => "medium",
            "time_constraints" => [
                "available_minutes_per_day" => 60,
                "days_per_week" => 5
            ],
            "phases" => [
                [
                    "name" => "Foundation",
                    "start_week" => 1,
                    "end_week" => 4,
                    "focus" => "Habit and consistency",
                    "expected_load" => "light"
                ]
            ],
            "task_generation_rules" => [
                "max_tasks_per_day" => 2,
                "min_task_duration_minutes" => 15,
                "max_task_duration_minutes" => 60,
                "buffer_days_per_week" => 1
            ],
            "failure_tolerance" => [
                "missed_days_before_adjustment" => 2,
                "auto_reduce_on_miss" => true
            ]
        ], JSON_PRETTY_PRINT);
    }

    protected function roadmapSchema(): string
    {
        return json_encode([
            'goal_summary' => 'string',
            'strategy' => 'string',
            'confidence_level' => 'low|medium|high',
            'time_constraints' => [
                'available_minutes_per_day' => 'integer',
                'days_per_week' => 'integer'
            ],
            'phases' => [
                [
                    'name' => 'string',
                    'start_week' => 'integer',
                    'end_week' => 'integer',
                    'focus' => 'string',
                    'expected_load' => 'light|moderate|heavy'
                ]
            ],
            'task_generation_rules' => [
                'max_tasks_per_day' => 'integer',
                'min_task_duration_minutes' => 'integer',
                'max_task_duration_minutes' => 'integer',
                'buffer_days_per_week' => 'integer'
            ],
            'failure_tolerance' => [
                'missed_days_before_adjustment' => 'integer',
                'auto_reduce_on_miss' => 'boolean'
            ]
        ], JSON_PRETTY_PRINT);
    }
}
