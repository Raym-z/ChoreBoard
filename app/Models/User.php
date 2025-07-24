<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userChores()
    {
        return $this->hasMany(UserChore::class);
    }

    public function households()
    {
        return $this->belongsToMany(Household::class)->withPivot('role');
    }

    public function createdChores()
    {
        return $this->hasMany(Chore::class, 'created_by');
    }

    public function getRankAttribute()
    {
        $xp = $this->xp;
        if ($xp < 100) return 'Apprentice Cleaner';
        if ($xp < 500) return 'Chore Pro';
        if ($xp < 1000) return 'Chore Champion';
        return 'Chore Master';
    }

    public function checkLevelUp()
    {
        $xpThresholds = [1 => 0, 2 => 100, 3 => 500, 4 => 1000];
        $newLevel = $this->level;
        foreach ($xpThresholds as $level => $xp) {
            if ($this->xp >= $xp) {
                $newLevel = $level;
            }
        }
        if ($newLevel > $this->level) {
            $this->level = $newLevel;
            $this->save();
            // Set a session flash message for level up
            session()->flash('level_up', 'You reached Level ' . $this->level . '!');
        }
    }

    public function updateStreaks()
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        $thisWeek = now()->startOfWeek()->toDateString();
        $lastCompleted = $this->last_completed_at;

        // Daily streak
        if ($lastCompleted === $yesterday) {
            $this->daily_streak += 1;
        } elseif ($lastCompleted !== $today) {
            $this->daily_streak = 1;
        }

        // Weekly streak
        $lastCompletedWeek = $lastCompleted ? \Carbon\Carbon::parse($lastCompleted)->startOfWeek()->toDateString() : null;
        if ($lastCompletedWeek === $thisWeek) {
            $this->weekly_streak += 1;
        } else {
            $this->weekly_streak = 1;
        }

        $this->last_completed_at = $today;
        $this->save();
    }

    public function getStreakLabelAttribute()
    {
        return $this->daily_streak >= 3 ? '🔥 ' . $this->daily_streak . ' days in a row!' : $this->daily_streak . ' day streak';
    }

    public function getAvatarUrlAttribute()
    {
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?s=64&d=identicon";
    }

    public function checkDailyChallenge()
    {
        // Example: complete 3 chores today
        $completedToday = $this->userChores()->whereDate('completed_at', now()->toDateString())->count();
        if (!$this->daily_challenge_completed && $completedToday >= 3) {
            $this->daily_challenge_completed = true;
            $this->challenge_xp_bonus += 25; // bonus XP
            $this->xp += 25;
            $this->save();
            session()->flash('level_up', 'Daily Challenge Complete! +25 XP');
        }
    }

    public function checkWeeklyChallenge()
    {
        // Example: complete 10 chores this week
        $completedThisWeek = $this->userChores()->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        if (!$this->weekly_challenge_completed && $completedThisWeek >= 10) {
            $this->weekly_challenge_completed = true;
            $this->challenge_xp_bonus += 100; // bonus XP
            $this->xp += 100;
            $this->save();
            session()->flash('level_up', 'Weekly Challenge Complete! +100 XP');
        }
    }

    public function getDailyChallengeProgressAttribute()
    {
        $completedToday = $this->userChores()->whereDate('completed_at', now()->toDateString())->count();
        return min($completedToday, 3) . '/3';
    }

    public function getWeeklyChallengeProgressAttribute()
    {
        $completedThisWeek = $this->userChores()->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        return min($completedThisWeek, 10) . '/10';
    }
}