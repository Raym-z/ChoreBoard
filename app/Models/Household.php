<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected static function booted()
    {
        static::creating(function ($household) {
            if (empty($household->invite_code)) {
                $household->invite_code = strtoupper(
                    substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8)
                );
            }
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    // If chores are linked to households in the future:
    // public function chores()
    // {
    //     return $this->hasMany(Chore::class);
    // }
}