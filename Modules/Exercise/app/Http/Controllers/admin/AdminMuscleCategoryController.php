<?php

namespace Modules\Exercise\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Exercise\Http\Requests\DeleteMuscleCategoryRequest;
use Modules\Exercise\Http\Requests\StoreMuscleCategoryRequest;
use Modules\Exercise\Http\Requests\UpdateMuscleCategoryRequest;
use Modules\Exercise\Models\MuscleCategory;
use Modules\Exercise\Transformers\MuscleCategoryResource;

class AdminMuscleCategoryController extends Controller
{
    use ApiResponseTrait;


    public function store(StoreMuscleCategoryRequest $request)
    {
        try {
            $muscleCategory = MuscleCategory::create($request->all()); // Create a new muscle category
            return $this->apiResponse($muscleCategory, 201, "Muscle category created successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }

    public function update(UpdateMuscleCategoryRequest $request)
    {
        try {
            $muscleCategory = MuscleCategory::findOrFail($request->id); // Find the muscle category by ID
            $muscleCategory->update($request->all()); // Update the muscle category
            return $this->apiResponse($muscleCategory, 200, "Muscle category updated successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }

    public function destroy(DeleteMuscleCategoryRequest $request)
    {
        try {
            $muscleCategory = MuscleCategory::findOrFail($request->id); // Find the muscle category by ID
            $muscleCategory->delete(); // Delete the muscle category
            return $this->apiResponse($muscleCategory, 204, "Muscle category deleted successfully");
        } catch (ValidationException $e) {
            return $this->apiResponse($e->errors(), 422, "Validation Error");
        }
    }
}
