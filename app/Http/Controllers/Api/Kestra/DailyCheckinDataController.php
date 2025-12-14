<?php

namespace App\Http\Controllers\Api\Kestra;

use App\Http\Controllers\Controller;
use App\Models\DailyCheckin;
use App\Models\Task;
use App\Services\Recovery\RecoveryClassifier;
use App\Services\Recovery\RecoveryPlanner;
use App\Services\Tasks\TaskAdjustmentService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DailyCheckinDataController extends Controller
{
    public function context(int $userId)
    {
        $today = Carbon::today();

        return [
            'checkin_submitted' => DailyCheckin::where('user_id', $userId)
                ->where('date', $today)
                ->exists(),

            'planned_tasks' => Task::where('user_id', $userId)
                ->whereDate('due_date', $today)
                ->count(),

            'completed_tasks' => Task::where('user_id', $userId)
                ->whereDate('due_date', $today)
                ->where('status', 'completed')
                ->count(),

            'consecutive_missed_days' => DailyCheckin::where('user_id', $userId)
                ->where('checkin_type', 'auto_missed')
                ->where('date', '>=', now()->subDays(7))
                ->count(),

            'missed_days_in_last_7' => DailyCheckin::where('user_id', $userId)
                ->where('checkin_type', 'auto_missed')
                ->where('date', '>=', now()->subDays(7))
                ->count(),
        ];
    }

    public function applyRecovery(
        int $userId,
        RecoveryClassifier $classifier,
        RecoveryPlanner $planner,
        TaskAdjustmentService $taskAdjuster
    ) {
        $context = $this->context($userId);
        $state = $classifier->classify($context);
        $plan = $planner->plan($state);

        $taskAdjuster->apply($userId, $plan);

        return [
            'status' => 'ok',
            'recovery_state' => $state->value,
            'plan' => $plan,
        ];
    }
}
