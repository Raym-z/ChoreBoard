<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserChore;

class AssignChoreBonuses extends Command
{
    protected $signature = 'chores:assign-bonuses';
    protected $description = 'Assign random bonus multipliers to chores';

    public function handle()
    {
        // Reset all bonuses
        UserChore::where('bonus_multiplier', '>', 1)->update(['bonus_multiplier' => 1]);

        // Assign bonus to a few random pending chores (e.g., 3 per day)
        $chores = UserChore::where('status', 'pending')->inRandomOrder()->limit(3)->get();
        foreach ($chores as $chore) {
            $chore->bonus_multiplier = 2;
            $chore->save();
        }

        $this->info('Random bonuses assigned!');
    }
} 