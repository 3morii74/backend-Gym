<?php

namespace Modules\Exercise\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Exercise\Models\ExerciseSystemCustomized;

class ExerciseSystemCustomizedFactoryFactory extends Factory
{
    protected $model = ExerciseSystemCustomized::class;

    public function definition()
    {
        return [
           // 'user_id' => User::factory(),
            'name' => $this->faker->unique()->word . ' System',
        ];
    }
}

