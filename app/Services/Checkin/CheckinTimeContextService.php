<?php

namespace App\Services\Checkin;

use App\Models\Task;
use Carbon\Carbon;

class CheckinTimeContextService
{
    public function build(int $userId, Carbon $date): array
    {
        $tasks = Task::where('user_id', $userId)
            ->whereDate('due_date', $date)
            ->get();

        $planned = $tasks->sum('estimated_minutes');

        $completed = $tasks
            ->whereNotNull('completed_at')
            ->sum('estimated_minutes');

        $ratio = $planned > 0
            ? intval(($completed / $planned) * 100)
            : 0;

        return [
            'planned_minutes' => $planned,
            'completed_minutes' => $completed,
            'completion_ratio' => $ratio,
            'task_count' => $tasks->count(),
        ];
    }
}
