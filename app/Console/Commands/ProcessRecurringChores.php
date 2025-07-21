<?php

namespace App\Console\Commands;

use App\Models\UserChore;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ProcessRecurringChores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chores:process-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process recurring chores and create new instances';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing recurring chores...');

        // Get completed recurring chores that need new instances
        $completedRecurringChores = UserChore::where('is_recurring', true)
            ->where('status', 'completed')
            ->whereHas('chore', function($query) {
                $query->where('frequency', '!=', 'one-time');
            })
            ->get();

        $created = 0;

        foreach ($completedRecurringChores as $userChore) {
            // Check if next instance already exists
            $nextInstanceExists = UserChore::where('chore_id', $userChore->chore_id)
                ->where('user_id', $userChore->user_id)
                ->where('status', 'pending')
                ->where('due_date', '>', $userChore->due_date)
                ->exists();

            if (!$nextInstanceExists) {
                $nextDueDate = $userChore->calculateNextDueDate();
                
                if ($nextDueDate && $nextDueDate->isFuture()) {
                    UserChore::create([
                        'chore_id' => $userChore->chore_id,
                        'user_id' => $userChore->user_id,
                        'due_date' => $nextDueDate,
                        'next_due_date' => $nextDueDate,
                        'is_recurring' => true,
                        'status' => 'pending',
                    ]);
                    
                    $created++;
                    $this->line("Created new instance for chore: {$userChore->chore->name} (Due: {$nextDueDate->format('Y-m-d')})");
                }
            }
        }

        $this->info("Processed {$completedRecurringChores->count()} recurring chores. Created {$created} new instances.");
        
        return Command::SUCCESS;
    }
}