<?php

namespace Modules\Exercise\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Exercise\Models\Muscle;
use Modules\Exercise\Models\MuscleCategory;

class MuscleFactoryFactory extends Factory
{
    protected $model = Muscle::class;

    public function definition()
    {
        $muscleNames = [
            'Chest' => ['Pectoralis Major', 'Pectoralis Minor'],
            'Arms' => ['Biceps Brachii', 'Triceps Brachii'],
            'Back' => ['Latissimus Dorsi', 'Trapezius'],
            'Legs' => ['Quadriceps', 'Hamstrings'],
            'Shoulders' => ['Deltoid', 'Rotator Cuff']
        ];

        return [
            'muscle_category_id' => MuscleCategory::factory(),
            'name' => $this->faker->unique()->randomElement(array_merge(...array_values($muscleNames))),
        ];
    }
}

