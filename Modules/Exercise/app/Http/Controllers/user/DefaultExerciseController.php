<?php

namespace Modules\Exercise\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Exercise\Http\Requests\SearchMuscleRequest;
use Modules\Exercise\Models\DefaultExercise;
use Modules\Exercise\Transformers\DefaultExerciseResource;

class DefaultExerciseController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all the default exercises with related muscles using the pivot table
        $exercises = DefaultExercise::get();

        return $this->apiResponse($exercises, 200, "Exercises with related muscles retrieved successfully.");
    }
    public function search(SearchMuscleRequest $request)
    {
        $exercises = DefaultExercise::where('name', 'like', '%' . $request->name . '%')
            ->get();
        return $this->apiResponse(DefaultExerciseResource::collection($exercises), 200, "ok");
    }
}
