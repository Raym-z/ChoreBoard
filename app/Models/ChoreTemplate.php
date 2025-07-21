<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoreTemplate extends Model
{
    use HasFactory;

    // If you want to relate templates to chores:
    // public function chores()
    // {
    //     return $this->hasMany(Chore::class);
    // }
}
