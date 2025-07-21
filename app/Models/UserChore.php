<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserChore extends Model
{
    use HasFactory;

    protected $fillable = [
        'chore_id',
        'user_id',
        'due_date',
        'next_due_date',
        'is_recurring',
        'status',
        'completed_at'
    ];

    protected $casts = [
        'due_date' => 'date',
        'next_due_date' => 'date',
        'is_recurring' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chore()
    {
        return $this->belongsTo(Chore::class);
    }

    /**
     * Calculate the next due date based on frequency
     */
    public function calculateNextDueDate(): ?Carbon
    {
        if (!$this->chore || !$this->is_recurring) {
            return null;
        }

        $baseDate = $this->next_due_date ?: $this->due_date;
        
        return match($this->chore->frequency) {
            'daily' => $baseDate->addDay(),
            'weekly' => $baseDate->addWeek(),
            'monthly' => $baseDate->addMonth(),
            default => null,
        };
    }

    /**
     * Mark as completed and schedule next occurrence if recurring
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        if ($this->is_recurring && $this->chore->frequency !== 'one-time') {
            $nextDueDate = $this->calculateNextDueDate();
            if ($nextDueDate) {
                // Create next occurrence
                self::create([
                    'chore_id' => $this->chore_id,
                    'user_id' => $this->user_id,
                    'due_date' => $nextDueDate,
                    'next_due_date' => $nextDueDate,
                    'is_recurring' => true,
                    'status' => 'pending',
                ]);
            }
        }
    }

    /**
     * Check if chore is overdue
     */
    public function getOverdueAttribute(): bool
    {
        return $this->status === 'pending' && $this->due_date->isPast();
    }
}