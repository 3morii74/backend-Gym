<?php

namespace Modules\Exercise\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Exercise\Models\Set;
use Modules\Exercise\Models\UserSystemExercise;

class SetFactoryFactory extends Factory
{
    protected $model = Set::class;

    public function definition()
    {
        return [
            'user_system_exercise_id' => UserSystemExercise::factory(),
            'reps' => $this->faker->numberBetween(5, 15),
            'weight' => $this->faker->randomFloat(2, 20, 200),
        ];
    }
}

