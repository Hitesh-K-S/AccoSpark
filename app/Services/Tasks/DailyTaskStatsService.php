<?php

namespace App\Services\Tasks;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class DailyTaskStatsService
{
    public function getForDate(User $user, Carbon $date): array
    {
        $planned = Task::where('user_id', $user->id)
            ->whereDate('due_date', $date)
            ->count();

        $completed = Task::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->whereDate('completed_at', $date)
            ->count();

        return [
            'planned'   => $planned,
            'completed' => $completed,
            'missed'    => max($planned - $completed, 0),
        ];
    }
}
