<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'goal_id',
        'user_id',
        'title',
        'description',
        'due_date',
        'status',
        'type',
        'google_event_id',
        'calendar_event_id',
        'calendar_synced',
        'ai_generated',
        'completed_at',
        'estimated_minutes',
        'frequency_per_week',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function complete(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Task completed successfully.');
    }

    /**
     * Get the goal that owns the task
     */
    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}