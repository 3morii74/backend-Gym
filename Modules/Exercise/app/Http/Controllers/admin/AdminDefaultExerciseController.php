<?php

namespace Modules\Exercise\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Exercise\Http\Requests\DeleteDefaultExerciseRequest;
use Modules\Exercise\Http\Requests\StoreDefaultExerciseRequest;
use Modules\Exercise\Http\Requests\UpdateDefaultExerciseRequest;
use Modules\Exercise\Models\DefaultExercise;

class AdminDefaultExerciseController extends Controller
{

    use ApiResponseTrait;


    public function store(StoreDefaultExerciseRequest $request)
    {
        try {
            // Create a new default exercise, including muscle_id
            $exercise = DefaultExercise::create($request->all()); // Include muscle_id

            // Attach the muscle using the muscle_id from the request (in case you still want the pivot table relationship)
            $exercise->muscles()->attach($request->muscle_id, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $this->apiResponse($exercise, 201, "Exercise created successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }

    public function update(UpdateDefaultExerciseRequest $request)
    {
        try {
            // Find the default exercise by ID
            $exercise = DefaultExercise::findOrFail($request->id);

            // Update the fields on the DefaultExercise model
            $exercise->update($request->all()); // Exclude muscle_id to avoid direct update on the model

            // Update the pivot table
            if ($request->has('muscle_id')) {
                // Sync the muscle in the pivot table
                $exercise->muscles()->sync([$request->muscle_id]);
            }

            return $this->apiResponse($exercise, 200, "Exercise updated successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }


    public function destroy(DeleteDefaultExerciseRequest $request)
    {
        try {
            $exercise = DefaultExercise::findOrFail($request->id); // Find the exercise by ID
            
            // Detach the related muscles from the pivot table
            $exercise->muscles()->detach();
            
            // Now delete the exercise
            $exercise->delete(); // Soft delete the exercise
            
            return $this->apiResponse($exercise, 204, "Exercise deleted successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
}
