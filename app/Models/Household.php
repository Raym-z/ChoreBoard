<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class);
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
