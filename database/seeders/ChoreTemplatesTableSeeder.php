<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChoreTemplatesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('chore_templates')->insert([
            [
                'name' => 'Take out trash',
                'description' => 'Empty all household trash bins and take trash to the curb.',
                'points' => 5,
                'frequency' => 'weekly',
                'priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vacuum living room',
                'description' => 'Vacuum the entire living room area.',
                'points' => 7,
                'frequency' => 'weekly',
                'priority' => 'high',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wash dishes',
                'description' => 'Wash all dirty dishes and clean the sink.',
                'points' => 3,
                'frequency' => 'daily',
                'priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sweep kitchen floor',
                'description' => 'Sweep and tidy the kitchen floor.',
                'points' => 4,
                'frequency' => 'daily',
                'priority' => 'low',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Clean bathroom',
                'description' => 'Clean sink, toilet, and shower in the bathroom.',
                'points' => 10,
                'frequency' => 'weekly',
                'priority' => 'high',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}