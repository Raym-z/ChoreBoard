<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chore extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'points',
        'frequency',
        'priority',
        'created_by',
        'household_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function userChores()
    {
        return $this->hasMany(UserChore::class);
    }

    public function household()
    {
        return $this->belongsTo(Household::class);
    }
}