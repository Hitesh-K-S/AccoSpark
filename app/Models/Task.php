<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'goal_id','user_id','title','description','due_date',
        'status','type','google_event_id','ai_generated'
    ];
}
