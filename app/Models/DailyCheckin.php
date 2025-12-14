<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyCheckin extends Model
{
    protected $fillable = [
    'user_id',
    'date',
    'checkin_type',

    'completed_task_ids',
    'skipped_task_ids',

    'summary_text',
    'energy_level',
    'mood_level',
    ];


    protected $casts = [
    'date' => 'date',
    'completed_task_ids' => 'array',
    'skipped_task_ids' => 'array',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
