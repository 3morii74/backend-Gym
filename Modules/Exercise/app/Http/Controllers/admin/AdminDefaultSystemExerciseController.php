<?php

namespace Modules\Exercise\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Exercise\Http\Requests\DeleteDefaultSystemExerciseRequest;
use Modules\Exercise\Http\Requests\StoreDefaultSystemExerciseRequest;
use Modules\Exercise\Http\Requests\UpdateDefaultSystemExerciseRequest;
use Modules\Exercise\Models\DefaultExercise;
use Modules\Exercise\Models\ExerciseSystemDefault;

class AdminDefaultSystemExerciseController extends Controller
{
    use ApiResponseTrait;

    // Create a new exercise system and attach a default exercise
    public function store(StoreDefaultSystemExerciseRequest $request)
    {
        // Check if an exercise system with the same name already exists
        $system = ExerciseSystemDefault::where('name', $request->name)
            ->whereNull('deleted_at')
            ->first();
        if (!$system) {
            // If it doesn't exist, create a new exercise system
            $system = ExerciseSystemDefault::create($request->all());
        }


        // Attach the default exercise to the system
        // Check if the exercise is already attached to avoid duplicates
        if (!$system->defaultExercises()->where('exerciseable_id', $request->default_exerciseId)->exists()) {
            $system->defaultExercises()->attach($request->default_exerciseId, [
                'exerciseable_type' => DefaultExercise::class,

                'exerciseable_id' => $request->default_exerciseId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $this->apiResponse($system, 200, "Default Exercise System retrieved/created successfully with attached exercise.");
    }

    // Update an existing exercise system and attach or update the default exercise
    public function update(UpdateDefaultSystemExerciseRequest $request)
    {

        // Find the exercise system by ID
        $system = ExerciseSystemDefault::findOrFail($request->id);
        $system->update($request->only('name', 'description'));

        // // If a default exercise ID is provided, attach or sync it
        // if ($request->filled('default_exerciseId')) {
        //     $system->defaultExercises()->sync([$request->default_exerciseId], false);
        // }

        return $this->apiResponse($system, 200, "Default Exercise System updated successfully.");
    }

    // Delete an exercise system and detach all associated exercises
    public function destroy(DeleteDefaultSystemExerciseRequest $request)
    {


        // Find the exercise system by ID
        $system = ExerciseSystemDefault::findOrFail($request->id);

        // Detach all associated default exercises
        $system->defaultExercises()->detach();

        // Delete the exercise system
        $system->delete();

        return $this->apiResponse(null, 204, "Default Exercise System deleted successfully.");
    }
}
