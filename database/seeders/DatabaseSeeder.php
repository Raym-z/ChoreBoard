<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Removed manual 'Test User' insert to avoid duplicate email error. All demo users are now seeded via UsersTableSeeder.

        $this->call([
            UsersTableSeeder::class,
            HouseholdsTableSeeder::class,
            HouseholdUserTableSeeder::class,
            ChoreTemplatesTableSeeder::class,
        ]);

        // Seed more sample chores and user chores for richer demo data
        \App\Models\Chore::factory(30)->create();
        \App\Models\UserChore::factory(100)->create();
    }
}