<?php

namespace Modules\Exercise\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Exercise\Models\MuscleCategory;

class MuscleCategoryFactoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = MuscleCategory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $categories = ['Chest', 'Arms', 'Back', 'Legs', 'Shoulders'];

        return [
            'name' => $this->faker->unique()->randomElement($categories),
        ];    }
}

