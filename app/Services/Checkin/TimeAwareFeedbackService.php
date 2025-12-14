<?php

namespace App\Services\Checkin;

class TimeAwareFeedbackService
{
    public function feedback(array $data, string $tone): string
    {
        $planned = $data['planned_minutes'];
        $completed = $data['completed_minutes'];
        $ratio = $data['completion_ratio'];

        return match (true) {

            $planned === 0 =>
                "No tasks planned today. That’s okay — tomorrow we plan intentionally.",

            $ratio >= 80 =>
                $this->winMessage($tone, $completed),

            $ratio >= 40 =>
                $this->partialMessage($tone, $completed, $planned),

            default =>
                $this->missedMessage($tone, $planned),
        };
    }

    protected function winMessage(string $tone, int $minutes): string
    {
        return match ($tone) {
            'strict' => "You executed {$minutes} focused minutes. Discipline held.",
            'chaotic' => "🔥 {$minutes} MINUTES DONE. ABSOLUTE MADNESS.",
            default => "Nice work — {$minutes} focused minutes is solid effort.",
        };
    }

    protected function partialMessage(string $tone, int $done, int $planned): string
    {
        return match ($tone) {
            'strict' => "Partial execution ({$done}/{$planned} min). We adjust tomorrow.",
            'chaotic' => "😤 You showed up… kinda. {$done} minutes still count.",
            default => "You made progress. Tomorrow will be lighter.",
        };
    }

    protected function missedMessage(string $tone, int $planned): string
    {
        return match ($tone) {
            'strict' => "No execution today. Recovery protocol engaged.",
            'chaotic' => "😬 {$planned} minutes planned. Zero happened. Resetting.",
            default => "Today didn’t land. We’ll recalibrate — no stress.",
        };
    }
}
