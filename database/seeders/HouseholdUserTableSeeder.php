<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HouseholdUserTableSeeder extends Seeder
{
    public function run(): void
    {
        // Get user and household IDs
        $aliceId = DB::table('users')->where('email', 'alice@example.com')->value('id');
        $bobId = DB::table('users')->where('email', 'bob@example.com')->value('id');
        $carolId = DB::table('users')->where('email', 'carol@example.com')->value('id');
        $smithFamilyId = DB::table('households')->where('name', 'The Smith Family')->value('id');
        $room101Id = DB::table('households')->where('name', 'Room 101')->value('id');

        DB::table('household_user')->insert([
            [
                'user_id' => $aliceId,
                'household_id' => $smithFamilyId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $bobId,
                'household_id' => $smithFamilyId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $carolId,
                'household_id' => $room101Id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}