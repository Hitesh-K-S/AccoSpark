<?php

namespace App\Services\AI;

use App\Models\Goal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoalTaskLLMService
{
    /**
     * Generate tasks from goal using LLM
     *
     * @param Goal $goal Goal model
     * @param string|null $goalDetails Optional free-form goal details
     * @return array Structured task data
     * @throws \RuntimeException If LLM call fails or returns invalid data
     */
    public function generateTasks(Goal $goal, ?string $goalDetails = null): array
    {
        $systemPrompt = $this->buildSystemPrompt();
        $userPrompt = $this->buildUserPrompt($goal, $goalDetails);

        $response = $this->callLLM($systemPrompt, $userPrompt);

        // Parse JSON response
        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('LLM returned invalid JSON', [
                'goal_id' => $goal->id,
                'response' => $response,
                'json_error' => json_last_error_msg(),
            ]);
            throw new \RuntimeException('LLM returned invalid JSON. Please try again.');
        }

        if (!is_array($data)) {
            Log::error('LLM response is not an array', [
                'goal_id' => $goal->id,
                'response' => $response,
            ]);
            throw new \RuntimeException('LLM returned unexpected format. Please try again.');
        }

        Log::info('LLM task generation successful', [
            'goal_id' => $goal->id,
            'task_count' => count($data['tasks'] ?? []),
        ]);

        return $data;
    }

    /**
     * Build system prompt
     */
    protected function buildSystemPrompt(): string
    {
        return <<<PROMPT
You are a planning assistant. Your job is to convert user goals into structured daily tasks.

Output ONLY valid JSON. No markdown. No explanations. No code blocks. Just pure JSON.

You must return a JSON object with this exact structure:
{
  "goal_title": "string",
  "duration_days": integer,
  "tasks": [
    {
      "title": "string",
      "description": "string",
      "estimated_minutes": integer (10-120),
      "frequency_per_week": integer (1-7)
    }
  ]
}

Rules:
- Maximum 7 tasks
- Each task's estimated_minutes must be between 10 and 120
- Each task's frequency_per_week must be between 1 and 7
- duration_days must be extracted from the goal text or calculated from target_date
- DO NOT specify times or dates - only frequency and duration
- Return ONLY the JSON object, nothing else
PROMPT;
    }

    /**
     * Build user prompt from goal data
     */
    protected function buildUserPrompt(Goal $goal, ?string $goalDetails = null): string
    {
        $parts = [];

        if ($goalDetails) {
            $parts[] = "Goal details: {$goalDetails}";
        }

        $parts[] = "Goal title: {$goal->title}";

        if ($goal->description) {
            $parts[] = "Goal description: {$goal->description}";
        }

        if ($goal->target_date) {
            $parts[] = "Target date: {$goal->target_date->toDateString()}";
            $daysUntilTarget = now()->diffInDays($goal->target_date, false);
            if ($daysUntilTarget > 0) {
                $parts[] = "Days until target: {$daysUntilTarget}";
            }
        }

        return implode("\n", $parts);
    }

    /**
     * Call OpenRouter LLM API
     *
     * @param string $systemPrompt System prompt
     * @param string $userPrompt User prompt
     * @return string Raw response text
     * @throws \RuntimeException If API call fails
     */
    protected function callLLM(string $systemPrompt, string $userPrompt): string
    {
        $apiKey = config('services.openrouter.api_key');
        $baseUrl = config('services.openrouter.base_url');
        $model = config('services.openrouter.model');

        if (!$apiKey) {
            throw new \RuntimeException('OpenRouter API key not configured');
        }

        $url = rtrim($baseUrl, '/') . '/chat/completions';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url'),
            ])->timeout(30)->post($url, [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt,
                    ],
                    [
                        'role' => 'user',
                        'content' => $userPrompt,
                    ],
                ],
                'response_format' => [
                    'type' => 'json_object',
                ],
            ]);

            if (!$response->successful()) {
                Log::error('OpenRouter API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \RuntimeException('Failed to generate tasks. Please try again.');
            }

            $data = $response->json();

            if (!isset($data['choices'][0]['message']['content'])) {
                Log::error('OpenRouter response missing content', [
                    'response' => $data,
                ]);
                throw new \RuntimeException('Invalid response from AI service. Please try again.');
            }

            // Extract content and clean it
            $content = $data['choices'][0]['message']['content'];
            
            // Remove markdown code blocks if present
            $content = preg_replace('/^```json\s*/', '', $content);
            $content = preg_replace('/\s*```$/', '', $content);
            $content = trim($content);

            return $content;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('OpenRouter connection error', [
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Connection to AI service failed. Please try again.');
        } catch (\Exception $e) {
            Log::error('OpenRouter API exception', [
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Failed to generate tasks. Please try again.');
        }
    }
}

