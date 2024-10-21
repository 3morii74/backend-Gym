<?php

namespace Modules\Exercise\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Exercise\Models\DefaultExercise;
use Modules\Exercise\Models\ExerciseSystemDefault;
use Modules\Exercise\Models\Muscle;
use Modules\Exercise\Models\MuscleCategory;

class ExerciseDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Muscle Categories and Muscles
        $categories = [
            'Chest' => ['Pectoralis Major', 'Pectoralis Minor'],
            'Arms' => ['Biceps Brachii', 'Triceps Brachii'],
            'Back' => ['Latissimus Dorsi', 'Trapezius'],
            'Legs' => ['Quadriceps', 'Hamstrings'],
            'Shoulders' => ['Deltoid', 'Rotator Cuff']
        ];

        foreach ($categories as $categoryName => $muscles) {
            $category = MuscleCategory::create(['name' => $categoryName]);
            foreach ($muscles as $muscleName) {
                Muscle::create([
                    'name' => $muscleName,
                    'muscle_category_id' => $category->id,
                ]);
            }
        }

        // Create Default Exercises
        $defaultExercises = [
            'Bench Press',
            'Deadlift',
            'Squat',
            'Shoulder Press',
            'Bicep Curl',
            'Tricep Extension'
        ];

        foreach ($defaultExercises as $exerciseName) {
            DefaultExercise::create([
                'name' => $exerciseName,
                'description' => 'Description for ' . $exerciseName,
            ]);
        }

        // Associate Muscles with Default Exercises (many-to-many)
        $benchPress = DefaultExercise::where('name', 'Bench Press')->first();
        $benchPress->muscles()->attach(Muscle::whereIn('name', ['Pectoralis Major', 'Triceps Brachii'])->pluck('id')->toArray());

        // Similarly, attach muscles for other exercises...

        // Create Users
        // User::factory(10)->create()->each(function ($user) {
        //     // Create Customized Exercises for each user
        //     CustomizedExercise::factory(3)->create(['user_id' => $user->id])->each(function ($customExercise) use ($user) {
        //         // Attach muscles to customized exercises
        //         $customExercise->muscles()->attach(Muscle::inRandomOrder()->take(2)->pluck('id')->toArray());
        //     });

        //     // Create Exercise System Defaults
        //     $systems = ExerciseSystemDefault::all();
        //     foreach ($systems as $system) {
        //         // Attach default exercises to systems
        //         $system->defaultExercises()->attach(DefaultExercise::inRandomOrder()->take(3)->pluck('id')->toArray());
        //         // Attach customized exercises to systems
        //         $system->customizedExercises()->attach(CustomizedExercise::inRandomOrder()->take(2)->pluck('id')->toArray());
        //     }

        //     // Create User System Exercises
        //     UserSystemExercise::factory(5)->create(['user_id' => $user->id])->each(function ($userSysEx) {
        //         // Create Sets for each UserSystemExercise
        //         Set::factory(3)->create(['user_system_exercise_id' => $userSysEx->id]);
        //     });
        // });

        // Create Exercise System Defaults
        $exerciseSystems = ['Push', 'Pull', 'Leg', 'Upper', 'Lower', 'Arnold Split'];
        foreach ($exerciseSystems as $systemName) {
            ExerciseSystemDefault::create(['name' => $systemName]);
        }

        // // Create Exercise System Customizeds
        // User::all()->each(function ($user) {
        //     ExerciseSystemCustomized::factory(2)->create(['user_id' => $user->id]);
        // });
    }
}
