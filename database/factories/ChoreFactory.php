<?php

namespace Database\Factories;

use App\Models\Chore;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChoreFactory extends Factory
{
    protected $model = Chore::class;

    public function definition(): array
    {
        $frequencies = ['one-time', 'daily', 'weekly'];
        $priorities = ['low', 'medium', 'high'];
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(8),
            'points' => $this->faker->numberBetween(1, 10),
            'frequency' => $this->faker->randomElement($frequencies),
            'priority' => $this->faker->randomElement($priorities),
            'created_by' => User::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}