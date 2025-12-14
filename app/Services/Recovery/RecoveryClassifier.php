<?php

namespace App\Services\Recovery;

use App\Enums\RecoveryType;

class RecoveryClassifier
{
    public function classify(array $context): RecoveryType
    {
        if ($context['consecutive_missed_days'] >= 3) {
            return RecoveryType::BURNOUT;
        }

        if (
            !$context['checkin_submitted'] &&
            $context['consecutive_missed_days'] >= 1 &&
            $context['missed_days_in_last_7'] <= 2
        ) {
            return RecoveryType::AVOIDING;
        }

        if (
            $context['checkin_submitted'] &&
            $context['planned_tasks'] > 0 &&
            ($context['completed_tasks'] / $context['planned_tasks']) < 0.4 &&
            $context['missed_days_in_last_7'] <= 1
        ) {
            return RecoveryType::OVERLOADED;
        }

        if (
            $context['checkin_submitted'] &&
            ($context['completed_tasks'] / max(1, $context['planned_tasks'])) >= 0.4
        ) {
            return RecoveryType::SLIPPED;
        }

        return RecoveryType::ON_TRACK;
    }
}
