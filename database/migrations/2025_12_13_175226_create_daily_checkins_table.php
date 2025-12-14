<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('daily_checkins', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('date'); // YYYY-MM-DD

            $table->enum('checkin_type', ['submitted', 'auto_missed'])
                ->default('submitted');

            $table->text('summary_text')->nullable();

            $table->tinyInteger('energy_level')->nullable(); // 1–5
            $table->tinyInteger('mood_level')->nullable();   // 1–5

            $table->boolean('self_reported_done')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'date']); // 🔒 one per day
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('daily_checkins');
    }
};
