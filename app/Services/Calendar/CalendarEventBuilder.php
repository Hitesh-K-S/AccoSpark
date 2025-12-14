<?php

namespace App\Services\Calendar;

use App\Models\Task;
use Carbon\Carbon;

class CalendarEventBuilder
{
    public function build(Task $task, int $duration, string $personaTone): array
    {
        $start = Carbon::parse($task->due_date)->setTime(10, 0);
        $end = (clone $start)->addMinutes($duration);

        return [
            'summary' => $this->title($task, $duration, $personaTone),
            'description' => $this->description($task),
            'start' => [
                'dateTime' => $start->toRfc3339String(),
                'timeZone' => config('app.timezone'),
            ],
            'end' => [
                'dateTime' => $end->toRfc3339String(),
                'timeZone' => config('app.timezone'),
            ],
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'popup', 'minutes' => 10],
                    ['method' => 'popup', 'minutes' => 0],
                ],
            ],
        ];
    }

    protected function title(Task $task, int $duration, string $tone): string
    {
        return match ($tone) {
            'friendly' => "🌱 {$duration} min focus: {$task->title}",
            'strict'   => "🎯 {$task->title} ({$duration} min)",
            'chaotic'  => "🔥 {$task->title} OR ELSE ({$duration} min)",
            default    => "{$task->title} ({$duration} min)",
        };
    }

    protected function description(Task $task): string
    {
        return trim("
Goal related task

Why it matters:
{$task->description}

Just show up for this block.
        ");
    }
}
