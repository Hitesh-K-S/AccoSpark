<?php

namespace App\Services\Tasks;

use App\Models\Task;
use Carbon\Carbon;

class TaskAdjustmentService
{
    public function apply(int $userId, array $plan): void
    {
        // Reschedule overdue tasks
        if ($plan['reschedule_tasks']) {
            Task::where('user_id', $userId)
                ->where('status', 'pending')
                ->whereDate('due_date', '<', Carbon::today())
                ->update([
                    'due_date' => Carbon::today()->addDay(),
                ]);
        }

        // Reduce workload for upcoming tasks
        if ($plan['reduce_workload']) {
            $this->reduceFutureLoad($userId, $plan['workload_multiplier']);
        }

        // Freeze system-generated tasks
        if ($plan['freeze_new_tasks']) {
            $this->freezeSystemTasks($userId);
        }
    }

    protected function reduceFutureLoad(int $userId, int $multiplier): void
    {
        Task::where('user_id', $userId)
            ->where('status', 'pending')
            ->whereDate('due_date', '>', Carbon::today())
            ->each(function ($task) use ($multiplier) {
                if (!isset($task->estimated_minutes)) {
                    return;
                }

                $task->estimated_minutes = max(
                    5,
                    intval($task->estimated_minutes * ($multiplier / 100))
                );
                $task->save();
            });
    }

    protected function freezeSystemTasks(int $userId): void
    {
        Task::where('user_id', $userId)
            ->where('is_system_generated', true)
            ->where('status', 'pending')
            ->update([
                'status' => 'paused',
            ]);
    }
}
