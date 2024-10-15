<?php

namespace Modules\Exercise\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Exercise\Models\ExerciseSystemDefault;

class ExerciseSystemDefaultFactoryFactory extends Factory
{
    protected $model = ExerciseSystemDefault::class;

    public function definition()
    {
        $systems = ['Push', 'Pull', 'Leg', 'Upper', 'Lower', 'Arnold Split'];

        return [
            'name' => $this->faker->unique()->randomElement($systems),
        ];
    }
}

