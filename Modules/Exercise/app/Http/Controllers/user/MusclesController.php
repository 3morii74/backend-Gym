<?php

namespace Modules\Exercise\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Exercise\Http\Requests\SearchMuscleRequest;
use Modules\Exercise\Models\Muscle;
use Modules\Exercise\Transformers\MuscleResource;

class MusclesController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $muscle = Muscle::all(); // Fetch all muscle categories
        return $this->apiResponse($muscle, 200, "ok");
    }
    public function musclesWithExercise()
    {
        // Fetch all the default exercises with related muscles using the pivot table
        $muscle = Muscle::with('defaultExercises')->get();

        return $this->apiResponse($muscle, 200, "Muscle with related Exercises retrieved successfully.");
    }
    public function musclesWithCustomizedExercise()
    {
        // Fetch all the default exercises with related muscles using the pivot table
        $muscle = Muscle::with('customizedExercises')->get();

        return $this->apiResponse($muscle, 200, "Muscle with related Customized Exercises retrieved successfully.");
    }

    public function search(SearchMuscleRequest $request)
    {
        $muscle = Muscle::where('name', 'like', '%' . $request->name . '%')
            ->get();
        return $this->apiResponse(MuscleResource::collection($muscle), 200, "ok");
    }

}
