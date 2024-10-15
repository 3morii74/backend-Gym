<?php

namespace Modules\Exercise\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Exercise\Models\CustomizedExercise;

class CustomizedExerciseFactoryFactory extends Factory
{
    protected $model = CustomizedExercise::class;

    public function definition()
    {
        return [
           // 'user_id' => User::factory(),
            'name' => $this->faker->unique()->word . ' Exercise',
            'description' => $this->faker->sentence(),
            'strength_percentage' => $this->faker->randomFloat(2, 0, 100), // Generate a random percentage between 0 and 100
        ];
    }
}

