<?php

namespace Modules\Exercise\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Exercise\Http\Requests\DeleteSetsRequest;
use Modules\Exercise\Http\Requests\IndexOneSetRequest;
use Modules\Exercise\Http\Requests\IndexSetsRequest;
use Modules\Exercise\Http\Requests\StoreSetsRequest;
use Modules\Exercise\Http\Requests\UpdateSetsRequest;
use Modules\Exercise\Models\Set;

class UserSetsController extends Controller
{
    use ApiResponseTrait;

    // List all sets for a given user exercise
    public function index(IndexSetsRequest $request)
    {
        try {
            $sets = Set::with(['userExercise.defaultExercise']) // Eager load DefaultExercise
                ->where('user_id', $request->user_id)
                ->get();
            return $this->apiResponse($sets, 200, "Sets indexed successfully.");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 500, "Validation Error");
        }
    }
    public function getOne(IndexOneSetRequest $request)
    {
        try {
            $sets = Set::with(['userExercise.defaultExercise']) // Eager load DefaultExercise
                ->where('id', $request->id)
                ->get();
            return $this->apiResponse($sets, 200, "Sets indexed successfully.");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 500, "Validation Error");
        }
    }

    // Store a new set
    public function store(StoreSetsRequest $request)
    {

        try {
            $set = Set::create($request->only(['user_id', 'user_exercise_id', 'reps', 'weight']));
            return $this->apiResponse($set, 201, "Set created successfully.");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 500, "Validation Error");
        }
    }

    // Update a set
    public function update(UpdateSetsRequest $request)
    {
        try {
            $set = Set::findOrFail($request->id);
            $set->update($request->only(['reps', 'weight']));

            return $this->apiResponse($set, 200, "Set updated successfully.");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 500, "Validation Error");
        }
    }

    // Delete a set
    public function destroy(DeleteSetsRequest $request)
    {
        try {
            $set = Set::findOrFail($request->id);
            $set->delete();
            return $this->apiResponse(null, 200, "Set deleted successfully.");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 500, "Error deleting set:" . $e->getMessage());
        }
    }
}
