<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_checkins', function (Blueprint $table) {

            // Task-level truth (AI and recovery logic need this)
            $table->json('completed_task_ids')->nullable()->after('checkin_type');
            $table->json('skipped_task_ids')->nullable()->after('completed_task_ids');

            // Optional: deprecate coarse flag
            if (Schema::hasColumn('daily_checkins', 'self_reported_done')) {
                $table->dropColumn('self_reported_done');
            }
        });
    }

    public function down(): void
    {
        Schema::table('daily_checkins', function (Blueprint $table) {
            $table->dropColumn(['completed_task_ids', 'skipped_task_ids']);
        });
    }
};
