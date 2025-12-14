<?php

namespace App\Services\Tasks;

use App\Models\Task;
use Carbon\Carbon;

class TaskTimePlannerService
{
    protected int $dailyLimit = 180; // minutes

    public function planForDate(int $userId, Carbon $date): array
    {
        $tasks = Task::where('user_id', $userId)
            ->whereDate('due_date', $date)
            ->where('status', 'pending')
            ->orderBy('estimated_minutes')
            ->get();

        $planned = [];
        $usedMinutes = 0;

        foreach ($tasks as $task) {
            if ($usedMinutes >= $this->dailyLimit) {
                break; //  stop adding more
            }

            $duration = $this->mapDuration($task->estimated_minutes);

            if (($usedMinutes + $duration) > $this->dailyLimit) {
                continue; // skip task, too heavy for today
            }

            $planned[] = [
                'task' => $task,
                'duration' => $duration,
            ];

            $usedMinutes += $duration;
        }

        return $planned;
    }

    protected function mapDuration(int $minutes): int
    {
        return match (true) {
            $minutes <= 15 => 15,
            $minutes <= 30 => 30,
            $minutes <= 60 => 60,
            default => 90,
        };
    }
}
