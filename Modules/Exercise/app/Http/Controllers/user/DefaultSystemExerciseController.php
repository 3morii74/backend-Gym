<?php

namespace Modules\Exercise\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Exercise\Http\Requests\SearchMuscleRequest;
use Modules\Exercise\Models\ExerciseSystemDefault;
use Modules\Exercise\Transformers\ExerciseSystemResource;

class DefaultSystemExerciseController extends Controller
{
    use ApiResponseTrait;

    // Fetch all default exercise systems
    public function index()
    {
        $systems = ExerciseSystemDefault::with('defaultExercises')->get();
        return $this->apiResponse($systems, 200, "Default Exercise Systems retrieved successfully.");
    }



    /**
     * Store a newly created resource in storage.
     */
    public function search(SearchMuscleRequest $request)
    {
        $exercises = ExerciseSystemDefault::where('name', 'like', '%' . $request->name . '%')
            ->get();
        return $this->apiResponse(ExerciseSystemResource::collection($exercises), 200, "ok");
    }
}
