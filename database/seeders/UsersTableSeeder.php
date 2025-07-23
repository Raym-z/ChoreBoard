<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Alice Admin',
                'email' => 'alice@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bob Member',
                'email' => 'bob@example.com',
                'password' => bcrypt('password'),
                'role' => 'member',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Carol Member',
                'email' => 'carol@example.com',
                'password' => bcrypt('password'),
                'role' => 'member',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Raymond W T',
                'email' => 'stepahead678@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'member',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}