<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HouseholdsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('households')->insert([
            [
                'name' => 'The Smith Family',
                'invite_code' => Str::random(8),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Room 101',
                'invite_code' => Str::random(8),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
