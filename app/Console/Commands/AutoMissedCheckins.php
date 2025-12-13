<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\DailyCheckin;
use Carbon\Carbon;

class AutoMissedCheckins extends Command
{
    protected $signature = 'checkins:auto-miss';

    protected $description = 'Create auto-missed daily check-ins for users who did not submit one';

    public function handle()
    {
        $today = Carbon::today();

        $users = User::where('banned_at', null)->get();

        foreach ($users as $user) {
            $exists = DailyCheckin::where('user_id', $user->id)
                ->where('date', $today)
                ->exists();

            if (!$exists) {
                DailyCheckin::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'checkin_type' => 'auto_missed',
                ]);

                $this->info("Auto-missed check-in created for user {$user->id}");
            }
        }

        return Command::SUCCESS;
    }
}
