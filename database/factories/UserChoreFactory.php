<?php

namespace Database\Factories;

use App\Models\UserChore;
use App\Models\User;
use App\Models\Chore;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserChoreFactory extends Factory
{
    protected $model = UserChore::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'completed']);
        $completedAt = $status === 'completed' ? $this->faker->dateTimeBetween('-1 week', 'now') : null;
        return [
            'chore_id' => Chore::inRandomOrder()->first()?->id ?? 1,
            'user_id' => User::inRandomOrder()->first()?->id ?? 1,
            'due_date' => $this->faker->dateTimeBetween('-1 week', '+1 week'),
            'completed_at' => $completedAt,
            'status' => $status,
            'created_at' => $this->faker->dateTimeBetween('-2 week', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}