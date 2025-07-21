<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoreLog extends Model
{
    use HasFactory;

    public function userChore()
    {
        return $this->belongsTo(UserChore::class);
    }
}
