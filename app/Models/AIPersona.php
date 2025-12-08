<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AIPersona extends Model
{
    protected $table = 'ai_personas';

    protected $fillable = [
        'name',
        'slug',
        'tone',
        'prompt_strength',
        'system_prompt',
        'is_active',
    ];

    // Auto-generate slug if missing
    protected static function booted()
    {
        static::creating(function ($persona) {
            if (empty($persona->slug)) {
                $persona->slug = Str::slug($persona->name);
            }
        });
    }
}
