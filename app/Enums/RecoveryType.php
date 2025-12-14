<?php
namespace App\Enums;

enum RecoveryType: string
{
    case ON_TRACK = 'on_track';           // doing fine
    case SLIPPED = 'slipped';              // missed a bit
    case OVERLOADED = 'overloaded';        // too much workload
    case AVOIDING = 'avoiding';            // avoidance / ghosting
    case BURNOUT = 'burnout';              // repeated failures
}
