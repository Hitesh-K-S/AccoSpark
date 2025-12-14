<?php

namespace App\Services\Persona;

use App\Models\AIPersona;
use App\Models\Task;

class PersonaWordingService
{
    public function calendarTitle(Task $task, AIPersona $persona): string
    {
        return match ($persona->tone) {
            'friendly' => "🌱 {$task->title} — just show up",
            'strict'   => "🎯 {$task->title} — no excuses",
            'chaotic'  => "🔥 {$task->title} (do it. now.)",
            default    => $task->title,
        };
    }

    public function calendarDescription(Task $task, AIPersona $persona): string
    {
        return match ($persona->tone) {
            'friendly' => $this->friendlyDesc($task),
            'strict'   => $this->strictDesc($task),
            'chaotic'  => $this->chaoticDesc($task),
            default    => $task->title,
        };
    }

    protected function friendlyDesc(Task $task): string
    {
        return <<<TEXT
Small step. Real progress.

Focus for {$task->estimated_minutes} minutes.
No pressure. Just consistency.

You’ve got this 💛
TEXT;
    }

    protected function strictDesc(Task $task): string
    {
        return <<<TEXT
This was planned for a reason.

{$task->estimated_minutes} minutes.
Phone away. No multitasking.

Finish it. Then rest.
TEXT;
    }

    protected function chaoticDesc(Task $task): string
    {
        return <<<TEXT
Okay listen.

You said you wanted this.
Future-you is watching.
Do it now before I reschedule your life.

{$task->estimated_minutes} minutes. GO.
TEXT;
    }
}
