<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goals', function (Blueprint $table) {

            // Make target_date meaningful for AI planning
            $table->date('target_date')->nullable(false)->change();

            // AI constraint: daily capacity
            $table->integer('available_minutes_per_day')
                  ->default(120)
                  ->after('target_date');

            // AI-generated roadmap / phases / assumptions
            $table->json('ai_plan')
                  ->nullable()
                  ->after('available_minutes_per_day');
        });
    }

    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->dropColumn([
                'available_minutes_per_day',
                'ai_plan',
            ]);

            // revert if needed
            $table->date('target_date')->nullable()->change();
        });
    }
};
