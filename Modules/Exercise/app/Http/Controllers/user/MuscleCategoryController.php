<?php

namespace Modules\Exercise\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Exercise\Http\Requests\MuscleByCategoryRequest;
use Modules\Exercise\Http\Requests\SearchMuscleRequest;
use Modules\Exercise\Models\Muscle;
use Modules\Exercise\Models\MuscleCategory;
use Modules\Exercise\Transformers\MuscleCategoryResource;

class MuscleCategoryController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $muscleCategories = MuscleCategory::all(); // Fetch all muscle categories
        return $this->apiResponse($muscleCategories, 200, "ok");
    }
    public function indexCategoryWithMuscles()
    {
        $muscleCategories = MuscleCategory::with('muscles')->get(); // Fetch all muscle categories with their muscles
        return $this->apiResponse($muscleCategories, 200, "ok");
    }

    public function search(SearchMuscleRequest $request)
    {
        $MuscleCategory = MuscleCategory::where('name', 'like', '%' . $request->name . '%')
            ->get();
        return $this->apiResponse(MuscleCategoryResource::collection($MuscleCategory), 200, "ok");
    }
    /**
     * Display a listing of the muscles for a specific category.
     */
    public function getMusclesByCategory(MuscleByCategoryRequest $request)
    {
        // Retrieve muscles belonging to the specified category
        $muscles = Muscle::where('muscle_category_id', $request->id)->get();

        return response()->json($muscles, 200); // OK
    }
}
