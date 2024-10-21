<?php

namespace Modules\Exercise\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Exercise\Models\ExerciseSystemDefault;
use Modules\Exercise\Models\UserSystemExercise;

class UserSystemExerciseFactoryFactory extends Factory
{
    protected $model = UserSystemExercise::class;

    public function definition()
    {
        return [
           // 'user_id' => User::factory(),
            'exercise_system_default_id' => ExerciseSystemDefault::factory(),
            // Or 'exercise_system_customized_id' => ExerciseSystemCustomized::factory(),
            'exercise_id' => null, // Assign based on exercise type
        ];
    }
}

