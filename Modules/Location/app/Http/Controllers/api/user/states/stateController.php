<?php

namespace Modules\Location\Http\Controllers\api\user\states;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Location\Repositories\Interface\LocationRepositoryInterface;
use Modules\Location\Transformers\StateResource;

class stateController extends Controller
{
    use ApiResponseTrait;
    protected $LocationRepository;

    public function __construct(LocationRepositoryInterface $LocationRepository)
    {
        $this->LocationRepository = $LocationRepository;
    }
    public function index(Request $request)
    {
        $states = $this->LocationRepository->allActiveState($request);
        return $this->apiResponse(StateResource::collection($states), 200, "ok");
    }
    public function search(Request $request)
    {
        $states = $this->LocationRepository->searchAllActiveState($request);
        return $this->apiResponse(StateResource::collection($states), 200, "ok");
    }
    public function getCity(Request $request)
    {

        $cities = $this->LocationRepository->getCityFromState($request);
        return $this->apiResponse(StateResource::collection($cities), 200, "ok");
    }
}
