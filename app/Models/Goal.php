<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'target_date',
        'status',
        'ai_plan',
    ];

    protected $casts = [
        'ai_plan' => 'array',
        'target_date' => 'date',
    ];

    /**
     * Get the tasks for the goal
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the user that owns the goal
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
