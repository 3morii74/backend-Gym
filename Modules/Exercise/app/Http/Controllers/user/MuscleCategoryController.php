<?php

namespace Modules\Exercise\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
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

    public function search(Request $request)
    {
        $MuscleCategory = MuscleCategory::where('name', 'like', '%' . $request->name . '%')
            ->get();
        return $this->apiResponse(MuscleCategoryResource::collection($MuscleCategory), 200, "ok");
    }
}
