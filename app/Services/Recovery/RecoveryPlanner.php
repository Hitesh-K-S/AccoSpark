<?php
namespace App\Services\Recovery;

use App\Enums\RecoveryType;

class RecoveryPlanner
{
    public function plan(RecoveryType $state): array
    {
        return match ($state) {

            RecoveryType::ON_TRACK => [
                'state' => $state,
                'reduce_workload' => false,
                'reschedule_tasks' => false,
                'freeze_new_tasks' => false,
                'workload_multiplier' => 100,
                'internal_reason' => 'User on track'
            ],

            RecoveryType::SLIPPED => [
                'state' => $state,
                'reduce_workload' => false,
                'reschedule_tasks' => true,
                'freeze_new_tasks' => false,
                'workload_multiplier' => 90,
                'internal_reason' => 'Minor slip, normal recovery'
            ],

            RecoveryType::OVERLOADED => [
                'state' => $state,
                'reduce_workload' => true,
                'reschedule_tasks' => true,
                'freeze_new_tasks' => false,
                'workload_multiplier' => 70,
                'internal_reason' => 'User overloaded'
            ],

            RecoveryType::AVOIDING => [
                'state' => $state,
                'reduce_workload' => true,
                'reschedule_tasks' => false,
                'freeze_new_tasks' => true,
                'workload_multiplier' => 50,
                'internal_reason' => 'User disengaging'
            ],

            RecoveryType::BURNOUT => [
                'state' => $state,
                'reduce_workload' => true,
                'reschedule_tasks' => true,
                'freeze_new_tasks' => true,
                'workload_multiplier' => 40,
                'internal_reason' => 'Burnout detected'
            ],
        };
    }
}
