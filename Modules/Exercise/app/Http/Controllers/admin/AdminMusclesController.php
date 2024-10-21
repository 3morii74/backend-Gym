<?php

namespace Modules\Exercise\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Exercise\Http\Requests\StoreMuscleRequest;
use Modules\Exercise\Models\Muscle;
use Illuminate\Validation\ValidationException;
use Modules\Exercise\Http\Requests\DeleteMuscleCategoryRequest;
use Modules\Exercise\Http\Requests\DestroyMuscleRequest;
use Modules\Exercise\Http\Requests\UpdateMuscleRequest;

class AdminMusclesController extends Controller
{


    use ApiResponseTrait;


    public function store(StoreMuscleRequest $request)
    {
        try {
            $muscle = Muscle::create($request->all()); // Create a new muscle category
            return $this->apiResponse($muscle, 201, "Muscle category created successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }

    public function update(UpdateMuscleRequest $request)
    {
        try {
            $muscle = Muscle::findOrFail($request->id); // Find the muscle category by ID
            $muscle->update($request->all()); // Update the muscle category
            return $this->apiResponse($muscle, 200, "Muscle updated successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }

    public function destroy(DestroyMuscleRequest $request)
    {
        try {
            $muscle = Muscle::findOrFail($request->id); // Find the muscle category by ID
            $muscle->delete(); // Delete the muscle category
            return $this->apiResponse($muscle, 204, "Muscle deleted successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
}
