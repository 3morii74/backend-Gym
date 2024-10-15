<?php

namespace Modules\Location\Http\Controllers\api\user\city;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Location\Http\Requests\SearchCountryRequest;
use Modules\Location\Repositories\Interface\LocationRepositoryInterface;
use Modules\Location\Transformers\CityResource;

class cityController extends Controller
{
    use ApiResponseTrait;
    protected $LocationRepository;

    public function __construct(LocationRepositoryInterface $LocationRepository)
    {
        $this->LocationRepository = $LocationRepository;
    }
    public function index(Request $request)
    {
        $cities = $this->LocationRepository->allActiveCity($request);
        return $this->apiResponse(CityResource::collection($cities), 200, "ok");
    }
    public function search(SearchCountryRequest $request)
    {
        $cities = $this->LocationRepository->searchAllActiveCity($request);
        return $this->apiResponse(CityResource::collection($cities), 200, "ok");
    }
}
