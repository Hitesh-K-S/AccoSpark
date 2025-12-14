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
        'summary_text',
        'energy_level',
        'mood_level',
        'self_reported_done',
    ];

    protected $casts = [
        'date' => 'date',
        'self_reported_done' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
