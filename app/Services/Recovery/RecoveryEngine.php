<?php

namespace App\Services\Recovery;

use App\Models\User;
use App\Models\Task;
use App\Models\DailyCheckin;
use Carbon\Carbon;

class RecoveryEngine
{
    public function evaluate(User $user): array
    {
        $today = Carbon::today();

        // --- Build context ---
        $plannedTasks = Task::where('user_id', $user->id)
            ->whereDate('due_date', $today)
            ->count();

        $completedTasks = Task::where('user_id', $user->id)
            ->whereDate('completed_at', $today)
            ->count();

        $checkinSubmitted = DailyCheckin::where('user_id', $user->id)
            ->where('date', $today)
            ->where('checkin_type', 'submitted')
            ->exists();

        $consecutiveMissedDays = DailyCheckin::where('user_id', $user->id)
            ->where('checkin_type', 'auto_missed')
            ->orderBy('date', 'desc')
            ->take(7)
            ->count();

        $missedLast7 = DailyCheckin::where('user_id', $user->id)
            ->where('checkin_type', 'auto_missed')
            ->where('date', '>=', $today->subDays(7))
            ->count();

        $context = [
            'planned_tasks' => $plannedTasks,
            'completed_tasks' => $completedTasks,
            'checkin_submitted' => $checkinSubmitted,
            'consecutive_missed_days' => $consecutiveMissedDays,
            'missed_days_in_last_7' => $missedLast7,
        ];

        // --- Decide state ---
        $state = app(RecoveryClassifier::class)->classify($context);

        // --- Decide plan ---
        $plan = app(RecoveryPlanner::class)->plan($state);

        return [
            'context' => $context,
            'state' => $state,
            'plan' => $plan,
        ];
    }
}
