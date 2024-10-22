<?php

namespace Modules\Exercise\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Exercise\Http\Requests\DeleteCustomizedExerciseRequest;
use Modules\Exercise\Http\Requests\SearchMuscleRequest;
use Modules\Exercise\Http\Requests\StoreCustomizedExerciseRequest;
use Modules\Exercise\Http\Requests\UpdateCustomizedExerciseRequest;
use Modules\Exercise\Models\CustomizedExercise;
use Modules\Exercise\Transformers\CustomizedExerciseResource;

class CustomizedExerciseController extends Controller
{
    use ApiResponseTrait;


    public function index()
    {
        // Fetch all the customized exercises with related muscles
        $exercises = CustomizedExercise::get();

        return $this->apiResponse($exercises, 200, "Customized Exercises retrieved successfully.");
    }
    public function getAllWithUsers()
    {
        // Fetch all customized exercises with the related users
        $exercises = CustomizedExercise::with('user')->get();

        return $this->apiResponse($exercises, 200, "Customized Exercises with Users retrieved successfully.");
    }

    public function search(SearchMuscleRequest $request)
    {
        $exercises = CustomizedExercise::where('name', 'like', '%' . $request->name . '%')
            ->get();
        return $this->apiResponse(CustomizedExerciseResource::collection($exercises), 200, "ok");
    }

    public function store(StoreCustomizedExerciseRequest $request)
    {
        try {
            // Create a new customized exercise, including muscle_id
            $exercise = CustomizedExercise::create($request->all());

            // Attach the muscle using the muscle_id from the request (for the pivot table)
            $exercise->muscles()->attach($request->muscle_id, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $this->apiResponse($exercise, 201, "Exercise created successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }

    public function update(UpdateCustomizedExerciseRequest $request)
    {
        try {
            // Find the customized exercise by ID
            $exercise = CustomizedExercise::findOrFail($request->id);

            // Update the exercise (excluding muscle_id)
            $exercise->update($request->all());

            // Update the pivot table if muscle_id is provided
            if ($request->has('muscle_id')) {
                $exercise->muscles()->sync([$request->muscle_id]);
            }

            return $this->apiResponse($exercise, 200, "Exercise updated successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }

    public function destroy(DeleteCustomizedExerciseRequest $request)
    {
        try {
            $exercise = CustomizedExercise::findOrFail($request->id); // Find the exercise by ID

            // Detach muscles from pivot table
            $exercise->muscles()->detach();

            // Soft delete the exercise
            $exercise->delete();

            return $this->apiResponse($exercise, 204, "Exercise deleted successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
}
