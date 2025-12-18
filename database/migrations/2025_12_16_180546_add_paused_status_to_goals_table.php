<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL doesn't support direct enum modification, use DB raw query
        DB::statement("ALTER TABLE goals MODIFY COLUMN status ENUM('active', 'paused', 'completed', 'abandoned') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE goals MODIFY COLUMN status ENUM('active', 'completed', 'abandoned') DEFAULT 'active'");
    }
};
