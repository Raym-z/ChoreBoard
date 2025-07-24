<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Carbon;

class AssignChallenges extends Command
{
    protected $signature = 'challenges:assign';
    protected $description = 'Assign daily and weekly challenges to all users';

    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->daily_challenge_completed = false;
            $user->weekly_challenge_completed = false;
            $user->challenge_xp_bonus = 0;
            $user->save();
        }
        $this->info('Daily and weekly challenges assigned to all users.');
    }
} 