<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DailyCheckin extends Model
{
    protected $casts = [
        'tasks_completed' => 'array',
        'ai_feedback' => 'array',
        'date' => 'date',
    ];
    protected $fillable = ['user_id','date','tasks_completed','offline_notes','ai_feedback'];
}
