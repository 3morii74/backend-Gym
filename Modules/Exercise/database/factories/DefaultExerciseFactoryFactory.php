<?php

namespace Modules\Exercise\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Exercise\Models\DefaultExercise;

class DefaultExerciseFactoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = DefaultExercise::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $exerciseNames = ['Bench Press', 'Deadlift', 'Squat', 'Shoulder Press', 'Bicep Curl', 'Tricep Extension'];

        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'strength_percentage' => $this->faker->randomFloat(2, 0, 100), // Generate a random percentage between 0 and 100
        ];
    }
}
